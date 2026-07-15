<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * CATATAN: file ini contoh referensi. Kalau kamu sudah punya app/Models/Product.php,
     * cukup TAMBAHKAN kolom-kolom baru di bawah ini ke $fillable yang sudah ada —
     * tidak perlu mengganti seluruh file.
     */
    protected $fillable = [
        'name',
        'sku',
        'category',
        'price',
        'cost_price',
        'stock',
        'image',
        'description',
        'indication',
        'composition',
        'dosage',
        'usage',
        'contraindication',
        'side_effect',
        'manufacturer',
        'drug_class',
    ];

    /**
     * Golongan obat yang wajib pakai resep dokter.
     */
    public function requiresPrescription(): bool
    {
        return $this->drug_class === 'keras';
    }
}