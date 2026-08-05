<?php

namespace App\Models;

use App\Support\DistanceCalculator;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id', 'kurir_id', 'alamat', 'jarak_km', 'ongkir', 'metode_pembayaran',
        'catatan', 'catatan_apoteker', 'status', 'alasan_batal', 'waktu_diambil', 'estimasi_menit',
        'resep_path', 'ktp_path', 'status_resep', 'total_harga',
        'pengiriman_batch_id', 'urutan_pengiriman', 'jarak_leg_km', 'estimasi_leg_menit',
        'jadwal_pengantaran_id', 'jadwal_antar_mulai', 'jadwal_antar_selesai', 'jadwal_popup_shown',
    ];

    protected $casts = [
        'waktu_diambil'       => 'datetime',
        'jadwal_popup_shown'  => 'boolean',
    ];

    /**
     * Estimasi waktu tempuh (menit), berdasarkan tabel estimasi tetap
     * di DistanceCalculator (rasio 2 menit per km): 0,5 km -> 1 menit,
     * 5 km -> 10 menit, 12 km -> 24 menit, 18 km -> 36 menit, dst.
     * Selalu dihitung ulang dari jarak_km (tidak lagi terpaku ke nilai lama
     * yang mungkin sudah tersimpan di kolom estimasi_menit).
     */
    public function hitungEstimasiMenit(): int
    {
        return DistanceCalculator::estimasiMenitUntukJarak($this->jarak_km);
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

    public function batch()
    {
        return $this->belongsTo(PengirimanBatch::class, 'pengiriman_batch_id');
    }

    public function jadwalPengantaran()
    {
        return $this->belongsTo(JadwalPengantaran::class, 'jadwal_pengantaran_id');
    }

    /**
     * True kalau pesanan ini punya jadwal pengantaran yang tersimpan
     * (dipilih customer saat checkout).
     */
    public function punyaJadwalPengantaran(): bool
    {
        return ! empty($this->jadwal_antar_mulai) && ! empty($this->jadwal_antar_selesai);
    }

    /**
     * Label siap tampil dari jadwal yang TERSIMPAN di pesanan ini
     * (snapshot saat checkout), bukan dihitung ulang dari master data —
     * supaya histori pesanan tetap akurat walau owner mengubah/menghapus
     * slot master-nya nanti. Contoh: "10.00 - 11.00 WIB".
     */
    public function jadwalPengantaranLabel(): ?string
    {
        if (! $this->punyaJadwalPengantaran()) {
            return null;
        }

        return JadwalPengantaran::formatJam($this->jadwal_antar_mulai)
            . ' - ' . JadwalPengantaran::formatJam($this->jadwal_antar_selesai)
            . ' WIB';
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

    /**
     * Ongkir otomatis berdasarkan jarak (km) tempuh jalan, memakai formula
     * tetap di DistanceCalculator (gratis <= 0,5 km, lalu +Rp3.000 setiap
     * kelipatan 0,5 km). Tidak ada batas jarak di sini — area layanan
     * divalidasi terpisah berdasarkan kecamatan (lihat DistanceCalculator::areaDilayani()).
     */
    public function hitungOngkirOtomatis(): ?float
    {
        if (is_null($this->jarak_km)) {
            return null;
        }

        return DistanceCalculator::ongkirUntukJarak($this->jarak_km);
    }

    public function ongkirLabel(): string
    {
        if (is_null($this->jarak_km)) {
            return 'Jarak belum diatur';
        }

        $ongkir = $this->ongkir ?? DistanceCalculator::ongkirUntukJarak($this->jarak_km);

        if ($ongkir <= 0) {
            return 'Gratis Ongkir (jarak ' . number_format($this->jarak_km, 1) . ' km)';
        }

        return 'Rp' . number_format($ongkir, 0, ',', '.') . ' (jarak ' . number_format($this->jarak_km, 1) . ' km)';
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