<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengantaran;
use Illuminate\Http\Request;

class JadwalPengantaranController extends Controller
{
    /**
     * Daftar semua jadwal pengantaran (Panel Owner).
     */
    public function index()
    {
        $jadwalList = JadwalPengantaran::urut()->get();

        return view('admin.jadwal-pengantaran.index', [
            'jadwalList' => $jadwalList,
        ]);
    }

    /**
     * Owner menambah slot jadwal pengantaran baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        JadwalPengantaran::create([
            'jam_mulai'   => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'aktif'       => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.jadwal-pengantaran.index')
            ->with('success', 'Jadwal pengantaran berhasil ditambahkan.');
    }

    /**
     * Owner mengubah rentang waktu / status aktif suatu slot.
     */
    public function update(Request $request, JadwalPengantaran $jadwalPengantaran)
    {
        $validated = $this->validasi($request);

        $jadwalPengantaran->update([
            'jam_mulai'   => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'aktif'       => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.jadwal-pengantaran.index')
            ->with('success', 'Jadwal pengantaran berhasil diperbarui.');
    }

    /**
     * Aktifkan/nonaktifkan slot dengan cepat tanpa membuka form edit.
     * Slot nonaktif tidak akan ditampilkan sebagai pilihan ke customer.
     */
    public function toggle(JadwalPengantaran $jadwalPengantaran)
    {
        $jadwalPengantaran->update(['aktif' => ! $jadwalPengantaran->aktif]);

        $pesan = $jadwalPengantaran->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.jadwal-pengantaran.index')
            ->with('success', "Jadwal {$jadwalPengantaran->label} berhasil {$pesan}.");
    }

    /**
     * Owner menghapus slot jadwal. Pesanan yang sudah memakai slot ini
     * tidak terpengaruh karena jam sudah tersimpan sebagai snapshot
     * langsung di tabel pesanan (jadwal_antar_mulai/selesai).
     */
    public function destroy(JadwalPengantaran $jadwalPengantaran)
    {
        $jadwalPengantaran->delete();

        return redirect()->route('admin.jadwal-pengantaran.index')
            ->with('success', 'Jadwal pengantaran berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);
    }
}
