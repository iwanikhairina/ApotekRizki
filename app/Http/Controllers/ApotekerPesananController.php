<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\User;

class ApotekerPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')->latest()->get();

        return view('apoteker.pesanan', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.obat'])->findOrFail($id);

        return view('apoteker.pesanan-detail', compact('pesanan'));
    }

    public function terima($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_resep === 'menunggu') {
            return back()->with('shift_error', 'Pesanan ini punya resep/KTP yang belum diverifikasi. Verifikasi dulu di menu Verifikasi Obat sebelum diterima.');
        }

        $pesanan->update(['status' => 'diproses']);

        return back()->with('success', 'Pesanan berhasil diterima dan sedang diproses.');
    }

    public function tolak($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'ditolak']);

        return back()->with('success', 'Pesanan telah ditolak.');
    }

    public function proses($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'disiapkan']);

        return back()->with('success', 'Obat sedang disiapkan.');
    }

    public function siapDikirim($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'siap_dikirim']);

        // Beri tahu semua kurir aktif bahwa ada pesanan baru yang bisa diambil.
        $kodePesanan = 'P' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT);
        $kurirAktif = User::where('role', 'kurir')->where('is_active', true)->get();

        foreach ($kurirAktif as $kurir) {
            Notifikasi::create([
                'user_id'    => $kurir->id,
                'pesanan_id' => $pesanan->id,
                'judul'      => 'Pesanan Siap Diantar',
                'pesan'      => 'Pesanan ' . $kodePesanan . ' sudah siap dan menunggu diambil untuk diantar.',
            ]);
        }

        return back()->with('success', 'Pesanan siap untuk dikirim.');
    }
}