<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Support\KategoriObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    /**
     * Daftar kategori, dengan filter kategori, klasifikasi, dan status kadaluarsa.
     */
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }

        if ($request->status === 'kadaluarsa') {
            $query->whereNotNull('tanggal_kadaluarsa')->whereDate('tanggal_kadaluarsa', '<', now());
        } elseif ($request->status === 'aktif') {
            $query->where(function ($q) {
                $q->whereNull('tanggal_kadaluarsa')->orWhereDate('tanggal_kadaluarsa', '>=', now());
            });
        }

        $obat = $query->orderBy('nama')->get();

        return view('admin.obat.index', [
            'obat'              => $obat,
            'daftarKategori'    => KategoriObat::namaList(),
            'filterKategori'    => $request->kategori,
            'filterKlasifikasi' => $request->klasifikasi,
            'filterStatus'      => $request->status,
        ]);
    }

    public function create()
    {
        return view('admin.obat.create', ['daftarKategori' => KategoriObat::namaList()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('obat', 'public');
        }

        Obat::create($validated);

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', ['obat' => $obat, 'daftarKategori' => KategoriObat::namaList()]);
    }

    public function update(Request $request, Obat $obat)
    {
        $validated = $this->validasi($request);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama supaya tidak numpuk file yang tidak terpakai
            if ($obat->gambar) {
                Storage::disk('public')->delete($obat->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('obat', 'public');
        }

        $obat->update($validated);

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil diperbarui.');
    }

    /**
     * Validasi bersama untuk store & update.
     */
    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'kategori'           => ['required', Rule::in(KategoriObat::namaList())],
            'deskripsi'          => ['nullable', 'string'],
            'klasifikasi'        => ['required', Rule::in(['obat_bebas', 'obat_bebas_terbatas', 'obat_keras'])],
            'butuh_resep'        => ['nullable', 'boolean'],
            'butuh_ktp'          => ['nullable', 'boolean'],
            'harga'              => ['required', 'numeric', 'min:0'],
            'stok'               => ['required', 'integer', 'min:0'],
            'tanggal_kadaluarsa' => ['nullable', 'date'],
            'gambar'             => ['nullable', 'image', 'max:2048'],
        ]);

        // Checkbox yang tidak dicentang tidak dikirim sama sekali oleh browser
        $data['butuh_resep'] = $request->boolean('butuh_resep');
        $data['butuh_ktp'] = $request->boolean('butuh_ktp');

        unset($data['gambar']); // gambar ditangani terpisah (upload file), bukan lewat array ini

        return $data;
    }
}