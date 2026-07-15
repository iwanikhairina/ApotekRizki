<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class KurirRiwayatController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')
            ->whereIn('status', ['selesai', 'dibatalkan_kurir'])
            ->where('kurir_id', auth()->id())
            ->latest()
            ->get();

        return view('kurir.riwayat', compact('pesanan'));
    }
}