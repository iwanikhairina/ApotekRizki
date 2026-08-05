<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\PengirimanBatch;
use App\Models\Pesanan;
use App\Support\BatchBuilder;
use Illuminate\Http\Request;

class KurirPengirimanController extends Controller
{
    public function index()
    {
        $kurir = auth()->user();

        $batch = PengirimanBatch::with(['pesanan.user', 'pesanan.detailPesanan.obat'])
            ->where('kurir_id', $kurir->id)
            ->where('status', 'berjalan')
            ->latest()
            ->first();

        $stopSaatIni = $batch?->stopSaatIni();

        // Pesanan yang sudah diklaim (kurir_id terisi) tapi belum digabung
        // jadi batch & belum berangkat — menunggu ditekan tombol "Mulai".
        $antrian = collect();
        if (! $batch) {
            $antrian = Pesanan::with('user')
                ->where('kurir_id', $kurir->id)
                ->where('status', 'siap_dikirim')
                ->whereNull('pengiriman_batch_id')
                ->get();
        }

        $jadwalSudahDiatur = filled($kurir->jam_antar_mulai) && filled($kurir->jam_antar_selesai);

        return view('kurir.pengiriman', compact('batch', 'stopSaatIni', 'antrian', 'jadwalSudahDiatur', 'kurir'));
    }

    /**
     * Dipanggil kurir sendiri lewat tombol "Mulai Pengantaran" — dipakai
     * saat owner BELUM mengatur jam antar spesifik untuk kurir ini, jadi
     * kurir mengumpulkan pesanan manual lalu berangkat kapan saja siap.
     * Kalau jam antar sudah diatur owner, batch dibuat otomatis oleh
     * command kurir:tugaskan-batch — tombol ini tidak ditampilkan lagi.
     */
    public function mulai()
    {
        $kurir = auth()->user();

        if (! $kurir->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift (' . $kurir->shiftLabel() . ').');
        }

        $sedangAntar = PengirimanBatch::where('kurir_id', $kurir->id)->where('status', 'berjalan')->exists();
        if ($sedangAntar) {
            return redirect()->route('kurir.pengiriman');
        }

        $antrian = Pesanan::with('user')
            ->where('kurir_id', $kurir->id)
            ->where('status', 'siap_dikirim')
            ->whereNull('pengiriman_batch_id')
            ->get();

        if ($antrian->isEmpty()) {
            return back()->with('shift_error', 'Belum ada pesanan yang kamu ambil. Ambil dulu pesanan dari menu Pesanan.');
        }

        BatchBuilder::mulaiBatch($kurir, $antrian);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pengantaran dimulai! Rute sudah diurutkan dari rumah terdekat.');
    }

    public function selesai($id)
    {
        $kurir = auth()->user();

        if (! $kurir->isOnShiftNow()) {
            return back()->with('shift_error', 'Kamu sedang di luar jam shift ('.$kurir->shiftLabel().'). Tidak bisa menyelesaikan pengantaran saat ini.');
        }

        $pesanan = Pesanan::where('kurir_id', $kurir->id)->findOrFail($id);
        $pesanan->update(['status' => 'selesai']);

        Notifikasi::create([
            'user_id' => $pesanan->user_id,
            'pesanan_id' => $pesanan->id,
            'judul' => 'Pesanan Selesai Diantar',
            'pesan' => 'Pesanan P'.str_pad($pesanan->id, 3, '0', STR_PAD_LEFT).' telah selesai diantar oleh kurir. Terima kasih telah berbelanja di Apotek Rizki!',
        ]);

        $this->tutupBatchJikaSelesai($pesanan->pengiriman_batch_id);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pesanan telah ditandai selesai diantar.');
    }

    public function batal(Request $request, $id)
    {
        $kurir = auth()->user();

        $request->validate([
            'alasan' => ['required', 'in:pelanggan_tidak_ada,alamat_tidak_ditemukan,pelanggan_batal,kendala_kendaraan,lainnya'],
            'catatan_tambahan' => ['nullable', 'string', 'max:500'],
        ]);

        $labelAlasan = match ($request->alasan) {
            'pelanggan_tidak_ada' => 'Pelanggan tidak ada di tempat',
            'alamat_tidak_ditemukan' => 'Alamat tidak ditemukan',
            'pelanggan_batal' => 'Pelanggan membatalkan pesanan',
            'kendala_kendaraan' => 'Kendala kendaraan/teknis kurir',
            'lainnya' => 'Alasan lainnya',
            default => 'Tidak diketahui',
        };

        $catatanLengkap = $labelAlasan;
        if ($request->catatan_tambahan) {
            $catatanLengkap .= ' — '.$request->catatan_tambahan;
        }

        $pesanan = Pesanan::where('kurir_id', $kurir->id)->findOrFail($id);
        $pesanan->update([
            'status' => 'dibatalkan_kurir',
            'alasan_batal' => $catatanLengkap,
        ]);

        Notifikasi::create([
            'user_id' => $pesanan->user_id,
            'pesanan_id' => $pesanan->id,
            'judul' => 'Pengantaran Dibatalkan',
            'pesan' => 'Pengantaran untuk pesanan P'.str_pad($pesanan->id, 3, '0', STR_PAD_LEFT).' dibatalkan oleh kurir. Alasan: '.$labelAlasan.'. Silakan hubungi Apotek Rizki untuk informasi lebih lanjut.',
        ]);

        $this->tutupBatchJikaSelesai($pesanan->pengiriman_batch_id);

        return redirect()->route('kurir.pengiriman')->with('success', 'Pesanan dibatalkan. Lanjut ke rumah berikutnya kalau masih ada.');
    }

    /**
     * Kalau semua pesanan dalam batch ini sudah tuntas (selesai/dibatalkan),
     * tandai batch-nya 'selesai' supaya kurir bebas menerima batch baru lagi
     * di jam antar berikutnya.
     */
    private function tutupBatchJikaSelesai(?int $batchId): void
    {
        if (! $batchId) {
            return;
        }

        $batch = PengirimanBatch::find($batchId);

        if ($batch && $batch->semuaSudahSelesai()) {
            $batch->update(['status' => 'selesai', 'selesai_at' => now()]);
        }
    }
}
