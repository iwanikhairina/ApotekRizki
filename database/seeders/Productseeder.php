<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Jalankan dengan: php artisan db:seed --class=ProductSeeder
     * (atau daftarkan di DatabaseSeeder.php supaya ikut jalan saat `php artisan migrate:fresh --seed`)
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Paracetamol 500mg (10 tablet)',
                'category' => 'obat',
                'price' => 8000,
                'stock' => 42,
                'drug_class' => 'bebas',
                'description' => 'PENGGUNAAN OBAT INI SEBAIKNYA SESUAI PETUNJUK. Paracetamol digunakan untuk meredakan demam serta nyeri ringan hingga sedang seperti sakit kepala, sakit gigi, dan nyeri otot.',
                'indication' => 'Meredakan demam dan nyeri ringan hingga sedang.',
                'composition' => 'Tiap tablet mengandung paracetamol 500 mg.',
                'dosage' => 'Dewasa: 1-2 tablet, 3-4 kali sehari. Maksimal 8 tablet per hari. Anak 6-12 tahun: 1/2-1 tablet, 3-4 kali sehari.',
                'usage' => 'Diminum setelah makan, dengan segelas air putih.',
                'contraindication' => 'Tidak untuk penderita gangguan fungsi hati berat.',
                'side_effect' => 'Jarang: reaksi alergi kulit, gangguan pencernaan ringan.',
                'manufacturer' => 'PT Kimia Farma',
            ],
            [
                'name' => 'Amoxicillin 500mg (10 kapsul)',
                'category' => 'obat',
                'price' => 15500,
                'stock' => 5,
                'drug_class' => 'keras',
                'description' => 'PENGGUNAAN OBAT INI HARUS SESUAI PETUNJUK DOKTER. Amoxicillin adalah antibiotik golongan penisilin untuk mengatasi infeksi bakteri pada saluran napas, saluran kemih, dan kulit.',
                'indication' => 'Mengatasi infeksi bakteri yang sensitif terhadap amoxicillin.',
                'composition' => 'Tiap kapsul mengandung amoxicillin trihydrate setara amoxicillin 500 mg.',
                'dosage' => 'Dewasa: 1 kapsul setiap 8 jam selama 5-7 hari sesuai resep dokter.',
                'usage' => 'Diminum sebelum atau sesudah makan, habiskan sesuai anjuran dokter meskipun gejala sudah membaik.',
                'contraindication' => 'Tidak digunakan pada pasien dengan riwayat alergi golongan penisilin.',
                'side_effect' => 'Mual, diare, ruam kulit.',
                'manufacturer' => 'PT Sanbe Farma',
            ],
            [
                'name' => 'CTM 4mg (10 tablet)',
                'category' => 'obat',
                'price' => 6000,
                'stock' => 25,
                'drug_class' => 'terbatas',
                'description' => 'PENGGUNAAN OBAT INI SEBAIKNYA SESUAI PETUNJUK. CTM (Chlorpheniramine Maleate) digunakan untuk meredakan gejala alergi seperti gatal-gatal, bersin, dan hidung berair.',
                'indication' => 'Meredakan gejala alergi (rhinitis alergi, urtikaria).',
                'composition' => 'Tiap tablet mengandung chlorpheniramine maleate 4 mg.',
                'dosage' => 'Dewasa: 1 tablet, 3 kali sehari.',
                'usage' => 'Diminum sesudah makan. Dapat menyebabkan kantuk, hindari mengemudi setelah minum obat ini.',
                'contraindication' => 'Tidak untuk penderita glaukoma sudut sempit.',
                'side_effect' => 'Mengantuk, mulut kering.',
                'manufacturer' => 'PT Indofarma',
            ],
            ['name' => 'Vitamin C 1000mg Effervescent', 'category' => 'nutrisi', 'price' => 32000, 'stock' => 28],
            ['name' => 'Susu Formula Bayi 0-6 Bulan', 'category' => 'bayi', 'price' => 118000, 'stock' => 14],
            ['name' => 'Minyak Kayu Putih Herbal 60ml', 'category' => 'herbal', 'price' => 21000, 'stock' => 33],
            ['name' => 'Termometer Digital Infrared', 'category' => 'alkes', 'price' => 145000, 'stock' => 9],
            ['name' => 'Tetes Mata Iritasi & Kering', 'category' => 'mata', 'price' => 27500, 'stock' => 0],
            ['name' => 'Kolagen + Suplemen Sendi', 'category' => 'suplemen', 'price' => 89000, 'stock' => 17],
            ['name' => 'Popok Bayi Ukuran M (34pcs)', 'category' => 'bayi', 'price' => 76000, 'stock' => 22],
            ['name' => 'Masker Medis 3-Ply (50pcs)', 'category' => 'alkes', 'price' => 35000, 'stock' => 3],
            ['name' => 'Jahe Merah Instan Herbal', 'category' => 'herbal', 'price' => 18000, 'stock' => 40],
            ['name' => 'Multivitamin Anak Rasa Jeruk', 'category' => 'nutrisi', 'price' => 47500, 'stock' => 19],
            ['name' => 'Serum Wajah Niacinamide 10ml', 'category' => 'kecantikan', 'price' => 65000, 'stock' => 26],
            ['name' => 'Sunscreen SPF 50 PA+++', 'category' => 'kecantikan', 'price' => 58000, 'stock' => 31],
            ['name' => 'Test Pack Kehamilan', 'category' => 'dewasa', 'price' => 15000, 'stock' => 20],
            ['name' => 'Kondom Sensitif (12pcs)', 'category' => 'dewasa', 'price' => 42000, 'stock' => 15],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}