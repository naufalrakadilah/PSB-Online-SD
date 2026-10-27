<?php

namespace App\Helpers;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * StuntingHelper - Layanan penghitungan Z-score antropometri WHO 2007
 * dan penentuan status stunting anak.
 *
 * Rumus Z-score (Standar WHO):
 * Z = (X - mu) / sigma
 *
 * Keterangan:
 * - Z     = Nilai Z-score
 * - X     = Tinggi badan anak yang diukur (cm)
 * - mu    = Nilai median tinggi badan anak standar WHO (cm)
 * - sigma = Simpangan baku standar WHO (cm)
 *
 * Kategori Stunting (Berdasarkan Z-score TB/U):
 * - Z < -3.0 SD        : Sangat Pendek (Severely Stunted) -> Stunting
 * - -3.0 s/d -2.0 SD   : Pendek (Stunted)                 -> Stunting
 * - -2.0 < Z <= 2.0 SD : Normal                           -> Tidak Stunting
 * - Z > 2.0 SD         : Tinggi                           -> Tidak Stunting
 *
 * Kompetensi:
 * - J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 */
class StuntingHelper
{
    /**
     * Tabel Referensi WHO 2007 Median dan Simpangan Baku (SD)
     * untuk anak usia masuk sekolah dasar (5 tahun 1 bulan, 6 tahun, 7 tahun).
     *
     * @var array<string, array<string, mixed>>
     */
    public const WHO_STANDARDS = [
        '5 tahun 1 bulan' => [
            'bulan' => 61,
            'Laki-laki' => [
                'median' => 110.3,
                'sd' => 4.59,
            ],
            'Perempuan' => [
                'median' => 109.6,
                'sd' => 4.77,
            ],
        ],
        '6 tahun' => [
            'bulan' => 72,
            'Laki-laki' => [
                'median' => 116.0,
                'sd' => 4.93,
            ],
            'Perempuan' => [
                'median' => 115.1,
                'sd' => 5.12,
            ],
        ],
        '7 tahun' => [
            'bulan' => 84,
            'Laki-laki' => [
                'median' => 121.7,
                'sd' => 5.29,
            ],
            'Perempuan' => [
                'median' => 120.8,
                'sd' => 5.47,
            ],
        ],
    ];

    /**
     * Mengukur tinggi badan anak apakah stunting atau tidak (naufalfunction).
     *
     * Mendukung berbagai bentuk pemanggilan:
     * 1. Direct: naufalfunction(80, 86, 3) -> (tinggi, median, sd)
     * 2. Standar WHO: naufalfunction(105, '5 tahun 1 bulan', 'Laki-laki')
     * 3. Standar WHO (numerik): naufalfunction(105, 6, 'Perempuan')
     * 4. Model: naufalfunction(110, $pendaftaran) -> umur & gender diambil dari data pendaftar
     *
     * @param  mixed  $tinggi  Tinggi badan (cm)
     * @param  mixed  $param2  Nilai median (float) ATAU umur anak (string/int) ATAU model Pendaftaran
     * @param  mixed  $param3  Nilai simpangan baku (float) ATAU jenis kelamin (string)
     * @param  bool  $asArray  Jika true, hasil dikembalikan dalam bentuk array asosiatif murni
     */
    public static function naufalfunction(
        mixed $tinggi,
        mixed $param2 = null,
        mixed $param3 = null,
        bool $asArray = false
    ): StuntingResult|array {
        $parsed = self::parseArguments($tinggi, $param2, $param3);

        $tinggiBadan = (float) $parsed['tinggi'];
        $median = (float) $parsed['median'];
        $sd = (float) $parsed['sd'];
        $umur = $parsed['umur'];
        $jenisKelamin = $parsed['jenis_kelamin'];

        if ($sd <= 0.0) {
            throw new InvalidArgumentException('Simpangan baku (sigma) harus bernilai lebih besar dari 0.');
        }

        // Rumus Z-score: Z = (X - mu) / sigma
        $zScore = round(($tinggiBadan - $median) / $sd, 2);

        // Penentuan status dan kategori stunting berdasarkan standar WHO
        if ($zScore < -3.0) {
            $isStunting = true;
            $status = 'Stunting';
            $kategori = 'Sangat Pendek (Severely Stunted)';
            $keterangan = sprintf(
                'Nilai Z-score adalah %.2f SD. Tinggi badan anak (%.1f cm) jauh di bawah standar WHO, dikategorikan Stunting Berat (%s).',
                $zScore,
                $tinggiBadan,
                $kategori
            );
        } elseif ($zScore <= -2.0) {
            $isStunting = true;
            $status = 'Stunting';
            $kategori = 'Pendek (Stunted)';
            $keterangan = sprintf(
                'Nilai Z-score adalah %.2f SD. Tinggi badan anak (%.1f cm) berada di bawah ambang batas -2 SD, dikategorikan Stunting (%s).',
                $zScore,
                $tinggiBadan,
                $kategori
            );
        } elseif ($zScore <= 2.0) {
            $isStunting = false;
            $status = 'Tidak Stunting';
            $kategori = 'Normal';
            $keterangan = sprintf(
                'Nilai Z-score adalah %.2f SD. Tinggi badan anak (%.1f cm) berada dalam rentang normal standar WHO (Tidak Stunting).',
                $zScore,
                $tinggiBadan
            );
        } else {
            $isStunting = false;
            $status = 'Tidak Stunting';
            $kategori = 'Tinggi';
            $keterangan = sprintf(
                'Nilai Z-score adalah %.2f SD. Tinggi badan anak (%.1f cm) berada di atas rata-rata standar WHO (%s).',
                $zScore,
                $tinggiBadan,
                $kategori
            );
        }

        $result = new StuntingResult(
            tinggi_badan: $tinggiBadan,
            median: $median,
            simpangan_baku: $sd,
            z_score: $zScore,
            is_stunting: $isStunting,
            status: $status,
            kategori: $kategori,
            keterangan: $keterangan,
            umur: $umur,
            jenis_kelamin: $jenisKelamin
        );

        return $asArray ? $result->toArray() : $result;
    }

