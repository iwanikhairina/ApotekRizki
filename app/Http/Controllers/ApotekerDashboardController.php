<?php

namespace App\Http\Controllers;

class ApotekerDashboardController extends Controller
{
    public function index()
    {
        $totalPesananHariIni = \App\Models\Pesanan::whereDate('created_at', now())->count();
        $menungguVerifikasi  = \App\Models\Pesanan::where('status', 'menunggu_verifikasi')->count();
        $sedangDiproses      = \App\Models\Pesanan::where('status', 'diproses')->count();

        return view('apoteker.dashboard', compact(
            'totalPesananHariIni',
            'menungguVerifikasi',
            'sedangDiproses'
        ));
    }
}