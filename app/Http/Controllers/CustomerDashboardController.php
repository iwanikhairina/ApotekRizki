<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Mapping label kategori (kolom `kategori` di tabel obat, lihat
     * ObatController::KATEGORI_LIST) -> slug yang dipakai di dashboard.
     */
    const KATEGORI_SLUG_MAP = [
        'Obat'           => 'obat',
        'Nutrisi'        => 'nutrisi',
        'Suplemen'       => 'suplemen',
        'Produk Bayi'    => 'bayi',
        'Herbal'         => 'herbal',
        'Alat Kesehatan' => 'alkes',
        'Mata'           => 'mata',
        'Kecantikan'     => 'kecantikan',
        'Kontrasepsi'    => 'kontrasepsi',
    ];

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
     * Daftar kategori yang tampil di blister strip dashboard.
     * "Kontrasepsi" dikunci untuk pelanggan usia 21+.
     */
    const CATEGORIES = [
        ['slug' => 'obat',        'label' => 'Obat',           'color' => '#12A874', 'icon' => 'pill'],
        ['slug' => 'nutrisi',     'label' => 'Nutrisi',        'color' => '#E8A33D', 'icon' => 'leaf-drop'],
        ['slug' => 'suplemen',    'label' => 'Suplemen',       'color' => '#8C7AE6', 'icon' => 'layers'],
        ['slug' => 'bayi',        'label' => 'Produk Bayi',    'color' => '#4E9BD9', 'icon' => 'baby'],
        ['slug' => 'herbal',      'label' => 'Herbal',         'color' => '#6FA83C', 'icon' => 'leaf'],
        ['slug' => 'alkes',       'label' => 'Alat Kesehatan', 'color' => '#E0715B', 'icon' => 'pulse'],
        ['slug' => 'mata',        'label' => 'Mata',           'color' => '#2FA5A0', 'icon' => 'eye'],
        ['slug' => 'kecantikan',  'label' => 'Kecantikan',     'color' => '#D9679C', 'icon' => 'sparkle'],
        ['slug' => 'kontrasepsi', 'label' => 'Kontrasepsi',    'color' => '#5B4B57', 'icon' => 'lock', 'restricted' => true, 'min_age' => 21],
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

        $products = Obat::query()
            // sembunyikan obat yang sudah kadaluarsa dari pelanggan
            ->where(function ($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhereDate('tanggal_kadaluarsa', '>=', now());
            })
            ->orderBy('nama')
            ->get()
            ->map(function (Obat $obat) {
                return (object) [
                    'id'          => $obat->id,
                    'name'        => $obat->nama,
                    'category'    => self::KATEGORI_SLUG_MAP[$obat->kategori] ?? 'obat',
                    'description' => $obat->deskripsi,
                    'price'       => $obat->harga,
                    'stock'       => $obat->stok,
                    'drug_class'  => self::KLASIFIKASI_MAP[$obat->klasifikasi] ?? null,
                ];
            });

        return view('customer.dashboard', [
            'products'   => $products,
            'categories' => self::CATEGORIES,
            'userAge'    => $userAge,
        ]);
    }
}