    /**
     * Mem-parsing parameter yang fleksibel ke bentuk standar.
     *
     * @return array{tinggi: float, median: float, sd: float, umur: ?string, jenis_kelamin: ?string}
     */
    protected static function parseArguments(mixed $p1, mixed $p2, mixed $p3): array
    {
        // Kasus 1: Parameter 2 adalah model Pendaftaran: (tinggi, $pendaftaran)
        if ($p2 instanceof Pendaftaran) {
            $tinggi = (float) $p1;
            $lookup = self::lookupStandardFromPendaftaran($p2);

            return [
                'tinggi' => $tinggi,
                'median' => $lookup['median'],
                'sd' => $lookup['sd'],
                'umur' => $lookup['umur_key'],
                'jenis_kelamin' => $lookup['jenis_kelamin'],
            ];
        }

        // Kasus 2: Tiga parameter numerik langsung: (tinggi, median, sd)
        // Contoh Gambar 1: naufalfunction(80, 86, 3)
        if (is_numeric($p1) && is_numeric($p2) && is_numeric($p3)) {
            return [
                'tinggi' => (float) $p1,
                'median' => (float) $p2,
                'sd' => (float) $p3,
                'umur' => null,
                'jenis_kelamin' => null,
            ];
        }

        // Kasus 3: Parameter dengan umur dan jenis kelamin: (tinggi, umur, jenis_kelamin)
        // Contoh Gambar 2: naufalfunction(105, '5 tahun 1 bulan', 'Laki-laki')
        $lookup = self::lookupWhoStandard($p2, $p3);

        return [
            'tinggi' => (float) $p1,
            'median' => $lookup['median'],
            'sd' => $lookup['sd'],
            'umur' => $lookup['umur_key'],
            'jenis_kelamin' => $lookup['jenis_kelamin'],
        ];
    }

    /**
     * Mencari nilai median dan simpangan baku standar WHO 2007 berdasarkan umur dan jenis kelamin.
     *
     * @return array{median: float, sd: float, umur_key: string, jenis_kelamin: string}
     */
    public static function lookupWhoStandard(mixed $umur, mixed $jenisKelamin): array
    {
        $genderNormalized = self::normalizeGender((string) $jenisKelamin);
        $ageKey = self::normalizeAge($umur);

        $standard = self::WHO_STANDARDS[$ageKey][$genderNormalized];

        return [
            'median' => (float) $standard['median'],
            'sd' => (float) $standard['sd'],
            'umur_key' => $ageKey,
            'jenis_kelamin' => $genderNormalized,
        ];
    }

    /**
     * Normalisasi string atau angka umur ke kunci tabel referensi.
     *
     * Contoh: 7 / '7 tahun' -> '7 tahun', '72 bulan' -> '6 tahun', '5 tahun 1 bulan' -> '5 tahun 1 bulan'.
     * Angka di atas 12 tanpa kata "tahun" dianggap sebagai bulan.
     */
    public static function normalizeAge(mixed $umur): string
    {
        if (is_null($umur)) {
            return '6 tahun';
        }

        $text = strtolower(trim((string) $umur));

        if (array_key_exists($text, self::WHO_STANDARDS)) {
            return $text;
        }

        if (! preg_match_all('/\d+(?:\.\d+)?/', $text, $matches)) {
            return '6 tahun';
        }

        $angka = array_map('floatval', $matches[0]);

        if (str_contains($text, 'tahun')) {
            $totalBulan = $angka[0] * 12 + ($angka[1] ?? 0);
        } elseif (str_contains($text, 'bulan') || str_contains($text, 'bln') || $angka[0] > 12) {
            $totalBulan = $angka[0];
        } else {
            $totalBulan = $angka[0] * 12;
        }

        return self::ageKeyFromMonths($totalBulan);
    }

    /**
     * Menentukan kunci tabel referensi WHO dari umur dalam bulan.
     */
    protected static function ageKeyFromMonths(float $totalBulan): string
    {
        if ($totalBulan <= 66) { // ~5 tahun 6 bulan ke bawah
            return '5 tahun 1 bulan';
        }

        if ($totalBulan >= 79) { // ~6 tahun 7 bulan ke atas
            return '7 tahun';
        }

        return '6 tahun';
    }

    /**
     * Normalisasi string jenis kelamin ('Laki-laki' atau 'Perempuan').
     */
    public static function normalizeGender(?string $gender): string
    {
        if (! $gender) {
            return 'Laki-laki';
        }

        $g = strtolower(trim($gender));

        if (str_starts_with($g, 'p') || str_contains($g, 'perempuan') || str_contains($g, 'wanita') || str_contains($g, 'female')) {
            return 'Perempuan';
        }

        return 'Laki-laki';
    }

    /**
     * Mendapatkan standar WHO dari model pendaftaran berdasarkan tanggal lahir dan gender.
     *
     * @return array{median: float, sd: float, umur_key: string, jenis_kelamin: string}
     */
    protected static function lookupStandardFromPendaftaran(Pendaftaran $pendaftaran): array
    {
        $gender = $pendaftaran->jenis_kelamin ?? 'Laki-laki';

        $ageKey = $pendaftaran->tanggal_lahir
            ? self::ageKeyFromMonths(Carbon::parse($pendaftaran->tanggal_lahir)->diffInMonths(Carbon::now()))
            : '6 tahun';

        return self::lookupWhoStandard($ageKey, $gender);
    }
}
