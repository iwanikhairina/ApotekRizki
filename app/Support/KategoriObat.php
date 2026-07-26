<?php

namespace App\Support;

/**
 * Satu-satunya sumber kebenaran untuk daftar kategori obat.
 *
 * Dipakai oleh:
 * - ObatController (dropdown kategori di form admin, validasi input)
 * - CustomerDashboardController (filter blister strip & mapping kategori->slug
 *   di dashboard customer)
 *
 * Mau tambah/ubah/hapus kategori? CUKUP edit array LIST di bawah ini,
 * tidak perlu sentuh controller manapun. Ini sengaja dibuat supaya kategori
 * baru tidak pernah lagi "hilang" di salah satu sisi (admin ada, customer
 * tidak muncul, atau sebaliknya).
 */
class KategoriObat
{
    /**
     * nama  = nilai yang disimpan persis di kolom `kategori` tabel obat
     * slug  = dipakai untuk filter/URL kategori di dashboard customer
     * color = warna aksen ikon kategori
     * icon  = key ikon SVG yang harus ada di $icons array pada
     *         resources/views/customer/dashboard.blade.php
     * restricted / min_age = opsional, khusus kategori yang dikunci usia
     */
    const LIST = [
        ['nama' => 'Obat',                   'slug' => 'obat',        'color' => '#12A874', 'icon' => 'pill'],
        ['nama' => 'Obat Kronis & Diabetes',  'slug' => 'kronis',      'color' => '#C0392B', 'icon' => 'droplet'],
        ['nama' => 'Obat Batuk, Pilek & Flu', 'slug' => 'batuk-flu',   'color' => '#3E9ADB', 'icon' => 'flu'],
        ['nama' => 'Obat Pencernaan',         'slug' => 'pencernaan',  'color' => '#B07A3E', 'icon' => 'stomach'],
        ['nama' => 'Nutrisi',                 'slug' => 'nutrisi',     'color' => '#E8A33D', 'icon' => 'leaf-drop'],
        ['nama' => 'Suplemen',                'slug' => 'suplemen',    'color' => '#8C7AE6', 'icon' => 'layers'],
        ['nama' => 'Ibu & Bayi',              'slug' => 'bayi',        'color' => '#4E9BD9', 'icon' => 'baby'],
        ['nama' => 'Herbal',                  'slug' => 'herbal',      'color' => '#6FA83C', 'icon' => 'leaf'],
        ['nama' => 'Alat Kesehatan',          'slug' => 'alkes',       'color' => '#E0715B', 'icon' => 'pulse'],
        ['nama' => 'Perawatan Gigi',          'slug' => 'gigi',        'color' => '#4EC0C0', 'icon' => 'tooth'],
        ['nama' => 'Perawatan Rambut',        'slug' => 'rambut',      'color' => '#A9754F', 'icon' => 'hair'],
        ['nama' => 'Mata',                    'slug' => 'mata',        'color' => '#2FA5A0', 'icon' => 'eye'],
        ['nama' => 'Kecantikan',              'slug' => 'kecantikan',  'color' => '#D9679C', 'icon' => 'sparkle'],
        ['nama' => 'P3K & Luka',              'slug' => 'p3k',         'color' => '#E05D5D', 'icon' => 'medkit'],
        ['nama' => 'Masker & APD',            'slug' => 'masker',      'color' => '#5B8DEF', 'icon' => 'mask'],
        [
            'nama'       => 'Kontrasepsi',
            'slug'       => 'kontrasepsi',
            'color'      => '#5B4B57',
            'icon'       => 'lock',
            'restricted' => true,
            'min_age'    => 21,
        ],
    ];

    /**
     * Untuk dropdown kategori di form admin (create/edit obat) & validasi Rule::in().
     */
    public static function namaList(): array
    {
        return array_column(self::LIST, 'nama');
    }

    /**
     * Untuk mapping nama kategori (persis seperti tersimpan di kolom `kategori`)
     * ke slug yang dipakai dashboard customer untuk filter.
     */
    public static function slugMap(): array
    {
        return array_combine(array_column(self::LIST, 'nama'), array_column(self::LIST, 'slug'));
    }

    /**
     * Untuk dashboard customer: daftar kategori siap pakai di blister strip
     * (slug, label, color, icon, dan restricted/min_age kalau ada).
     */
    public static function forDashboard(): array
    {
        return array_map(function (array $item) {
            $result = [
                'slug'  => $item['slug'],
                'label' => $item['nama'],
                'color' => $item['color'],
                'icon'  => $item['icon'],
            ];

            if (isset($item['restricted'])) {
                $result['restricted'] = $item['restricted'];
            }

            if (isset($item['min_age'])) {
                $result['min_age'] = $item['min_age'];
            }

            return $result;
        }, self::LIST);
    }
}