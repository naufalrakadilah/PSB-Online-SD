<?php

namespace App\Helpers;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use InvalidArgumentException;

class StuntingHelper
{
    public const WHO_STANDARDS = [
        '5 tahun 1 bulan' => [
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

        $zScore = round(($tinggiBadan - $median) / $sd, 2);

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

    protected static function parseArguments(mixed $p1, mixed $p2, mixed $p3): array
    {
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

        if (is_numeric($p1) && is_numeric($p2) && is_numeric($p3)) {
            return [
                'tinggi' => (float) $p1,
                'median' => (float) $p2,
                'sd' => (float) $p3,
                'umur' => null,
                'jenis_kelamin' => null,
            ];
        }

        $lookup = self::lookupWhoStandard($p2, $p3);

        return [
            'tinggi' => (float) $p1,
            'median' => $lookup['median'],
            'sd' => $lookup['sd'],
            'umur' => $lookup['umur_key'],
            'jenis_kelamin' => $lookup['jenis_kelamin'],
        ];
    }

    protected static function lookupWhoStandard(mixed $umur, mixed $jenisKelamin): array
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

    protected static function ageKeyFromMonths(float $totalBulan): string
    {
        if ($totalBulan <= 66) {
            return '5 tahun 1 bulan';
        }

        if ($totalBulan >= 79) {
            return '7 tahun';
        }

        return '6 tahun';
    }

    protected static function normalizeGender(?string $gender): string
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

    protected static function lookupStandardFromPendaftaran(Pendaftaran $pendaftaran): array
    {
        $gender = $pendaftaran->jenis_kelamin ?? 'Laki-laki';

        $ageKey = $pendaftaran->tanggal_lahir
            ? self::ageKeyFromMonths(Carbon::parse($pendaftaran->tanggal_lahir)->diffInMonths(Carbon::now()))
            : '6 tahun';

        return self::lookupWhoStandard($ageKey, $gender);
    }
}
