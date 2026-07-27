<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Support\DistanceCalculator;
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

        // PERBAIKAN: cek kelengkapan alamat dengan cara yang SAMA seperti
        // CartController ($alamat + $latitude + $longitude), bukan cuma
        // $user->alamat. Kalau alamat sudah diisi tapi belum ter-geocode
        // (lat/long kosong), DistanceCalculator tidak akan bisa jalan.
        $alamatLengkap = $user->alamat && $user->latitude && $user->longitude;

        if (! $alamatLengkap) {
            return redirect()->route('cart.index')
                ->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

        $jarakKm = DistanceCalculator::km(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

        // SEMENTARA: jangan blokir checkout kalau ongkir gagal dihitung
        // (jarak null / di luar tier). Pakai ongkir tier tertinggi sebagai
        // fallback aman, dan catat di log supaya nanti kita cek kenapa
        // perhitungan jaraknya bisa null/di luar jangkauan.
        if (is_null($ongkir)) {
            \Illuminate\Support\Facades\Log::warning('Ongkir fallback dipakai saat checkout.', [
                'user_id'  => $user->id,
                'jarak_km' => $jarakKm,
                'lat'      => $user->latitude,
                'lng'      => $user->longitude,
            ]);

            $ongkir = collect(config('apotek.ongkir_tiers'))->last()['harga'] ?? 15000;
        }

        // BARU: checkout.blade.php pakai $requiresResep dan $kerasItems untuk
        // menampilkan kartu upload resep, tapi sebelumnya kedua variabel ini
        // tidak pernah dikirim dari sini — bug ini tersembunyi selama ini
        // karena halaman checkout selalu keburu redirect balik duluan akibat
        // masalah ongkir. Sekarang dikirim dengan benar.
        $kerasItems = $cartItems->filter(fn ($item) => $item->obat->perluResep());
        $requiresResep = $kerasItems->isNotEmpty();

        return view('customer.checkout', [
            'cartItems'      => $cartItems,
            'user'           => $user,
            'requiresResep'  => $requiresResep,
            'kerasItems'     => $kerasItems,
            'summary'        => [
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

        $alamatLengkap = $user->alamat && $user->latitude && $user->longitude;

        if (! $alamatLengkap) {
            return redirect()->route('cart.index')->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:cod,qris'],
            'catatan'           => ['nullable', 'string', 'max:1000'],
        ]);

        // PERBAIKAN: sama seperti show(), pakai DistanceCalculator langsung
        // supaya jarak & ongkir yang dipakai untuk membuat pesanan konsisten
        // dengan yang ditampilkan di halaman checkout sebelumnya.
        $jarakKm = DistanceCalculator::km(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

        // SEMENTARA: sama seperti show(), pakai fallback ongkir tertinggi
        // kalau perhitungan jarak gagal, supaya customer tetap bisa checkout.
        if (is_null($ongkir)) {
            \Illuminate\Support\Facades\Log::warning('Ongkir fallback dipakai saat membuat pesanan.', [
                'user_id'  => $user->id,
                'jarak_km' => $jarakKm,
                'lat'      => $user->latitude,
                'lng'      => $user->longitude,
            ]);

            $ongkir = collect(config('apotek.ongkir_tiers'))->last()['harga'] ?? 15000;
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
}