<?php

namespace App\Support;

use App\Models\User;
use Carbon\Carbon;

class JadwalPengantaran
{
    /**
     * Cari slot jadwal pengantaran TERDEKAT dari sekarang, digabung dari
     * jam_antar_mulai/selesai semua kurir aktif yang sudah diatur owner.
     *
     * Return null kalau tidak ada satupun kurir yang jam antarnya sudah
     * diatur owner (fitur jadwal belum aktif dipakai toko ini).
     *
     * Return array:
     *   - 'mulai' (Carbon), 'selesai' (Carbon)
     *   - 'sedang_berlangsung' (bool) — true kalau slot ini SEDANG jalan sekarang
     *   - 'besok' (bool) — true kalau slot terdekat baru ada besok (semua slot hari ini sudah lewat)
     *   - 'label' (string) — teks siap tampil, contoh: "10:00 - 11:00" atau "Besok, 10:00 - 11:00"
     */
    public static function slotBerikutnya(): ?array
    {
        $kurirList = User::where('role', 'kurir')
            ->where('is_active', true)
            ->whereNotNull('jam_antar_mulai')
            ->whereNotNull('jam_antar_selesai')
            ->get();

        if ($kurirList->isEmpty()) {
            return null;
        }

        $now = now();

        $buatWindows = function (string $tanggal) use ($kurirList) {
            return $kurirList->map(function (User $k) use ($tanggal) {
                return [
                    'mulai' => Carbon::parse($tanggal.' '.Carbon::parse($k->jam_antar_mulai)->format('H:i:s')),
                    'selesai' => Carbon::parse($tanggal.' '.Carbon::parse($k->jam_antar_selesai)->format('H:i:s')),
                ];
            });
        };

        $windowsHariIni = $buatWindows($now->format('Y-m-d'));

        // 1. Sedang ada kurir yang jam antarnya berlangsung sekarang?
        $sedangBerlangsung = $windowsHariIni->first(fn ($w) => $now->between($w['mulai'], $w['selesai']));
        if ($sedangBerlangsung) {
            return [
                'mulai' => $sedangBerlangsung['mulai'],
                'selesai' => $sedangBerlangsung['selesai'],
                'sedang_berlangsung' => true,
                'besok' => false,
                'label' => $sedangBerlangsung['mulai']->format('H:i').' - '.$sedangBerlangsung['selesai']->format('H:i'),
            ];
        }

        // 2. Slot terdekat yang belum mulai, hari ini.
        $terdekatHariIni = $windowsHariIni->filter(fn ($w) => $w['mulai']->gt($now))->sortBy('mulai')->first();
        if ($terdekatHariIni) {
            return [
                'mulai' => $terdekatHariIni['mulai'],
                'selesai' => $terdekatHariIni['selesai'],
                'sedang_berlangsung' => false,
                'besok' => false,
                'label' => $terdekatHariIni['mulai']->format('H:i').' - '.$terdekatHariIni['selesai']->format('H:i'),
            ];
        }

        // 3. Semua slot hari ini sudah lewat -> ambil slot paling pagi besok.
        $besokTanggal = $now->copy()->addDay()->format('Y-m-d');
        $terdekatBesok = $buatWindows($besokTanggal)->sortBy('mulai')->first();

        return [
            'mulai' => $terdekatBesok['mulai'],
            'selesai' => $terdekatBesok['selesai'],
            'sedang_berlangsung' => false,
            'besok' => true,
            'label' => 'Besok, '.$terdekatBesok['mulai']->format('H:i').' - '.$terdekatBesok['selesai']->format('H:i'),
        ];
    }
}
