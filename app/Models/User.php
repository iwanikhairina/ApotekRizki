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
    public function isOnShiftNow(): bool
{
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

public function shiftLabel(): string
{
    return match($this->shift) {
        'pagi' => 'Pagi (08.00 - 17.00)',
        'sore' => 'Sore (17.00 - 22.00)',
        default => 'Belum diatur oleh pemilik apotek',
    };
}
}