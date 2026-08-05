<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Master data jadwal pengantaran yang dikelola OWNER lewat Panel Owner.
 * Satu baris = satu rentang waktu pengantaran, misalnya 10:00 - 11:00.
 * Slot yang `aktif` akan muncul sebagai pilihan untuk customer saat checkout.
 */
class JadwalPengantaran extends Model
{
    protected $table = 'jadwal_pengantaran';

    protected $fillable = [
        'jam_mulai',
        'jam_selesai',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderBy('jam_mulai');
    }

    /**
     * Label siap tampil, contoh: "10.00 - 11.00 WIB".
     * Pakai titik (bukan titik dua) sesuai kebiasaan penulisan jam di Indonesia.
     */
    public function getLabelAttribute(): string
    {
        return self::formatJam($this->jam_mulai) . ' - ' . self::formatJam($this->jam_selesai) . ' WIB';
    }

    public static function formatJam(?string $jam): string
    {
        if (! $jam) {
            return '-';
        }

        return str_replace(':', '.', Carbon::parse($jam)->format('H:i'));
    }
}
