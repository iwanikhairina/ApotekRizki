<?php

return [
    'nama' => 'Apotek Rizki',

    'alamat' => 'Jalan Terminal Paya Ilang, Desa Blang Kolak II, Kec. Bebesen, Kab. Aceh Tengah',

    // GANTI dengan koordinat presisi: buka Google Maps, klik kanan tepat di lokasi apotek,
    // klik angka koordinat yang muncul di menu, lalu copy ke sini.
    'latitude'  => 4.6317,
    'longitude' => 96.8433,

    // Radius layanan maksimum (km) sesuai cakupan Kec. Bebesen, Kebayakan, Pegasing,
    // dan sekitar Danau Laut Tawar.
    'radius_maksimum_km' => 18,

    // Minimal total belanja (subtotal produk, TIDAK termasuk ongkir) supaya
    // customer bisa lanjut ke checkout.
    'minimum_belanja' => 10000,
// Untuk testing lokal saja — set true untuk bypass pengecekan jam shift apoteker.
// WAJIB false lagi sebelum deploy / sidang, supaya validasi shift tetap berfungsi.
'abaikan_cek_shift' => env('APOTEK_ABAIKAN_SHIFT', false),
    // Tarif ongkir bertingkat berdasarkan jarak (km)
    'ongkir_tiers' => [
        ['max_km' => 5,  'harga' => 0],
        ['max_km' => 10, 'harga' => 5000],
        ['max_km' => 15, 'harga' => 10000],
        ['max_km' => 18, 'harga' => 15000],
    ],
];