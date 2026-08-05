<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimanBatch extends Model
{
    protected $table = 'pengiriman_batch';

    protected $fillable = [
        'kurir_id', 'status', 'jumlah_pesanan', 'total_jarak_km',
        'dimulai_at', 'selesai_at',
    ];

    protected $casts = [
        'dimulai_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class)->orderBy('urutan_pengiriman');
    }

    /**
     * Rumah yang harus diantar berikutnya: pesanan pertama (berdasarkan
     * urutan_pengiriman) yang statusnya masih 'dikirim' (belum selesai/batal).
     */
    public function stopSaatIni(): ?Pesanan
    {
        return $this->pesanan()
            ->where('status', 'dikirim')
            ->first();
    }

    public function semuaSudahSelesai(): bool
    {
        return ! $this->pesanan()->where('status', 'dikirim')->exists();
    }

    public function jumlahSelesai(): int
    {
        return $this->pesanan()->whereIn('status', ['selesai', 'dibatalkan_kurir'])->count();
    }
}
