<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Support\DistanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     *
     * Halaman ini dipakai untuk resep yang diunggah TANPA memilih obat dulu
     * (misal customer belum tahu nama obatnya, cuma punya foto resep dari
     * dokter). Karena itu, upload di sini langsung membuat Pesanan baru
     * berisi resepnya saja — daftar obatnya nanti diisi oleh apoteker lewat
     * menu Verifikasi Resep setelah membaca foto resep tersebut.
     *
     * PERBAIKAN: sebelumnya jarak_km & ongkir tidak pernah dihitung di sini
     * (beda dengan alur checkout keranjang yang sudah pakai DistanceCalculator
     * di CartController/CheckoutController), sehingga customer selalu melihat
     * "Jarak belum diatur" di halaman pembayaran. Sekarang dihitung langsung
     * di sini kalau alamat customer sudah lengkap (alamat + latitude + longitude).
     * Kalau belum lengkap, dibiarkan null — nanti akan dihitung ulang otomatis
     * saat apoteker menyetujui resep (lihat ApotekerVerifikasiController::setujui()).
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

        $user = Auth::user();

        // Pesanan belum punya kolom terpisah untuk nama pasien/nama dokter,
        // jadi digabung ke kolom `catatan` supaya informasinya tetap tersimpan
        // dan terlihat oleh apoteker di halaman Verifikasi Resep.
        $catatanParts = ['Nama pasien: ' . $validated['nama_pasien']];
        if (! empty($validated['nama_dokter'])) {
            $catatanParts[] = 'Dokter: ' . $validated['nama_dokter'];
        }
        if (! empty($validated['catatan'])) {
            $catatanParts[] = $validated['catatan'];
        }

        $jarakKm = null;
        $ongkir  = null;

        if ($user->alamat && $user->latitude && $user->longitude) {
            $jarakKm = DistanceCalculator::km(
                config('apotek.latitude'),
                config('apotek.longitude'),
                $user->latitude,
                $user->longitude
            );

            $ongkir = DistanceCalculator::ongkirUntukJarak($jarakKm);
        }

        Pesanan::create([
            'user_id'      => $user->id,
            'alamat'       => $user->alamat ?? null,
            'jarak_km'     => $jarakKm,
            'ongkir'       => $ongkir,
            'resep_path'   => $path,
            'status'       => 'menunggu_verifikasi',
            'status_resep' => 'menunggu',
            'catatan'      => implode(' | ', $catatanParts),
            'total_harga'  => 0,
        ]);

        return redirect()
            ->route('resep.upload')
            ->with('success', 'Resep berhasil diunggah. Apoteker kami akan segera memverifikasi resep dan menyiapkan obatmu.');
    }
}