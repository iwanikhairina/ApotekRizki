<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

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

        return view('apoteker.verifikasi-detail', compact('pesanan'));
    }

    public function setujui($id)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa memverifikasi resep saat ini.');
        }

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status_resep' => 'disetujui']);

        return back()->with('success', 'Resep/dokumen pelanggan telah disetujui.');
    }

    public function tolak($id)
    {
        $apoteker = auth()->user();

        if (! $apoteker->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $apoteker->shiftLabel() . '). Tidak bisa memverifikasi resep saat ini.');
        }

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status_resep' => 'ditolak',
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Resep/dokumen pelanggan ditolak, pesanan otomatis dibatalkan.');
    }
}