<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id', 'kurir_id', 'alamat', 'jarak_km', 'ongkir', 'metode_pembayaran',
        'catatan', 'catatan_apoteker', 'status', 'alasan_batal', 'waktu_diambil', 'estimasi_menit',
        'resep_path', 'ktp_path', 'status_resep', 'total_harga',
    ];

    protected $casts = [
        'waktu_diambil' => 'datetime',
    ];

    /**
     * Estimasi waktu tempuh (menit), dihitung langsung dari jarak (km) —
     * rasio 2 menit per km (≈ kecepatan rata-rata kurir 30 km/jam):
     * 0,5 km → 1 menit, 5 km → 10 menit, 12 km → 24 menit, 18 km → 36 menit, dst.
     * Selalu dihitung ulang dari jarak_km (tidak lagi terpaku ke nilai lama
     * yang mungkin sudah tersimpan di kolom estimasi_menit).
     */
    public function hitungEstimasiMenit(): int
    {
        if (! $this->jarak_km) {
            return 0;
        }

        return (int) max(1, round($this->jarak_km * 2));
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

    /**
     * Sumber kebenaran tunggal: apakah pesanan ini mengandung obat yang
     * perlu resep dokter (butuh_resep = true ATAU klasifikasi obat_keras).
     * Dipakai di halaman detail pesanan untuk menampilkan form upload resep,
     * dan nanti di panel apoteker untuk daftar verifikasi.
     */
    public function requiresResep(): bool
    {
        return $this->detailPesanan->contains(
            fn ($detail) => $detail->obat && $detail->obat->perluResep()
        );
    }

    /**
     * True kalau resep sudah pernah diunggah untuk pesanan ini.
     */
    public function sudahUploadResep(): bool
    {
        return ! empty($this->resep_path);
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