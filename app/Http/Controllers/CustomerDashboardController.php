<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Support\KategoriObat;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Mapping kolom `klasifikasi` (golongan obat resmi) -> key yang
     * dipakai view untuk menampilkan logo & status wajib resep.
     */
    const KLASIFIKASI_MAP = [
        'obat_bebas'          => 'bebas',
        'obat_bebas_terbatas' => 'terbatas',
        'obat_keras'          => 'keras',
    ];

    /**
     * Menampilkan dashboard pelanggan.
     *
     * $userAge dihitung dari kolom birth_date milik user yang login,
     * lalu dipakai di view untuk mengunci kategori/produk usia 21+
     * (kategori "Kontrasepsi").
     *
     * Kalau birth_date belum diisi pelanggan (field-nya opsional saat
     * registrasi), $userAge akan null -> di view otomatis dianggap
     * BELUM terverifikasi, jadi produk dewasa tetap terkunci.
     */
    public function index()
    {
        $user = Auth::user();
        $userAge = null;

        if ($user && $user->birth_date) {
            $userAge = \Carbon\Carbon::parse($user->birth_date)->age;
        }

        $kategoriSlugMap = KategoriObat::slugMap();

        $products = Obat::query()
            // sembunyikan obat yang sudah kadaluarsa dari pelanggan
            ->where(function ($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhereDate('tanggal_kadaluarsa', '>=', now());
            })
            ->orderBy('nama')
            ->get()
            ->map(function (Obat $obat) use ($kategoriSlugMap) {
    return (object) [
        'id'          => $obat->id,
        'name'        => $obat->nama,
        'category'    => $kategoriSlugMap[$obat->kategori] ?? 'obat',
        'description' => $obat->deskripsi,
        'price'       => $obat->harga,
        'stock'       => $obat->stok,
        'image'       => $obat->gambar,
        'drug_class'  => self::KLASIFIKASI_MAP[$obat->klasifikasi] ?? null,
    ];
});

        return view('customer.dashboard', [
            'products'   => $products,
            'categories' => KategoriObat::forDashboard(),
            'userAge'    => $userAge,
        ]);
    }
}