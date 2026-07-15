<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

   protected $fillable = [
    'user_id', 'kurir_id', 'alamat', 'jarak_km', 'ongkir', 'metode_pembayaran',
    'catatan', 'status', 'alasan_batal', 'waktu_diambil', 'estimasi_menit',
    'resep_path', 'ktp_path', 'status_resep', 'total_harga',
];

protected $casts = [
    'waktu_diambil' => 'datetime',
];

public function hitungEstimasiMenit(): int
{
    if (! $this->jarak_km) {
        return 30; // default kalau jarak belum diatur
    }

    // Asumsi kecepatan rata-rata kurir 25 km/jam di area pegunungan Aceh Tengah
    $menit = ($this->jarak_km / 25) * 60;

    return (int) max(10, round($menit));
}

public function estimasiSelesaiAt(): ?\Carbon\Carbon
{
    if (! $this->waktu_diambil || ! $this->estimasi_menit) {
        return null;
    }

    return $this->waktu_diambil->copy()->addMinutes($this->estimasi_menit);
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function butuhVerifikasiKhusus(): bool
    {
        return $this->detailPesanan->contains(function ($detail) {
            return $detail->obat && ($detail->obat->butuh_resep || $detail->obat->butuh_ktp);
        });
    }

    public function hitungOngkirOtomatis(): ?float
    {
        if (is_null($this->jarak_km)) {
            return null;
        }

        return match(true) {
            $this->jarak_km <= 5   => 0,
            $this->jarak_km <= 10  => 5000,
            $this->jarak_km <= 15  => 10000,
            default                => null, // di luar jangkauan, tidak dilayani otomatis
        };
    }

    public function ongkirLabel(): string
    {
        if (is_null($this->jarak_km)) {
            return 'Jarak belum diatur';
        }

        return match(true) {
            $this->jarak_km <= 5   => 'Gratis Ongkir (0-5 km)',
            $this->jarak_km <= 10  => 'Rp5.000 - Rp10.000 (5-10 km)',
            $this->jarak_km <= 15  => 'Rp10.000 - Rp15.000 (10-15 km)',
            default                => 'Di luar jangkauan (>15 km) — sesuai ketersediaan kurir',
        };
    }

    public function totalKeseluruhan(): float
    {
        return ($this->total_harga ?? 0) + ($this->ongkir ?? 0);
    }

    public function googleMapsEmbedUrl(): ?string
    {
        if (! $this->alamat) {
            return null;
        }

        $origin = config('apotek.alamat');
        $destination = $this->alamat;

        return 'https://www.google.com/maps?saddr=' . urlencode($origin) . '&daddr=' . urlencode($destination) . '&output=embed';
    }

    public function googleMapsDirectionUrl(): ?string
    {
        if (! $this->alamat) {
            return null;
        }

        $origin = config('apotek.alamat');
        $destination = $this->alamat;

        return 'https://www.google.com/maps/dir/?api=1&origin=' . urlencode($origin) . '&destination=' . urlencode($destination);
    }
}