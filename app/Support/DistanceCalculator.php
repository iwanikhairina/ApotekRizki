<?php

namespace App\Support;

class DistanceCalculator
{
    /**
     * Hitung jarak garis lurus (Haversine) antara dua titik koordinat, dalam km.
     */
    public static function km(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Hitung ongkir berdasarkan jarak, pakai tier dari config/apotek.php.
     * Return null kalau jarak melebihi radius layanan (tidak bisa diantar).
     */
    public static function ongkirUntukJarak(float $jarakKm): ?int
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