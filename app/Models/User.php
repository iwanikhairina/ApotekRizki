<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'phone',
    'birth_date',
    'gender',
    'alamat',
    'detail_alamat',
    'provinsi',
    'kota',
    'kecamatan',
    'kelurahan',
    'kode_pos',
    'nama_penerima',
    'no_telepon',
    'label_alamat',
    'latitude',
    'longitude',
    'tanggal_lahir',
    'role',
    'shift',
    'jam_antar_mulai',
    'jam_antar_selesai',
    'is_active',
    'jenis_kendaraan',
    'plat_nomor',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah apoteker/kurir sedang dalam jam shift-nya sekarang.
     *
     * Pengecekan ini bisa di-bypass lewat config('app.shift_check_enabled')
     * yang diatur dari .env (SHIFT_CHECK_ENABLED=false) — HANYA untuk
     * keperluan testing lokal. Pastikan SHIFT_CHECK_ENABLED=true (atau
     * baris itu dihapus dari .env, karena default-nya true) di lingkungan
     * normal, lalu jalankan `php artisan config:clear` setelah mengubahnya.
     */
    public function isOnShiftNow(): bool
    {
        if (! config('app.shift_check_enabled', true)) {
            return true; // bypass untuk testing
        }

        if (! $this->shift) {
            return false;
        }

        $ranges = [
            'pagi' => ['08:00', '17:00'],
            'sore' => ['17:00', '22:00'],
        ];

        if (! isset($ranges[$this->shift])) {
            return false;
        }

        [$start, $end] = $ranges[$this->shift];
        $now = now();
        $startTime = \Carbon\Carbon::createFromTimeString($start);
        $endTime = \Carbon\Carbon::createFromTimeString($end);

        return $now->between($startTime, $endTime);
    }

    /**
     * Label shift yang enak dibaca, dipakai di dashboard & pesan error
     * saat apoteker/kurir mencoba mengubah pesanan di luar jam shift.
     */
    public function shiftLabel(): string
    {
        return match ($this->shift) {
            'pagi' => 'Pagi (08.00 - 17.00)',
            'sore' => 'Sore (17.00 - 22.00)',
            default => 'Belum diatur oleh pemilik apotek',
        };
    }

    /**
     * Cek apakah SEKARANG masuk jam antar spesifik kurir ini
     * (contoh: kurir cuma boleh dapat batch pengiriman jam 10.00-12.00).
     * Ini lapisan tambahan yang lebih detail di atas isOnShiftNow() —
     * kurir tetap harus di dalam shift-nya juga.
     *
     * Kalau jam_antar_mulai/selesai belum diisi owner untuk kurir ini,
     * dianggap TIDAK ada jadwal spesifik (return false) supaya owner
     * wajib mengatur jam antar dulu sebelum kurir bisa dapat batch.
     */
    public function adaJadwalAntarSekarang(): bool
    {
        if (! config('app.shift_check_enabled', true)) {
            return true; // bypass untuk testing lokal, sama seperti isOnShiftNow()
        }

        if (! $this->jam_antar_mulai || ! $this->jam_antar_selesai) {
            return false;
        }

        $now = now();
        $tanggalHariIni = $now->format('Y-m-d');
        $mulai = \Carbon\Carbon::parse($tanggalHariIni . ' ' . \Carbon\Carbon::parse($this->jam_antar_mulai)->format('H:i:s'));
        $selesai = \Carbon\Carbon::parse($tanggalHariIni . ' ' . \Carbon\Carbon::parse($this->jam_antar_selesai)->format('H:i:s'));

        return $now->between($mulai, $selesai);
    }

    /**
     * Label jam antar yang enak dibaca untuk ditampilkan di profil kurir
     * dan halaman staff (owner).
     */
    public function jadwalAntarLabel(): string
    {
        if (! $this->jam_antar_mulai || ! $this->jam_antar_selesai) {
            return 'Belum diatur oleh pemilik apotek';
        }

        $format = fn ($t) => \Carbon\Carbon::parse($t)->format('H:i');

        return $format($this->jam_antar_mulai) . ' - ' . $format($this->jam_antar_selesai);
    }
}