<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';

    protected $fillable = ['pesanan_id', 'obat_id', 'jumlah', 'harga_satuan'];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}