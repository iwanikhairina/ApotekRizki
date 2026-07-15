<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KurirPengirimanController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.obat'])
            ->where('status', 'dikirim')
            ->where('kurir_id', auth()->id())
            ->latest()
            ->first(); // hanya 1 pengiriman aktif yang mungkin ada

        return view('kurir.pengiriman', compact('pesanan'));
    }

    public function selesai($id)
    {
        $kurir = auth()->user();

        if (! $kurir->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $kurir->shiftLabel() . '). Tidak bisa menyelesaikan pengantaran saat ini.');
        }

        $pesanan = Pesanan::where('kurir_id', $kurir->id)->findOrFail($id);
        $pesanan->update(['status' => 'selesai']);

        Notifikasi::create([
            'user_id'    => $pesanan->user_id,
            'pesanan_id' => $pesanan->id,
            'judul'      => 'Pesanan Selesai Diantar',
            'pesan'      => 'Pesanan P' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) . ' telah selesai diantar oleh kurir. Terima kasih telah berbelanja di Apotek Rizki!',
        ]);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pesanan telah ditandai selesai diantar.');
    }

    public function batal(Request $request, $id)
{
    $kurir = auth()->user();

    $request->validate([
        'alasan' => ['required', 'in:pelanggan_tidak_ada,alamat_tidak_ditemukan,pelanggan_batal,kendala_kendaraan,lainnya'],
        'catatan_tambahan' => ['nullable', 'string', 'max:500'],
    ]);

    $labelAlasan = match($request->alasan) {
        'pelanggan_tidak_ada'     => 'Pelanggan tidak ada di tempat',
        'alamat_tidak_ditemukan'  => 'Alamat tidak ditemukan',
        'pelanggan_batal'         => 'Pelanggan membatalkan pesanan',
        'kendala_kendaraan'       => 'Kendala kendaraan/teknis kurir',
        'lainnya'                 => 'Alasan lainnya',
        default                   => 'Tidak diketahui',
    };

    $catatanLengkap = $labelAlasan;
    if ($request->catatan_tambahan) {
        $catatanLengkap .= ' — ' . $request->catatan_tambahan;
    }

    $pesanan = Pesanan::where('kurir_id', $kurir->id)->findOrFail($id);
    $pesanan->update([
        'status'        => 'dibatalkan_kurir',
        'alasan_batal'  => $catatanLengkap,
    ]);

    Notifikasi::create([
        'user_id'    => $pesanan->user_id,
        'pesanan_id' => $pesanan->id,
        'judul'      => 'Pengantaran Dibatalkan',
        'pesan'      => 'Pengantaran untuk pesanan P' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) . ' dibatalkan oleh kurir. Alasan: ' . $labelAlasan . '. Silakan hubungi Apotek Rizki untuk informasi lebih lanjut.',
    ]);

    return redirect()->route('kurir.riwayat')->with('success', 'Pengiriman dibatalkan. Pelanggan telah diberi notifikasi.');
}
}