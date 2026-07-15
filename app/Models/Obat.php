<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
       'nama', 'kategori', 'deskripsi', 'klasifikasi', 'butuh_resep', 'butuh_ktp',
       'harga', 'stok', 'tanggal_kadaluarsa', 'gambar',
   ];
protected $casts = [
    'tanggal_kadaluarsa' => 'date',
    'butuh_resep'        => 'boolean',
    'butuh_ktp'          => 'boolean',
];
    /**
     * Obat golongan "obat_keras" wajib resep secara aturan resmi, terlepas dari
     * apakah checkbox butuh_resep di form admin sudah dicentang atau belum.
     * Dipakai sebagai satu-satunya sumber kebenaran di seluruh alur checkout
     * & verifikasi, supaya tidak ada obat keras yang lolos tanpa resep.
     */
    public function perluResep(): bool
    {
        return (bool) $this->butuh_resep || $this->klasifikasi === 'obat_keras';
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /**
     * FEFO (First-Expired-First-Out): urutkan supaya obat yang paling dekat
     * kadaluarsa tampil paling atas. Obat tanpa tanggal_kadaluarsa ditaruh
     * di paling akhir karena tidak ada urgensi untuk dijual duluan.
     * Dipakai di katalog customer supaya stok lama laku duluan sebelum
     * kadaluarsa, alih-alih menumpuk di gudang.
     */
    public function scopeUrutFefo($query)
    {
        return $query->orderByRaw('tanggal_kadaluarsa IS NULL, tanggal_kadaluarsa ASC');
    }

    /**
     * True jika obat ini kadaluarsa dalam 30 hari ke depan (dan belum lewat).
     * Dipakai untuk menampilkan badge peringatan di katalog & dashboard.
     */
    public function getSegeraKadaluarsaAttribute(): bool
    {
        if (! $this->tanggal_kadaluarsa) {
            return false;
        }

        $sisaHari = now()->diffInDays($this->tanggal_kadaluarsa, false);

        return $sisaHari >= 0 && $sisaHari <= 30;
    }

    /**
     * True jika tanggal kadaluarsa sudah lewat.
     */
    public function getSudahKadaluarsaAttribute(): bool
    {
        return $this->tanggal_kadaluarsa && $this->tanggal_kadaluarsa->isPast();
    }
}