<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Carbon;

class KurirDashboardController extends Controller
{
    public function index()
    {
        $siapDiantar   = Pesanan::where('status', 'siap_dikirim')->count();
        $sedangDiantar = Pesanan::where('status', 'dikirim')->count();
        $selesaiHariIni = Pesanan::where('status', 'selesai')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        return view('kurir.dashboard', compact(
            'siapDiantar',
            'sedangDiantar',
            'selesaiHariIni'
        ));
    }
}