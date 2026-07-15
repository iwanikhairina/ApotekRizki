<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PesananController extends Controller
{
    /**
     * Peta status asli di tabel `pesanan` -> status tampilan yang dipahami
     * view customer.pesanan / customer.pesanan-detail (menunggu, diproses,
     * dikirim, terkirim, dibatalkan).
     */
    private const STATUS_MAP = [
        'menunggu_verifikasi' => 'menunggu',
        'diproses'            => 'diproses',
        'disiapkan'           => 'diproses',
        'siap_dikirim'        => 'diproses',
        'dikirim'             => 'dikirim',
        'selesai'             => 'terkirim',
        'ditolak'             => 'dibatalkan',
        'dibatalkan_kurir'    => 'dibatalkan',
    ];

    private const KLASIFIKASI_MAP = [
        'obat_bebas'          => 'bebas',
        'obat_bebas_terbatas' => 'terbatas',
        'obat_keras'          => 'keras',
    ];

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
            'code'             => 'P' . str_pad((string) $pesanan->id, 3, '0', STR_PAD_LEFT),
            'status'           => self::STATUS_MAP[$pesanan->status] ?? 'menunggu',
            'status_resep'     => $pesanan->status_resep,
            'created_at'       => $pesanan->created_at,
            'shipping_address' => $pesanan->alamat,
            'payment_method'   => $pesanan->metode_pembayaran,
            'shipping_cost'    => (float) $pesanan->ongkir,
            'eta_at'           => $etaAt,
            'sisa_menit'       => $sisaMenit,
            'items'            => $items,
            'kurir'            => $kurir,
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
     * Mengubah status dikirim -> selesai, dan otomatis membuat pesanan
     * ini masuk kategori riwayat di halaman list (lihat toOrderArray:
     * status 'selesai' dipetakan ke 'terkirim').
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
}