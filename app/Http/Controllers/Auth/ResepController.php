<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResepController extends Controller
{
    /**
     * Tampilkan halaman upload resep.
     */
    public function create()
    {
        return view('customer.upload-resep');
    }

    /**
     * Simpan resep yang diunggah customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resep'        => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'], // 10MB
            'nama_pasien'  => ['required', 'string', 'max:150'],
            'nama_dokter'  => ['nullable', 'string', 'max:150'],
            'catatan'      => ['nullable', 'string', 'max:1000'],
        ], [
            'resep.required' => 'Silakan pilih file resep terlebih dahulu.',
            'resep.mimes'     => 'Format file harus JPG atau PNG.',
            'resep.max'       => 'Ukuran file maksimal 10MB.',
            'nama_pasien.required' => 'Nama pasien wajib diisi.',
        ]);

        // Simpan file ke storage/app/public/resep
        // Pastikan sudah menjalankan: php artisan storage:link
        $path = $request->file('resep')->store('resep', 'public');

        // Contoh penyimpanan ke database (sesuaikan dengan model Anda)
        // \App\Models\Resep::create([
        //     'user_id'      => Auth::id(),
        //     'nama_pasien'  => $validated['nama_pasien'],
        //     'nama_dokter'  => $validated['nama_dokter'] ?? null,
        //     'catatan'      => $validated['catatan'] ?? null,
        //     'file_path'    => $path,
        //     'status'       => 'menunggu_verifikasi',
        // ]);

        return redirect()
            ->route('resep.upload')
            ->with('success', 'Resep berhasil diunggah. Apoteker kami akan segera memverifikasi resep Anda.');
    }
}