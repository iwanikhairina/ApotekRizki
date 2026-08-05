<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek tiap menit: kurir mana yang jam antarnya sedang berlangsung,
// lalu bagikan semua pesanan siap_dikirim ke kurir itu jadi satu batch,
// diurutkan dari rumah yang paling dekat dulu.
//
// PENTING: scheduler Laravel butuh satu baris cron di server yang jalan
// tiap menit, kalau belum ada tambahkan di server (Railway/Render):
//   * * * * * cd /path-ke-project && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('kurir:tugaskan-batch')->everyMinute();
