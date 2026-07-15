<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DetailPesanan;
use App\Models\Notifikasi;
use App\Models\Obat;
use App\Models\Pesanan;
use App\Models\User;
use App\Support\DistanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * GET /checkout — halaman konfirmasi sebelum pesanan disimpan.
     * Semua angka (jarak, ongkir, total) dihitung ulang di server,
     * tidak dipercaya dari session/keranjang di sisi klien.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::with('obat')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $alamatLengkap = (bool) ($user->alamat && $user->latitude && $user->longitude);

        if (! $alamatLengkap) {
            return redirect()->route('cart.index')
                ->with('error', 'Lengkapi alamat pengiriman terlebih dahulu sebelum checkout.');
        }

        $jarakKm = DistanceCalculator::km(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);
        $bisaDiantar = $ongkir !== null;

        if (! $bisaDiantar) {
            return redirect()->route('cart.index')
                ->with('error', 'Alamat kamu di luar jangkauan pengiriman (maksimal '
                    . config('apotek.radius_maksimum_km') . ' km).');
        }

        // Cek stok saat ini (informasional di halaman ini; validasi keras terjadi lagi di store())
        $itemStokKurang = $cartItems->filter(fn ($item) => $item->quantity > $item->obat->stok);

        if ($itemStokKurang->isNotEmpty()) {
            $daftar = $itemStokKurang->map(fn ($item) => $item->obat->nama . ' (tersisa ' . $item->obat->stok . ')')->implode(', ');
            return redirect()->route('cart.index')
                ->with('error', 'Beberapa produk stoknya tidak mencukupi: ' . $daftar . '. Silakan sesuaikan jumlah di keranjang.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->obat->harga);

        $butuhResep = $cartItems->contains(fn ($item) => $item->obat->perluResep());
        $butuhKtp = $cartItems->contains(fn ($item) => (bool) $item->obat->butuh_ktp);

        $summary = [
            'item_count' => $cartItems->sum('quantity'),
            'subtotal'   => $subtotal,
            'jarak_km'   => $jarakKm,
            'ongkir'     => $ongkir,
            'total'      => $subtotal + $ongkir,
        ];

        return view('customer.checkout', compact('cartItems', 'summary', 'user', 'butuhResep', 'butuhKtp'));
    }

    /**
     * POST /checkout — proses checkout: validasi ulang semua, simpan pesanan +
     * detail_pesanan, kurangi stok, kosongkan keranjang, kirim notifikasi ke apoteker.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::with('obat')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $alamatLengkap = (bool) ($user->alamat && $user->latitude && $user->longitude);
        if (! $alamatLengkap) {
            return redirect()->route('cart.index')->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        $butuhResep = $cartItems->contains(fn ($item) => $item->obat->perluResep());
        $butuhKtp = $cartItems->contains(fn ($item) => (bool) $item->obat->butuh_ktp);

        $rules = [
            'metode_pembayaran' => ['required', 'in:cod,qris'],
            'catatan'           => ['nullable', 'string', 'max:1000'],
        ];

        if ($butuhResep) {
            $rules['resep'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'];
        }
        if ($butuhKtp) {
            $rules['ktp'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'];
        }

        $validated = $request->validate($rules, [
            'resep.required' => 'Ada produk yang memerlukan resep dokter. Silakan unggah foto resep.',
            'ktp.required'   => 'Ada produk yang memerlukan verifikasi KTP. Silakan unggah foto KTP.',
        ]);

        try {
            $pesanan = DB::transaction(function () use ($user, $cartItems, $validated, $request, $butuhResep, $butuhKtp) {
                // Kunci baris obat yang terlibat supaya tidak ada dua checkout bersamaan
                // lolos validasi stok yang sama (race condition).
                $obatIds = $cartItems->pluck('obat_id')->all();
                $obatTerkunci = Obat::whereIn('id', $obatIds)->lockForUpdate()->get()->keyBy('id');

                $errorStok = [];
                foreach ($cartItems as $item) {
                    $obat = $obatTerkunci->get($item->obat_id);
                    if (! $obat || $item->quantity > $obat->stok) {
                        $sisa = $obat->stok ?? 0;
                        $errorStok[] = ($obat->nama ?? 'Produk') . " (diminta {$item->quantity}, tersisa {$sisa})";
                    }
                }

                if (! empty($errorStok)) {
                    throw new \RuntimeException('Stok tidak mencukupi untuk: ' . implode(', ', $errorStok));
                }

                // Hitung ulang jarak & ongkir dari data alamat terbaru user (jangan percaya input klien)
                $jarakKm = DistanceCalculator::km(
                    config('apotek.latitude'),
                    config('apotek.longitude'),
                    $user->latitude,
                    $user->longitude
                );
                $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

                if ($ongkir === null) {
                    throw new \RuntimeException('Alamat kamu di luar jangkauan pengiriman (maksimal '
                        . config('apotek.radius_maksimum_km') . ' km).');
                }

                $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $obatTerkunci->get($item->obat_id)->harga);

                $resepPath = $butuhResep ? $request->file('resep')->store('resep', 'public') : null;
                $ktpPath = $butuhKtp ? $request->file('ktp')->store('ktp', 'public') : null;

                $pesanan = Pesanan::create([
                    'user_id'           => $user->id,
                    'alamat'            => $user->alamat,
                    'jarak_km'          => $jarakKm,
                    'ongkir'            => $ongkir,
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'catatan'           => $validated['catatan'] ?? null,
                    'status'            => 'menunggu_verifikasi',
                    'status_resep'      => ($butuhResep || $butuhKtp) ? 'menunggu' : 'tidak_perlu',
                    'resep_path'        => $resepPath,
                    'ktp_path'          => $ktpPath,
                    'total_harga'       => $subtotal,
                ]);

                foreach ($cartItems as $item) {
                    $obat = $obatTerkunci->get($item->obat_id);

                    DetailPesanan::create([
                        'pesanan_id'   => $pesanan->id,
                        'obat_id'      => $obat->id,
                        'jumlah'       => $item->quantity,
                        'harga_satuan' => $obat->harga,
                    ]);

                    $obat->decrement('stok', $item->quantity);
                }

                CartItem::where('user_id', $user->id)->delete();

                return $pesanan;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Notifikasi ke semua apoteker aktif — di luar transaction, gagal kirim notifikasi
        // tidak boleh membatalkan pesanan yang sudah tersimpan.
        $kodePesanan = 'P' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT);
        $apotekerAktif = User::where('role', 'apoteker')->where('is_active', true)->get();

        foreach ($apotekerAktif as $apoteker) {
            Notifikasi::create([
                'user_id'    => $apoteker->id,
                'pesanan_id' => $pesanan->id,
                'judul'      => 'Pesanan Baru Masuk',
                'pesan'      => 'Pesanan ' . $kodePesanan . ' dari ' . $user->name . ' menunggu diproses.'
                    . ($pesanan->status_resep === 'menunggu' ? ' Perlu verifikasi resep/KTP.' : ''),
            ]);
        }

        return redirect()->route('pesanan.detail', $kodePesanan)
            ->with('success', 'Pesanan ' . $kodePesanan . ' berhasil dibuat. Apoteker kami akan segera memprosesnya.');
    }
}
