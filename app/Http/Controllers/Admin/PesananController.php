<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    /**
     * Daftar semua pesanan (semua status), dengan filter status & pencarian.
     */
    public function index(Request $request)
    {
        $filterStatus = $request->query('status', '');
        $filterCari = $request->query('cari', '');

        $query = Pesanan::with(['user', 'kurir']);

        if ($filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        if ($filterCari !== '') {
            $query->where(function ($q) use ($filterCari) {
                $q->where('id', 'like', "%{$filterCari}%")
                    ->orWhereHas('user', function ($u) use ($filterCari) {
                        $u->where('name', 'like', "%{$filterCari}%");
                    });
            });
        }

        $pesanan = $query->latest()->paginate(15)->withQueryString();

        $jumlahPerStatus = Pesanan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.pesanan.index', [
            'pesanan' => $pesanan,
            'jumlahPerStatus' => $jumlahPerStatus,
            'filterStatus' => $filterStatus,
            'filterCari' => $filterCari,
        ]);
    }

    /**
     * Detail satu pesanan, lengkap dengan daftar kurir aktif untuk ditugaskan.
     */
    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'kurir', 'detailPesanan.obat']);

        $daftarKurir = User::where('role', 'kurir')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.pesanan.show', compact('pesanan', 'daftarKurir'));
    }

    /**
     * Menugaskan atau mengganti kurir untuk sebuah pesanan secara manual.
     * Dipakai terutama untuk pesanan berstatus 'dibatalkan_kurir' yang
     * perlu ditugaskan ulang ke kurir lain oleh owner.
     */
    public function assignKurir(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'kurir_id' => ['required', 'exists:users,id'],
        ]);

        $pesanan->update([
            'kurir_id' => $request->kurir_id,
            'status' => 'dikirim',
            'waktu_diambil' => $pesanan->waktu_diambil ?? now(),
            'estimasi_menit' => $pesanan->estimasi_menit ?? $pesanan->hitungEstimasiMenit(),
        ]);

        return back()->with('success', 'Kurir berhasil ditugaskan untuk pesanan ini.');
    }

    /**
     * Mengubah status pesanan secara manual (override oleh owner).
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => [
                'required',
                'in:menunggu_verifikasi,diproses,ditolak,disiapkan,siap_dikirim,dikirim,selesai,dibatalkan_kurir',
            ],
        ]);

        $pesanan->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Menghapus pesanan secara permanen dari histori.
     */
    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus dari histori.');
    }
}