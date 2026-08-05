<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class KurirPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')
            ->where('status', 'siap_dikirim')
            ->whereNull('kurir_id')
            ->latest()
            ->get();

        return view('kurir.pesanan', compact('pesanan'));
    }

public function show($id)
{
    $pesanan = Pesanan::with([
        'user:id,name,phone,alamat,latitude,longitude',
        'detailPesanan.obat'
    ])->findOrFail($id);

    return view('kurir.pesanan-detail', compact('pesanan'));
}

    public function ambil($id)
    {
        $kurir = auth()->user();

        if (! $kurir->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $kurir->shiftLabel() . '). Tidak bisa mengambil pesanan saat ini.');
        }

        // Kalau kurir sedang di tengah perjalanan mengantar batch, jangan
        // biarkan ambil pesanan baru dulu.
        $sedangAntar = \App\Models\PengirimanBatch::where('kurir_id', $kurir->id)
            ->where('status', 'berjalan')
            ->exists();

        if ($sedangAntar) {
            return back()->with('shift_error', 'Kamu masih punya batch pengiriman yang sedang berjalan. Selesaikan dulu semua rumah di batch itu sebelum mengambil pesanan baru.');
        }

        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'siap_dikirim' || $pesanan->kurir_id) {
            return redirect()->route('kurir.pesanan')->with('shift_error', 'Pesanan ini sudah diambil kurir lain.');
        }

        // Cuma "diklaim" dulu (masuk antrian kurir ini), belum berangkat.
        // Kurir bisa ambil beberapa pesanan lain juga sebelum menekan
        // tombol "Mulai" di halaman Pengiriman — nanti semuanya digabung
        // jadi satu batch dan diurutkan dari rumah terdekat.
        $pesanan->update(['kurir_id' => $kurir->id]);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pesanan P' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) . ' ditambahkan ke antrian pengantaran kamu.');
    }
}