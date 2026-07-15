<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->filled('dari')
            ? Carbon::parse($request->dari)->startOfDay()
            : now()->startOfMonth();

        $tanggalSelesai = $request->filled('sampai')
            ? Carbon::parse($request->sampai)->endOfDay()
            : now()->endOfDay();

        $pesananSelesai = Pesanan::with('user')
            ->where('status', 'selesai')
            ->whereBetween('updated_at', [$tanggalMulai, $tanggalSelesai])
            ->orderByDesc('updated_at')
            ->get();

        // ===== RINGKASAN =====
        $totalPendapatan = $pesananSelesai->sum(fn ($p) => $p->totalKeseluruhan());
        $totalOngkir     = $pesananSelesai->sum('ongkir');
        $totalPesanan    = $pesananSelesai->count();
        $rataRata        = $totalPesanan > 0 ? $totalPendapatan / $totalPesanan : 0;

        // ===== GRAFIK PENDAPATAN HARIAN =====
        $grafikHarian = [];
        $periode = Carbon::parse($tanggalMulai)->toPeriod($tanggalSelesai, '1 day');
        foreach ($periode as $tanggal) {
            $tgl = $tanggal->format('Y-m-d');
            $grafikHarian[$tgl] = 0;
        }
        foreach ($pesananSelesai as $p) {
            $tgl = $p->updated_at->format('Y-m-d');
            if (isset($grafikHarian[$tgl])) {
                $grafikHarian[$tgl] += $p->totalKeseluruhan();
            }
        }

        // ===== PRODUK TERLARIS =====
$produkTerlaris = DetailPesanan::selectRaw('obat_id, SUM(jumlah) as total_terjual, SUM(jumlah * harga_satuan) as total_pendapatan')
    ->whereHas('pesanan', function ($q) use ($tanggalMulai, $tanggalSelesai) {
        $q->where('status', 'selesai')->whereBetween('updated_at', [$tanggalMulai, $tanggalSelesai]);
    })
    ->with('obat:id,nama,klasifikasi')   // <-- ganti kategori jadi klasifikasi
    ->groupBy('obat_id')
    ->orderByDesc('total_terjual')
    ->limit(5)
    ->get();

// ===== PENDAPATAN PER KATEGORI (KLASIFIKASI) =====
$pendapatanPerKategori = DetailPesanan::selectRaw('obat.klasifikasi as kategori, SUM(detail_pesanan.jumlah * detail_pesanan.harga_satuan) as total')
    ->join('obat', 'obat.id', '=', 'detail_pesanan.obat_id')
    ->whereHas('pesanan', function ($q) use ($tanggalMulai, $tanggalSelesai) {
        $q->where('status', 'selesai')->whereBetween('updated_at', [$tanggalMulai, $tanggalSelesai]);
    })
    ->groupBy('obat.klasifikasi')
    ->orderByDesc('total')
    ->get();

        return view('admin.laporan.index', [
            'pesananSelesai'        => $pesananSelesai->take(50), // batasi tabel biar tidak berat
            'totalPendapatan'       => $totalPendapatan,
            'totalOngkir'           => $totalOngkir,
            'totalPesanan'          => $totalPesanan,
            'rataRata'              => $rataRata,
            'grafikHarian'          => $grafikHarian,
            'produkTerlaris'        => $produkTerlaris,
            'pendapatanPerKategori' => $pendapatanPerKategori,
            'tanggalMulai'          => $tanggalMulai->format('Y-m-d'),
            'tanggalSelesai'        => $tanggalSelesai->format('Y-m-d'),
        ]);
    }
}