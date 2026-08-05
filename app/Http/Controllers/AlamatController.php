<?php

namespace App\Http\Controllers;

use App\Support\DistanceCalculator;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        $existingAddress = null;
        if ($user->alamat && $user->latitude && $user->longitude) {
            $existingAddress = [
                'latitude'       => $user->latitude,
                'longitude'      => $user->longitude,
                'alamat_lengkap' => $user->alamat,
                'detail_alamat'  => $user->detail_alamat,
                'provinsi'       => $user->provinsi,
                'kota'           => $user->kota,
                'kecamatan'      => $user->kecamatan,
                'kelurahan'      => $user->kelurahan,
                'kode_pos'       => $user->kode_pos,
                'nama_penerima'  => $user->nama_penerima,
                'no_telepon'     => $user->no_telepon,
                'label_alamat'   => $user->label_alamat,
            ];
        }

        return view('customer.tambah-alamat', compact('existingAddress'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'alamat_lengkap'  => 'required|string',
            'detail_alamat'   => 'nullable|string|max:255',
            'provinsi'        => 'required|string|max:100',
            'kota'            => 'required|string|max:100',
            'kecamatan'       => 'required|string|max:100',
            'kelurahan'       => 'nullable|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'nama_penerima'   => 'required|string|max:100',
            'no_telepon'      => 'required|string|max:20',
            'label_alamat'    => 'nullable|string|max:50',
        ]);

        // Validasi area layanan berdasarkan wilayah administrasi (kecamatan),
        // bukan lagi berdasarkan radius jarak. Hanya alamat yang berada di
        // salah satu kecamatan yang dilayani yang bisa diproses.
        if (! DistanceCalculator::areaDilayani($validated['kecamatan'])) {
            $kecamatanDilayani = implode(', ', DistanceCalculator::kecamatanDilayani());

            return back()->withInput()->withErrors([
                'lokasi' => 'Maaf, alamat yang Anda pilih berada di luar area layanan Apotek Rizki. '
                    . 'Saat ini pengantaran hanya melayani Kecamatan ' . $kecamatanDilayani . '.',
            ]);
        }

        $request->user()->update([
            'alamat'         => $validated['alamat_lengkap'],
            'detail_alamat'  => $validated['detail_alamat'] ?? null,
            'provinsi'       => $validated['provinsi'],
            'kota'           => $validated['kota'],
            'kecamatan'      => $validated['kecamatan'],
            'kelurahan'      => $validated['kelurahan'] ?? null,
            'kode_pos'       => $validated['kode_pos'] ?? null,
            'nama_penerima'  => $validated['nama_penerima'],
            'no_telepon'     => $validated['no_telepon'],
            'label_alamat'   => $validated['label_alamat'] ?? null,
            'latitude'       => $validated['latitude'],
            'longitude'      => $validated['longitude'],
        ]);

        return redirect()->route('cart.index')->with('success', 'Alamat berhasil disimpan.');
    }
}