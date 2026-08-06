<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DetailPesanan;
use App\Models\JadwalPengantaran;
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

        // Area layanan divalidasi berdasarkan kecamatan. Alamat baru sudah
        // dicegah tersimpan kalau di luar area lewat AlamatController, tapi
        // dicek ulang di sini untuk berjaga-jaga terhadap alamat lama yang
        // tersimpan sebelum validasi ini ada.
        if (! DistanceCalculator::areaDilayaniUntukUser($user->kecamatan, $user->alamat)) {
            $kecamatanDilayani = implode(', ', DistanceCalculator::kecamatanDilayani());

            return redirect()->route('cart.index')->with('error',
                'Maaf, alamat pengiriman kamu berada di luar area layanan Apotek Rizki. '
                . 'Saat ini pengantaran hanya melayani Kecamatan ' . $kecamatanDilayani . '.'
            );
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

        // Total belanja (subtotal produk, tidak termasuk ongkir) harus
        // memenuhi minimum sebelum lanjut checkout.
        $minimumBelanja = config('apotek.minimum_belanja', 0);

        if ($subtotal < $minimumBelanja) {
            return redirect()->route('cart.index')->with('error',
                'Oops! Total belanja belum mencukupi. Untuk melanjutkan pemesanan, minimal total belanja adalah '
                . 'Rp' . number_format($minimumBelanja, 0, ',', '.') . '. Yuk, tambahkan produk ke keranjang Anda.'
            );
        }

        $rute = DistanceCalculator::route(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $jarakKm = $rute['jarak_km'];
        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

        // BARU: checkout.blade.php pakai $requiresResep dan $kerasItems untuk
        // menampilkan kartu upload resep, tapi sebelumnya kedua variabel ini
        // tidak pernah dikirim dari sini — bug ini tersembunyi selama ini
        // karena halaman checkout selalu keburu redirect balik duluan akibat
        // masalah ongkir. Sekarang dikirim dengan benar.
        $kerasItems = $cartItems->filter(fn ($item) => $item->obat->perluResep());
        $requiresResep = $kerasItems->isNotEmpty();

        // Jadwal pengantaran yang sudah diatur OWNER (Panel Owner > Jadwal
        // Antar). Hanya slot yang aktif yang ditampilkan sebagai pilihan.
        $jadwalOptions = JadwalPengantaran::aktif()->urut()->get();

        return view('customer.checkout', [
            'cartItems'      => $cartItems,
            'user'           => $user,
            'requiresResep'  => $requiresResep,
            'kerasItems'     => $kerasItems,
            'jadwalOptions'  => $jadwalOptions,
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

        // Total belanja (subtotal produk, tidak termasuk ongkir) harus
        // memenuhi minimum. Dicek ulang di sini (bukan cuma di show())
        // untuk berjaga-jaga kalau customer submit checkout langsung tanpa
        // lewat halaman show(), misalnya lewat request manual.
        $minimumBelanja = config('apotek.minimum_belanja', 0);
        $subtotalCek = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

        if ($subtotalCek < $minimumBelanja) {
            return redirect()->route('cart.index')->with('error',
                'Oops! Total belanja belum mencukupi. Untuk melanjutkan pemesanan, minimal total belanja adalah '
                . 'Rp' . number_format($minimumBelanja, 0, ',', '.') . '. Yuk, tambahkan produk ke keranjang Anda.'
            );
        }

        // Jadwal pengantaran wajib dipilih HANYA kalau owner memang sudah
        // mengatur minimal satu slot aktif. Kalau belum ada slot sama sekali,
        // fitur ini belum "aktif" dipakai toko dan checkout tetap jalan normal.
        $adaJadwalAktif = JadwalPengantaran::aktif()->exists();

        $validated = $request->validate([
            'metode_pembayaran'      => ['required', 'in:cod,qris'],
            'catatan'                => ['nullable', 'string', 'max:1000'],
            'jadwal_pengantaran_id'  => [$adaJadwalAktif ? 'required' : 'nullable', 'exists:jadwal_pengantaran,id'],
        ], [
            'jadwal_pengantaran_id.required' => 'Silakan pilih jadwal pengantaran terlebih dahulu.',
        ]);

        $jadwalTerpilih = $adaJadwalAktif
            ? JadwalPengantaran::aktif()->find($validated['jadwal_pengantaran_id'])
            : null;

        if ($adaJadwalAktif && ! $jadwalTerpilih) {
            return back()->withInput()->with('error', 'Jadwal pengantaran yang kamu pilih sudah tidak tersedia. Silakan pilih ulang.');
        }

        // Area layanan divalidasi berdasarkan kecamatan (bukan radius jarak).
        if (! DistanceCalculator::areaDilayaniUntukUser($user->kecamatan, $user->alamat)) {
            $kecamatanDilayani = implode(', ', DistanceCalculator::kecamatanDilayani());

            return redirect()->route('cart.index')->with('error',
                'Maaf, alamat pengiriman kamu berada di luar area layanan Apotek Rizki. '
                . 'Saat ini pengantaran hanya melayani Kecamatan ' . $kecamatanDilayani . '.'
            );
        }

        // PERBAIKAN: sama seperti show(), pakai DistanceCalculator::route()
        // (jarak jalan sebenarnya via OSRM) supaya jarak & ongkir yang dipakai
        // untuk membuat pesanan konsisten dengan yang ditampilkan di halaman
        // checkout sebelumnya.
        $rute = DistanceCalculator::route(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $jarakKm = $rute['jarak_km'];
        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);
        $estimasiMenit = $rute['estimasi_menit'];

        $requiresResep = $cartItems->contains(fn ($item) => $item->obat->perluResep());

        $pesanan = DB::transaction(function () use ($user, $cartItems, $validated, $jarakKm, $ongkir, $estimasiMenit, $jadwalTerpilih) {

            $totalHarga = $cartItems->sum(fn ($item) => $item->obat->harga * $item->quantity);

            // Status awal SELALU 'menunggu_verifikasi', sama untuk pesanan biasa
            // maupun pesanan yang butuh resep — konsisten dengan STATUS_MAP di
            // PesananController dan dengan alur apoteker (terima/proses/dst).
            // Kolom status_resep TIDAK diisi manual di sini: biarkan default
            // 'tidak_perlu' dari migration. Begitu customer upload resep nanti
            // di halaman detail pesanan, baru diubah jadi 'menunggu'.
            $pesanan = Pesanan::create([
                'user_id'               => $user->id,
                'alamat'                => $user->alamat,
                'jarak_km'              => $jarakKm,
                'ongkir'                => $ongkir,
                'estimasi_menit'        => $estimasiMenit,
                'metode_pembayaran'     => $validated['metode_pembayaran'],
                'catatan'               => $validated['catatan'] ?? null,
                'status'                => 'menunggu_verifikasi',
                'total_harga'           => $totalHarga,
                // Snapshot jadwal pengantaran yang dipilih customer, supaya
                // histori pesanan tetap akurat walau owner nanti mengubah
                // atau menghapus slot master-nya.
                'jadwal_pengantaran_id' => $jadwalTerpilih?->id,
                'jadwal_antar_mulai'    => $jadwalTerpilih?->jam_mulai,
                'jadwal_antar_selesai'  => $jadwalTerpilih?->jam_selesai,
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