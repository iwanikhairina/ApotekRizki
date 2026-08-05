<?php

namespace App\Support;

use App\Models\Notifikasi;
use App\Models\PengirimanBatch;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BatchBuilder
{
    /**
     * Bikin satu PengirimanBatch dari kumpulan pesanan yang sudah pasti
     * milik kurir ini (baik karena diambil manual satu-satu, maupun
     * dikumpulkan otomatis oleh command kurir:tugaskan-batch).
     * Urutan rumah dihitung dari yang terdekat dulu (RuteOptimizer).
     */
    public static function mulaiBatch(User $kurir, Collection $pesananList): PengirimanBatch
    {
        $origin = [
            'lat' => (float) config('apotek.latitude'),
            'lng' => (float) config('apotek.longitude'),
        ];

        $stops = $pesananList->map(fn ($p) => [
            'pesanan_id' => $p->id,
            'lat' => (float) $p->user->latitude,
            'lng' => (float) $p->user->longitude,
        ])->all();

        $stopsTerurut = RuteOptimizer::urutkanTerdekat($origin, $stops);

        return DB::transaction(function () use ($kurir, $pesananList, $stopsTerurut) {
            $totalJarak = array_sum(array_column($stopsTerurut, 'jarak_leg_km'));

            $batch = PengirimanBatch::create([
                'kurir_id' => $kurir->id,
                'status' => 'berjalan',
                'jumlah_pesanan' => count($stopsTerurut),
                'total_jarak_km' => round($totalJarak, 2),
                'dimulai_at' => now(),
            ]);

            foreach ($stopsTerurut as $stop) {
                $pesanan = $pesananList->firstWhere('id', $stop['pesanan_id']);

                $pesanan->update([
                    'status' => 'dikirim',
                    'kurir_id' => $kurir->id,
                    'pengiriman_batch_id' => $batch->id,
                    'urutan_pengiriman' => $stop['urutan'],
                    'jarak_leg_km' => $stop['jarak_leg_km'],
                    'estimasi_leg_menit' => $stop['estimasi_leg_menit'],
                    'waktu_diambil' => now(),
                    'estimasi_menit' => $stop['estimasi_leg_menit'],
                ]);

                Notifikasi::create([
                    'user_id' => $pesanan->user_id,
                    'pesanan_id' => $pesanan->id,
                    'judul' => 'Pesanan Sedang Diantar',
                    'pesan' => 'Pesanan P'.str_pad($pesanan->id, 3, '0', STR_PAD_LEFT).' sedang diantar oleh kurir '.$kurir->name.'. Pesananmu urutan ke-'.$stop['urutan'].' dari '.count($stopsTerurut).' rumah yang diantar kurir ini.',
                ]);
            }

            return $batch;
        });
    }
}
