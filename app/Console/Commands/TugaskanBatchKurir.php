<?php

namespace App\Console\Commands;

use App\Models\PengirimanBatch;
use App\Models\Pesanan;
use App\Models\User;
use App\Support\BatchBuilder;
use Illuminate\Console\Command;

class TugaskanBatchKurir extends Command
{
    /**
     * php artisan kurir:tugaskan-batch
     * Dijadwalkan jalan tiap menit lewat routes/console.php (Schedule::command).
     * HANYA berlaku untuk kurir yang jam_antar_mulai/selesai-nya SUDAH diatur
     * owner. Kurir yang belum diatur jadwalnya harus ambil manual +
     * tekan tombol "Mulai" sendiri di halaman Pengiriman.
     */
    protected $signature = 'kurir:tugaskan-batch';

    protected $description = 'Kumpulkan pesanan siap_dikirim dan bagikan otomatis ke kurir yang jam antarnya (diatur owner) sedang berlangsung, sekaligus urutkan rute dari yang terdekat.';

    public function handle(): int
    {
        $kurirTersedia = User::where('role', 'kurir')
            ->where('is_active', true)
            ->whereNotNull('jam_antar_mulai')
            ->whereNotNull('jam_antar_selesai')
            ->get()
            ->filter(function (User $kurir) {
                if (! $kurir->isOnShiftNow() || ! $kurir->adaJadwalAntarSekarang()) {
                    return false;
                }

                return ! PengirimanBatch::where('kurir_id', $kurir->id)
                    ->where('status', 'berjalan')
                    ->exists();
            });

        if ($kurirTersedia->isEmpty()) {
            $this->info('Tidak ada kurir berjadwal tetap yang sedang masuk jam antar & siap menerima batch baru.');

            return self::SUCCESS;
        }

        foreach ($kurirTersedia as $kurir) {
            $this->tugaskanUntukKurir($kurir);
        }

        return self::SUCCESS;
    }

    private function tugaskanUntukKurir(User $kurir): void
    {
        // Ambil pesanan siap_dikirim yang BELUM diklaim kurir manapun
        // (kalau sudah diklaim manual oleh kurir lain, biarkan; kurir itu
        // yang akan menekan tombol "Mulai" sendiri).
        $pesananSiap = Pesanan::with('user')
            ->where('status', 'siap_dikirim')
            ->whereNull('kurir_id')
            ->whereNull('pengiriman_batch_id')
            ->whereHas('user', fn ($q) => $q->whereNotNull('latitude')->whereNotNull('longitude'))
            ->get();

        if ($pesananSiap->isEmpty()) {
            return;
        }

        $batch = BatchBuilder::mulaiBatch($kurir, $pesananSiap);

        $this->info("Batch #{$batch->id}: {$batch->jumlah_pesanan} pesanan ditugaskan otomatis ke kurir {$kurir->name}.");
    }
}
