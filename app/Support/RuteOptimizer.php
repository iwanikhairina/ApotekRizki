<?php

namespace App\Support;

class RuteOptimizer {

    /**
     * Urutkan beberapa "stop" (tujuan antar) berdasarkan strategi
     * nearest-neighbor: mulai dari titik asal (apotek), lalu tiap
     * langkah pilih stop TERDEKAT yang belum dikunjungi dari posisi
     * saat ini — bukan cuma diurutkan berdasar jarak ke apotek saja,
     * karena itu tidak menjamin rute paling efisien saat berpindah
     * rumah ke rumah.
     *
     * $origin: ['lat' => float, 'lng' => float] — lokasi apotek.
     * $stops: array asosiatif, tiap elemen WAJIB punya key 'lat' & 'lng',
     *         boleh ada key lain (mis. 'pesanan_id') yang akan ikut terbawa.
     *
     * Return: array stop yang sama tapi sudah terurut, masing-masing
     *         ditambah key:
     *         - 'urutan' (mulai dari 1)
     *         - 'jarak_leg_km' (jarak dari titik SEBELUMNYA, km)
     *         - 'estimasi_leg_menit' (estimasi waktu dari titik sebelumnya)
     */
    public static function urutkanTerdekat(array $origin, array $stops): array
    {
        $belumDikunjungi = $stops;
        $urutanHasil = [];

        $posisiSekarang = $origin;
        $nomorUrut = 1;

        while (! empty($belumDikunjungi)) {
            $indexTerdekat = null;
            $ruteTerdekat = null;

            foreach ($belumDikunjungi as $index => $stop) {
                $rute = DistanceCalculator::route(
                    $posisiSekarang['lat'],
                    $posisiSekarang['lng'],
                    $stop['lat'],
                    $stop['lng']
                );

                if ($ruteTerdekat === null || $rute['jarak_km'] < $ruteTerdekat['jarak_km']) {
                    $ruteTerdekat = $rute;
                    $indexTerdekat = $index;
                }
            }

            $stopTerpilih = $belumDikunjungi[$indexTerdekat];
            $stopTerpilih['urutan'] = $nomorUrut;
            $stopTerpilih['jarak_leg_km'] = $ruteTerdekat['jarak_km'];
            $stopTerpilih['estimasi_leg_menit'] = $ruteTerdekat['estimasi_menit'];

            $urutanHasil[] = $stopTerpilih;

            $posisiSekarang = ['lat' => $stopTerpilih['lat'], 'lng' => $stopTerpilih['lng']];
            unset($belumDikunjungi[$indexTerdekat]);
            $belumDikunjungi = array_values($belumDikunjungi);
            $nomorUrut++;
        }

        return $urutanHasil;
    }
}
