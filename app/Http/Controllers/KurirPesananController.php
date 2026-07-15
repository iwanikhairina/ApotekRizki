<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class KurirPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')
            ->where('status', 'siap_dikirim')
            ->latest()
            ->get();

        return view('kurir.pesanan', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.obat'])->findOrFail($id);

        return view('kurir.pesanan-detail', compact('pesanan'));
    }

    public function ambil($id)
    {
        $kurir = auth()->user();

        if (! $kurir->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $kurir->shiftLabel() . '). Tidak bisa mengambil pesanan saat ini.');
        }

        // Batasi: kurir hanya boleh punya 1 pengiriman aktif
        $sedangAktif = Pesanan::where('kurir_id', $kurir->id)->where('status', 'dikirim')->exists();

        if ($sedangAktif) {
            return back()->with('shift_error', 'Kamu masih memiliki pengiriman yang sedang berlangsung. Selesaikan pengiriman itu terlebih dahulu sebelum mengambil pesanan baru.');
        }

        $pesanan = Pesanan::findOrFail($id);

        // Cek juga apakah pesanan ini masih tersedia (belum diambil kurir lain)
        if ($pesanan->status !== 'siap_dikirim') {
            return redirect()->route('kurir.pesanan')->with('shift_error', 'Pesanan ini sudah diambil kurir lain.');
        }

        $pesanan->update([
            'status'          => 'dikirim',
            'kurir_id'        => $kurir->id,
            'waktu_diambil'   => now(),
            'estimasi_menit'  => $pesanan->hitungEstimasiMenit(),
        ]);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pesanan berhasil diambil dan sedang diantar.');
    }
}