<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PesananController extends Controller
{
    /**
     * Peta status asli di tabel `pesanan` -> status tampilan yang dipahami
     * view customer.pesanan / customer.pesanan-detail (menunggu, diproses,
     * dikirim, terkirim, dibatalkan).
     *
     * 'menunggu_pembayaran' dipetakan ke 'menunggu' juga di tracker, tapi
     * ditandai khusus lewat flag terpisah $order['menunggu_pembayaran']
     * supaya blade bisa menampilkan card pembayaran.
     */
    private const STATUS_MAP = [
        'menunggu_verifikasi' => 'menunggu',
        'menunggu_pembayaran' => 'menunggu',
        'diproses'            => 'diproses',
        'disiapkan'           => 'diproses',
        'siap_dikirim'        => 'diproses',
        'dikirim'             => 'dikirim',
        'selesai'             => 'terkirim',
        'ditolak'             => 'dibatalkan',
        'dibatalkan_kurir'    => 'dibatalkan',
        'dibatalkan_customer' => 'dibatalkan',
    ];

    private const KLASIFIKASI_MAP = [
        'obat_bebas'          => 'bebas',
        'obat_bebas_terbatas' => 'terbatas',
        'obat_keras'          => 'keras',
    ];

    /**
     * Status pesanan yang masih boleh dibatalkan mandiri oleh customer.
     */
    private const STATUS_BOLEH_BATAL = ['menunggu_verifikasi', 'diproses', 'menunggu_pembayaran'];

    /**
     * Ubah satu model Pesanan menjadi array dengan bentuk yang sudah
     * dipakai view (code, status, items, shipping_cost, dst), supaya
     * view lama tidak perlu ditulis ulang.
     */
    private function toOrderArray(Pesanan $pesanan): array
    {
        $items = $pesanan->detailPesanan->map(function ($detail) {
            return [
                'name'       => $detail->obat->nama ?? 'Produk dihapus',
                'qty'        => $detail->jumlah,
                'price'      => $detail->jumlah * $detail->harga_satuan,
                'drug_class' => self::KLASIFIKASI_MAP[$detail->obat->klasifikasi ?? ''] ?? null,
            ];
        })->all();

        $etaAt = $pesanan->estimasiSelesaiAt();
        $sisaMenit = $etaAt ? max(0, (int) round(now()->diffInMinutes($etaAt, false))) : null;

        $kurir = null;
        if ($pesanan->kurir_id && $pesanan->kurir) {
            $kurir = [
                'name'            => $pesanan->kurir->name,
                'phone'           => $pesanan->kurir->phone,
                'jenis_kendaraan' => $pesanan->kurir->jenis_kendaraan,
                'plat_nomor'      => $pesanan->kurir->plat_nomor,
            ];
        }

        return [
            'code'                 => 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT),
            'status'               => self::STATUS_MAP[$pesanan->status] ?? 'menunggu',
            'status_resep'         => $pesanan->status_resep,
            'requires_resep'       => $pesanan->requiresResep(),
            'resep_url'            => $pesanan->resep_path ? Storage::url($pesanan->resep_path) : null,
            'alasan_batal'         => $pesanan->alasan_batal,
            'can_cancel'           => in_array($pesanan->status, self::STATUS_BOLEH_BATAL),
            // BARU: flag & total tagihan untuk card "pilih metode pembayaran"
            // yang muncul setelah apoteker menyetujui resep.
            'menunggu_pembayaran'  => $pesanan->status === 'menunggu_pembayaran',
            'total_harga'          => (float) $pesanan->total_harga,
            'created_at'           => $pesanan->created_at,
            'shipping_address'     => $pesanan->alamat,
            'payment_method'       => $pesanan->metode_pembayaran,
            'shipping_cost'        => (float) $pesanan->ongkir,
            'eta_at'               => $etaAt,
            'sisa_menit'           => $sisaMenit,
            'items'                => $items,
            'kurir'                => $kurir,
        ];
    }

    public function index()
    {
        $orders = Pesanan::with(['detailPesanan.obat', 'kurir'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(fn ($pesanan) => $this->toOrderArray($pesanan))
            ->all();

        return view('customer.pesanan', compact('orders'));
    }

    public function show(string $code)
    {
        // Kode pesanan berformat P001, P002, dst — ambil angka id-nya.
        $id = (int) preg_replace('/\D/', '', $code);

        $pesanan = Pesanan::with(['detailPesanan.obat', 'kurir'])
            ->where('user_id', Auth::id())
            ->find($id);

        if (! $pesanan) {
            abort(Response::HTTP_NOT_FOUND, 'Pesanan tidak ditemukan.');
        }

        $order = $this->toOrderArray($pesanan);

        return view('customer.pesanan-detail', ['order' => $order]);
    }

    /**
     * Customer konfirmasi pesanan sudah diterima secara fisik.
     */
    public function konfirmasiDiterima(string $code)
    {
        $id = (int) preg_replace('/\D/', '', $code);

        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);

        if ($pesanan->status !== 'dikirim') {
            return back()->with('error', 'Pesanan ini belum bisa dikonfirmasi diterima.');
        }

        $pesanan->update(['status' => 'selesai']);

        return redirect()->route('pesanan.detail', $code)
            ->with('success', 'Pesanan telah dikonfirmasi diterima. Terima kasih!');
    }

    /**
     * Customer membatalkan pesanan sendiri, wajib isi alasan.
     * Ditambahkan status 'menunggu_pembayaran' ke daftar yang boleh dibatalkan,
     * supaya customer bisa membatalkan setelah resep disetujui tapi belum
     * memilih metode pembayaran.
     */
    public function batalkan(Request $request, string $code)
    {
        $id = (int) preg_replace('/\D/', '', $code);

        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);

        if (! in_array($pesanan->status, self::STATUS_BOLEH_BATAL)) {
            return back()->with('error', 'Pesanan ini sudah diproses lebih lanjut dan tidak bisa dibatalkan sendiri. Silakan hubungi apotek.');
        }

        $validated = $request->validate([
            'alasan_batal' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $pesanan->update([
            'status'       => 'dibatalkan_customer',
            'alasan_batal' => $validated['alasan_batal'],
        ]);

        return redirect()->route('pesanan.detail', $code)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Customer upload foto resep dokter untuk pesanan yang mengandung obat keras.
     *
     * PERBAIKAN BUG: sebelumnya method ini mengisi status_resep dengan nilai
     * 'menunggu_verifikasi' — nilai ini TIDAK ADA di enum kolom status_resep
     * (yang valid hanya: tidak_perlu, menunggu, disetujui, ditolak), sehingga
     * akan menyebabkan SQL error setiap kali customer upload resep.
     */
    public function uploadResep(Request $request, string $code)
    {
        $id = (int) preg_replace('/\D/', '', $code);

        $pesanan = Pesanan::with('detailPesanan.obat')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if (! $pesanan->requiresResep()) {
            return back()->with('error', 'Pesanan ini tidak memerlukan resep.');
        }

        $request->validate([
            'resep' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        // Hapus file resep lama kalau ada, supaya storage tidak menumpuk file usang.
        if ($pesanan->resep_path) {
            Storage::disk('public')->delete($pesanan->resep_path);
        }

        $path = $request->file('resep')->store('resep', 'public');

        $pesanan->update([
            'resep_path'   => $path,
            'status_resep' => 'menunggu', // <- diperbaiki, sebelumnya 'menunggu_verifikasi'
        ]);

        return redirect()->route('pesanan.detail', $code)
            ->with('success', 'Resep berhasil diunggah, menunggu verifikasi apoteker.');
    }

    /**
     * BARU: customer memilih metode pembayaran (COD/QRIS) setelah apoteker
     * menyetujui resep dan menyusun daftar obat final. Hanya bisa dilakukan
     * saat status pesanan 'menunggu_pembayaran'. Setelah metode dipilih,
     * pesanan lanjut ke status 'siap_dikirim' dan kurir aktif dinotifikasi
     * (notifikasi kurir sengaja tidak dikirim di tahap 'setujui' apoteker,
     * supaya kurir baru bergerak setelah customer benar-benar mengonfirmasi
     * pembayaran).
     */
    public function pilihPembayaran(Request $request, string $code)
    {
        $id = (int) preg_replace('/\D/', '', $code);

        $pesanan = Pesanan::with('detailPesanan.obat')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($pesanan->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Pesanan ini tidak sedang menunggu pembayaran.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:cod,qris'],
        ]);

        $pesanan->update([
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status'            => 'siap_dikirim',
        ]);

        $kodePesanan = 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT);

        $kurirAktif = User::where('role', 'kurir')->where('is_active', true)->get();
        foreach ($kurirAktif as $kurir) {
            Notifikasi::create([
                'user_id'    => $kurir->id,
                'pesanan_id' => $pesanan->id,
                'judul'      => 'Pesanan Siap Diantar',
                'pesan'      => 'Pesanan ' . $kodePesanan . ' sudah dikonfirmasi pembayaran dan siap diambil untuk diantar.',
            ]);
        }

        return redirect()->route('pesanan.detail', $code)
            ->with('success', 'Metode pembayaran dipilih. Pesananmu sedang disiapkan untuk dikirim.');
    }
}