<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Notifikasi;
use App\Models\Obat;
use App\Models\Pesanan;
use App\Models\User;
use App\Support\DistanceCalculator;
use Illuminate\Http\Request;

class ApotekerVerifikasiController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.obat'])
            ->whereHas('detailPesanan.obat', function ($query) {
                $query->where('butuh_resep', true)
                    ->orWhere('butuh_ktp', true)
                    ->orWhere('klasifikasi', 'obat_keras');
            })
            ->latest()
            ->get();

        return view('apoteker.verifikasi-obat', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.obat'])->findOrFail($id);
        $daftarObat = Obat::orderBy('nama')->get();

        return view('apoteker.verifikasi-detail', compact('pesanan', 'daftarObat'));
    }

    /**
     * Apoteker menambahkan satu atau lebih obat sekaligus ke pesanan
     * setelah membaca isi resep foto pelanggan. Dikirim sebagai array
     * (obat_id[], jumlah[]) karena satu resep bisa berisi banyak obat.
     * Kalau obat yang sama sudah ada di pesanan, jumlahnya tinggal ditambah.
     */
    public function tambahObat(Request $request, $id)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa mengubah pesanan saat ini.');
        }

        $validated = $request->validate([
            'obat_id'   => ['required', 'array', 'min:1'],
            'obat_id.*' => ['required', 'exists:obat,id'],
            'jumlah'    => ['required', 'array', 'min:1'],
            'jumlah.*'  => ['required', 'integer', 'min:1'],
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $namaDitambahkan = [];

        foreach ($validated['obat_id'] as $index => $obatId) {
            $jumlah = $validated['jumlah'][$index];
            $obat = Obat::findOrFail($obatId);

            $existing = DetailPesanan::where('pesanan_id', $pesanan->id)
                ->where('obat_id', $obat->id)
                ->first();

            if ($existing) {
                $existing->update(['jumlah' => $existing->jumlah + $jumlah]);
            } else {
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'obat_id'      => $obat->id,
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $obat->harga,
                ]);
            }

            $namaDitambahkan[] = $obat->nama . ' (' . $jumlah . ')';
        }

        $this->hitungUlangTotal($pesanan);

        return back()->with('success', implode(', ', $namaDitambahkan) . ' berhasil ditambahkan ke pesanan.');
    }

    /**
     * Hapus satu item obat dari pesanan (mis. salah baca resep / dobel input).
     */
    public function hapusObat($id, $detailId)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa mengubah pesanan saat ini.');
        }

        $pesanan = Pesanan::findOrFail($id);

        $detail = DetailPesanan::where('pesanan_id', $pesanan->id)->findOrFail($detailId);
        $namaObat = $detail->obat->nama ?? 'Obat';
        $detail->delete();

        $this->hitungUlangTotal($pesanan);

        return back()->with('success', $namaObat . ' dihapus dari pesanan.');
    }

    /**
     * Hitung ulang total_harga pesanan berdasarkan seluruh baris detail_pesanan
     * yang ada saat ini (jumlah x harga_satuan). Dipanggil setiap kali apoteker
     * menambah/menghapus obat, supaya total yang dilihat customer selalu akurat.
     */
    private function hitungUlangTotal(Pesanan $pesanan): void
    {
        $pesanan->load('detailPesanan');

        $total = $pesanan->detailPesanan->sum(
            fn ($detail) => $detail->jumlah * $detail->harga_satuan
        );

        $pesanan->update(['total_harga' => $total]);
    }

    /**
     * Pastikan jarak_km & ongkir sudah terisi sebelum pesanan diteruskan ke
     * customer untuk dibayar. Pesanan yang dibuat lewat upload resep langsung
     * (ResepController) bisa saja belum punya jarak_km (misal alamat customer
     * belum lengkap waktu upload). Di sini dihitung ulang pakai alamat customer
     * YANG TERBARU, supaya ongkir yang ditampilkan ke customer selalu akurat —
     * bukan cuma dilewatkan begitu saja sebagai "Jarak belum diatur".
     */
    private function pastikanJarakOngkirTerisi(Pesanan $pesanan): void
    {
        $user = $pesanan->user;

        if (! $user || ! $user->alamat || ! $user->latitude || ! $user->longitude) {
            // Alamat customer masih belum lengkap — biarkan null, nanti
            // customer akan diminta melengkapi alamat sebelum bisa bayar.
            return;
        }

        $rute = DistanceCalculator::route(
            config('apotek.latitude'),
            config('apotek.longitude'),
            $user->latitude,
            $user->longitude
        );

        $jarakKm = $rute['jarak_km'];
        $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);

        $pesanan->update([
            'alamat'         => $user->alamat,
            'jarak_km'       => $jarakKm,
            'ongkir'         => $ongkir,
            'estimasi_menit' => $rute['estimasi_menit'],
        ]);
    }

    /**
     * Setujui resep/dokumen pelanggan. Daftar obat pada pesanan dianggap final
     * (sesuai yang dibaca apoteker dari foto resep). Pesanan TIDAK langsung
     * lanjut ke kurir — status diubah ke 'menunggu_pembayaran' dulu, supaya
     * customer bisa melihat total tagihan final dan memilih metode pembayaran
     * (atau membatalkan) sebelum pesanan diteruskan ke kurir.
     */
    public function setujui(Request $request, $id)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa memverifikasi resep saat ini.');
        }

        $validated = $request->validate([
            'catatan_apoteker' => ['nullable', 'string', 'max:1000'],
        ]);

        $pesanan = Pesanan::with(['detailPesanan.obat', 'user'])->findOrFail($id);

        if ($pesanan->detailPesanan->isEmpty()) {
            return back()->with('shift_error', 'Pesanan ini belum ada obatnya. Tambahkan obat sesuai resep dulu sebelum disetujui.');
        }

        // Pastikan jarak & ongkir terisi (lihat catatan di method-nya) sebelum
        // total_harga dihitung ulang dan dikirim ke customer.
        $this->pastikanJarakOngkirTerisi($pesanan);

        // Pastikan total_harga akurat sebelum dikirim ke customer
        $this->hitungUlangTotal($pesanan);
        $pesanan->refresh();

        $pesanan->update([
            'status_resep'     => 'disetujui',
            'status'           => 'menunggu_pembayaran',
            'catatan_apoteker' => $validated['catatan_apoteker'] ?? $pesanan->catatan_apoteker,
        ]);

        $kodePesanan = 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT);
        $totalTagihan = $pesanan->total_harga + $pesanan->ongkir;

        // Notifikasi ke customer: resep disetujui, tunggu customer pilih pembayaran.
        // Notifikasi ke kurir BELUM dikirim di sini — baru dikirim setelah
        // customer memilih metode pembayaran (lihat PesananController::pilihPembayaran).
        if ($pesanan->user_id) {
            Notifikasi::create([
                'user_id'    => $pesanan->user_id,
                'pesanan_id' => $pesanan->id,
                'judul'      => 'Resep Disetujui — Menunggu Pembayaran',
                'pesan'      => 'Resep untuk pesanan ' . $kodePesanan . ' telah diverifikasi dan disetujui apoteker. '
                    . 'Total tagihan: Rp' . number_format($totalTagihan, 0, ',', '.') . '. '
                    . 'Silakan pilih metode pembayaran di halaman detail pesanan.',
            ]);
        }

        return back()->with('success', 'Resep disetujui. Menunggu customer memilih metode pembayaran.');
    }

    public function tolak(Request $request, $id)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa memverifikasi resep saat ini.');
        }

        $validated = $request->validate([
            'catatan_apoteker' => ['nullable', 'string', 'max:1000'],
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status_resep'     => 'ditolak',
            'status'           => 'ditolak',
            'catatan_apoteker' => $validated['catatan_apoteker'] ?? $pesanan->catatan_apoteker,
        ]);

        $kodePesanan = 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT);

        if ($pesanan->user_id) {
            Notifikasi::create([
                'user_id'    => $pesanan->user_id,
                'pesanan_id' => $pesanan->id,
                'judul'      => 'Resep Ditolak',
                'pesan'      => 'Mohon maaf, resep untuk pesanan ' . $kodePesanan . ' ditolak apoteker dan pesanan dibatalkan.'
                    . ($pesanan->catatan_apoteker ? ' Catatan: ' . $pesanan->catatan_apoteker : ''),
            ]);
        }

        return back()->with('success', 'Resep/dokumen pelanggan ditolak, pesanan otomatis dibatalkan.');
    }
}