<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceCalculator
{
    /**
     * Hitung jarak garis lurus (Haversine) antara dua titik koordinat, dalam km.
     */
    public static function km(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $bumiRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($bumiRadiusKm * $c, 2);
    }

    /**
     * Hitung jarak & estimasi waktu tempuh JALAN SEBENARNYA (bukan garis lurus)
     * antara dua titik koordinat, memakai data routing OpenStreetMap (OSRM) —
     * sumber data yang sama dipakai untuk peta pemilihan alamat customer,
     * supaya jarak & estimasi kurir konsisten dengan yang dilihat customer.
     *
     * Return: ['jarak_km' => float, 'estimasi_menit' => int, 'sumber' => string]
     */
    public static function route(float $lat1, float $lng1, float $lat2, float $lng2): array
    {
        $cacheKey = 'osrm_route_' . md5(sprintf('%.6f,%.6f-%.6f,%.6f', $lat1, $lng1, $lat2, $lng2));

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($lat1, $lng1, $lat2, $lng2) {
            try {
                $response = Http::timeout(5)->get(sprintf(
                    'https://router.project-osrm.org/route/v1/driving/%s,%s;%s,%s',
                    $lng1,
                    $lat1,
                    $lng2,
                    $lat2
                ), [
                    'overview' => 'false',
                ]);

                if ($response->successful()) {
                    $route = $response->json('routes.0');

                    if ($route && isset($route['distance'], $route['duration'])) {
                        return [
                            'jarak_km'       => round($route['distance'] / 1000, 2),
                            'estimasi_menit' => (int) max(1, round($route['duration'] / 60)),
                            'sumber'         => 'rute',
                        ];
                    }
                }

                Log::warning('OSRM merespons tapi tidak valid, pakai fallback Haversine.', [
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('OSRM tidak bisa diakses, pakai fallback Haversine: ' . $e->getMessage());
            }

            // Fallback: Haversine + asumsi kecepatan 25 km/jam (sama seperti logika lama)
            $jarakKm = self::km($lat1, $lng1, $lat2, $lng2);
            $menit = max(10, round(($jarakKm / 25) * 60));

            return [
                'jarak_km'       => $jarakKm,
                'estimasi_menit' => (int) $menit,
                'sumber'         => 'haversine_fallback',
            ];
        });
    }

    /**
     * Ongkir bertingkat berdasarkan jarak (km), sesuai config/apotek.php.
     * Return null kalau di luar jangkauan (tidak bisa dilayani).
     */
    public static function ongkirUntukJarak(float $jarakKm): ?float
    {
        $radiusMax = config('apotek.radius_maksimum_km');

        if ($jarakKm > $radiusMax) {
            return null;
        }

        foreach (config('apotek.ongkir_tiers') as $tier) {
            if ($jarakKm <= $tier['max_km']) {
                return $tier['harga'];
            }
        }

        return null;
    }
}