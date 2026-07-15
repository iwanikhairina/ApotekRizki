<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    // Batas stok dianggap "menipis"
    const BATAS_STOK_MENIPIS = 10;

    // Batas hari sebelum kadaluarsa yang dianggap perlu diwaspadai
    const HARI_PERINGATAN_KADALUARSA = 30;

    public function index()
    {
        $hariIni = now()->toDateString();

        // 1. Total pesanan hari ini (berdasarkan tanggal dibuat)
        $totalPesananHariIni = Pesanan::whereDate('created_at', $hariIni)->count();

        // 2. Total pendapatan hari ini (pesanan yang SELESAI dan selesai-nya hari ini)
        $totalPendapatanHariIni = Pesanan::where('status', 'selesai')
            ->whereDate('updated_at', $hariIni)
            ->sum('total_harga');

        // 3. Jumlah staff aktif (apoteker & kurir yang is_active = true)
        $jumlahApotekerAktif = User::where('role', 'apoteker')->where('is_active', true)->count();
        $jumlahKurirAktif = User::where('role', 'kurir')->where('is_active', true)->count();
        $totalStaffAktif = $jumlahApotekerAktif + $jumlahKurirAktif;

        // 4. Pesanan per status (untuk grafik/angka)
        $pesananPerStatus = Pesanan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Susun ulang supaya semua status selalu tampil walau jumlahnya 0
        $statusList = [
            'menunggu_verifikasi',
            'diproses',
            'ditolak',
            'disiapkan',
            'siap_dikirim',
            'dikirim',
            'selesai',
            'dibatalkan_kurir',
        ];
        $rekapStatus = [];
        foreach ($statusList as $status) {
            $rekapStatus[$status] = $pesananPerStatus[$status] ?? 0;
        }

        // 5. Stok obat menipis
        $obatStokMenipis = Obat::where('stok', '<=', self::BATAS_STOK_MENIPIS)
            ->orderBy('stok', 'asc')
            ->get();

        // 6. Obat yang mendekati kadaluarsa (30 hari ke depan), urut FEFO —
        //    yang paling dekat kadaluarsa tampil paling atas supaya diprioritaskan.
        $obatMendekatiKadaluarsa = Obat::whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '<=', now()->addDays(self::HARI_PERINGATAN_KADALUARSA))
            ->where('stok', '>', 0)
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();

        return view('admin.dashboard', [
            'totalPesananHariIni'      => $totalPesananHariIni,
            'totalPendapatanHariIni'   => $totalPendapatanHariIni,
            'jumlahApotekerAktif'      => $jumlahApotekerAktif,
            'jumlahKurirAktif'         => $jumlahKurirAktif,
            'totalStaffAktif'          => $totalStaffAktif,
            'rekapStatus'              => $rekapStatus,
            'obatStokMenipis'          => $obatStokMenipis,
            'obatMendekatiKadaluarsa'  => $obatMendekatiKadaluarsa,
        ]);
    }
}