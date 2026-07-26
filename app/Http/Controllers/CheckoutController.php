<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $cartItems = CartItem::with('obat')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        if (!$user->alamat) {
            return redirect()->route('cart.index')
                ->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

        // GANTI: pakai jarak_km yang sudah dihitung DistanceCalculator di CartController
        $jarakKm = $user->jarak_km ?? null;
        $ongkir  = $this->hitungOngkir($jarakKm);

        if (is_null($ongkir)) {
            return redirect()->route('cart.index')
                ->with('error', 'Alamat kamu di luar jangkauan pengiriman.');
        }

        return view('customer.checkout', [
            'cartItems' => $cartItems,
            'user'      => $user,
            'summary'   => [
                'subtotal' => $subtotal,
                'ongkir'   => $ongkir,
                'total'    => $subtotal + $ongkir,
                'jarak_km' => $jarakKm,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $cartItems = CartItem::with('obat')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        if (!$user->alamat) {
            return redirect()->route('cart.index')->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:cod,qris'],
            'catatan'           => ['nullable', 'string', 'max:1000'],
        ]);

        // GANTI: pakai jarak_km yang sama dengan yang ditampilkan di halaman checkout
        $jarakKm = $user->jarak_km ?? null;
        $ongkir  = $this->hitungOngkir($jarakKm);

        if (is_null($ongkir)) {
            return redirect()->route('cart.index')->with('error', 'Alamat kamu di luar jangkauan pengiriman.');
        }

        $requiresResep = $cartItems->contains(fn ($item) => $item->obat->perluResep());

        $pesanan = DB::transaction(function () use ($user, $cartItems, $validated, $jarakKm, $ongkir) {

            $totalHarga = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

            // Status awal SELALU 'menunggu_verifikasi', sama untuk pesanan biasa
            // maupun pesanan yang butuh resep — konsisten dengan STATUS_MAP di
            // PesananController dan dengan alur apoteker (terima/proses/dst).
            // Kolom status_resep TIDAK diisi manual di sini: biarkan default
            // 'tidak_perlu' dari migration. Begitu customer upload resep nanti
            // di halaman detail pesanan, baru diubah jadi 'menunggu'.
            $pesanan = Pesanan::create([
                'user_id'           => $user->id,
                'alamat'            => $user->alamat,
                'jarak_km'          => $jarakKm,
                'ongkir'            => $ongkir,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan'           => $validated['catatan'] ?? null,
                'status'            => 'menunggu_verifikasi',
                'total_harga'       => $totalHarga,
            ]);

            foreach ($cartItems as $item) {
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'obat_id'      => $item->obat_id,
                    'jumlah'       => $item->quantity,
                    'harga_satuan' => $item->obat->harga,
                ]);

                $item->obat->decrement('stok', $item->quantity);
            }

            CartItem::where('user_id', $user->id)->delete();

            return $pesanan;
        });

        $pesan = $requiresResep
            ? 'Pesanan berhasil dibuat. Jangan lupa upload resep dokter di halaman detail pesanan ya.'
            : 'Pesanan berhasil dibuat, menunggu konfirmasi apoteker.';

        return redirect()->route('pesanan.detail', 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT))
            ->with('success', $pesan);
    }

    private function hitungOngkir(?float $jarakKm): ?float
    {
        if (is_null($jarakKm)) {
            return null;
        }

        foreach (config('apotek.ongkir_tiers') as $tier) {
            if ($jarakKm <= $tier['max_km']) {
                return $tier['harga'];
            }
        }

        return null; // di luar radius_maksimum_km
    }
}