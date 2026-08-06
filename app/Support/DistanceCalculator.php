<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceCalculator
{
    /**
     * Hitung jarak garis lurus (Haversine) antara dua titik koordinat, dalam km.
     * Sekarang HANYA dipakai sebagai fallback internal saat OSRM tidak bisa
     * diakses (lihat route()) — jarak yang dipakai untuk ongkir, estimasi
     * waktu, dsb tetap harus lewat route() supaya berbasis jarak jalan.
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
     * Hitung jarak tempuh JALAN SEBENARNYA (bukan garis lurus) antara dua
     * titik koordinat, memakai data routing OpenStreetMap (OSRM) — sumber
     * data yang sama dipakai untuk peta pemilihan alamat customer, supaya
     * jarak, ongkir, dan estimasi kurir konsisten dengan yang dilihat customer.
     *
     * Estimasi waktu TIDAK diambil dari durasi mentah OSRM, melainkan selalu
     * dihitung dari tabel estimasi tetap berdasarkan jarak hasil rute
     * (lihat estimasiMenitUntukJarak), supaya konsisten di seluruh sistem.
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

                    if ($route && isset($route['distance'])) {
                        $jarakKm = round($route['distance'] / 1000, 2);

                        return [
                            'jarak_km'       => $jarakKm,
                            'estimasi_menit' => self::estimasiMenitUntukJarak($jarakKm),
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

            // Fallback: Haversine (garis lurus). Estimasi waktu tetap memakai
            // tabel estimasi yang sama supaya konsisten walau sumber jaraknya
            // sedang fallback.
            $jarakKm = self::km($lat1, $lng1, $lat2, $lng2);

            return [
                'jarak_km'       => $jarakKm,
                'estimasi_menit' => self::estimasiMenitUntukJarak($jarakKm),
                'sumber'         => 'haversine_fallback',
            ];
        });
    }

    /**
     * Ongkos kirim otomatis berdasarkan jarak tempuh jalan (km):
     * - Jarak <= 0,5 km: gratis (Rp0)
     * - Setelah 0,5 km, setiap kelipatan 0,5 km berikutnya (dibulatkan ke
     *   atas) dikenakan Rp3.000. Contoh: 0,5 km = Rp0; 0,5-1 km = Rp3.000;
     *   1-1,5 km = Rp6.000; 1,5-2 km = Rp9.000; dst.
     * Tidak ada batas jarak maksimum di sini — area layanan divalidasi
     * secara terpisah berdasarkan kecamatan (lihat areaDilayani()).
     */
    public static function ongkirUntukJarak(float $jarakKm): float
    {
        $gratisHinggaKm = (float) config('apotek.ongkir_gratis_hingga_km', 0.5);

        if ($jarakKm <= $gratisHinggaKm) {
            return 0;
        }

        $stepKm = (float) config('apotek.ongkir_per_step_km', 0.5);
        $hargaPerStep = (float) config('apotek.ongkir_per_step_harga', 3000);

        $jumlahStep = (int) ceil(($jarakKm - $gratisHinggaKm) / $stepKm);

        return $jumlahStep * $hargaPerStep;
    }

    /**
     * Estimasi waktu pengantaran (menit) berdasarkan tabel tetap:
     * 0,5 km = 1 menit, 1 km = 2 menit, 2 km = 4 menit, ... 18 km = 36 menit
     * (rasio 2 menit per km, dibulatkan, minimum 1 menit).
     */
    public static function estimasiMenitUntukJarak(?float $jarakKm): int
    {
        if (! $jarakKm) {
            return 0;
        }

        return (int) max(1, round($jarakKm * 2));
    }

    /**
     * Daftar kecamatan yang dilayani. Diambil dari config('apotek.kecamatan_dilayani'),
     * TAPI dengan fallback hardcode di sini juga — supaya kalau config sempat
     * ke-cache versi lama (mis. lupa jalankan `php artisan config:clear`
     * setelah update), validasi area tetap jalan benar dan tidak
     * menolak semua alamat begitu saja.
     */
    private const KECAMATAN_DILAYANI_DEFAULT = ['Kebayakan', 'Bebesen', 'Pegasing', 'Lut Tawar'];

    public static function kecamatanDilayani(): array
    {
        $daftar = config('apotek.kecamatan_dilayani');

        return is_array($daftar) && count($daftar) > 0
            ? $daftar
            : self::KECAMATAN_DILAYANI_DEFAULT;
    }

    /**
     * Cek apakah sebuah nama kecamatan termasuk area yang dilayani
     * pengantaran Apotek Rizki (lihat kecamatanDilayani()).
     * Perbandingan tidak peka besar-kecil huruf, mengabaikan prefix
     * "Kec."/"Kecamatan", dan cukup "mengandung" nama kecamatannya —
     * supaya teks alamat yang lebih panjang dari hasil reverse-geocode
     * (mis. "Kecamatan Bebesen, Aceh Tengah" atau "Bebesen, Aceh Tengah")
     * tetap terdeteksi, tidak cuma exact match.
     */
    public static function areaDilayani(?string $kecamatan): bool
    {
        $normalisasi = self::normalisasiKecamatan($kecamatan);

        if ($normalisasi === '') {
            return false;
        }

        foreach (self::kecamatanDilayani() as $kecamatanDilayani) {
            $target = self::normalisasiKecamatan($kecamatanDilayani);

            if ($target !== '' && (
                $normalisasi === $target
                || str_contains($normalisasi, $target)
                || str_contains($target, $normalisasi)
            )) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sama seperti areaDilayani(), TAPI dengan fallback tambahan: kalau
     * kolom kecamatan gagal cocok (mis. data lama yang tersimpan sebelum
     * perbaikan ekstraksi kecamatan di form tambah-alamat, sehingga
     * isinya keliru — misal kena nama desa, bukan nama kecamatan),
     * dicoba lagi dengan mencari nama kecamatan yang dilayani di dalam
     * teks alamat lengkap (mis. "Umang, Bebesen, Aceh Tengah, ...").
     *
     * Pakai method ini (bukan areaDilayani() polos) di mana pun kita
     * sudah punya alamat lengkap user, supaya alamat lama yang datanya
     * sempat salah tersimpan tetap bisa lolos selama teks alamatnya
     * memang menyebut kecamatan yang dilayani.
     */
    public static function areaDilayaniUntukUser(?string $kecamatan, ?string $alamatLengkap = null): bool
    {
        if (self::areaDilayani($kecamatan)) {
            return true;
        }

        $alamatNormalisasi = strtolower(trim((string) $alamatLengkap));

        if ($alamatNormalisasi === '') {
            return false;
        }

        foreach (self::kecamatanDilayani() as $kecamatanDilayani) {
            $target = strtolower(trim($kecamatanDilayani));

            if ($target !== '' && str_contains($alamatNormalisasi, $target)) {
                return true;
            }
        }

        return false;
    }

    private static function normalisasiKecamatan(?string $kecamatan): string
    {
        $kecamatan = strtolower(trim((string) $kecamatan));
        $kecamatan = preg_replace('/^kec(amatan)?\.?\s+/', '', $kecamatan);

        return trim($kecamatan);
    }
}