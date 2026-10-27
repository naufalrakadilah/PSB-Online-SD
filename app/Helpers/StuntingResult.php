<?php

namespace App\Helpers;

use ArrayAccess;
use JsonSerializable;
use Stringable;

/**
 * StuntingResult - Value Object representasi hasil pengukuran status stunting anak.
 *
 * Mendukung akses sebagai:
 * - String: echo $result (menghasilkan "Stunting" atau "Tidak Stunting")
 * - Array: $result['status'], $result['z_score'], $result['is_stunting']
 * - Object: $result->status, $result->z_score, $result->is_stunting
 * - JSON: json_encode($result)
 *
 * Kompetensi:
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 */
class StuntingResult implements ArrayAccess, JsonSerializable, Stringable
{
    /**
     * @param  float  $tinggi_badan  Nilai tinggi badan aktual anak (X) dalam cm
     * @param  float  $median  Nilai median standar WHO (mu) dalam cm
     * @param  float  $simpangan_baku  Nilai simpangan baku standar WHO (sigma) dalam cm
     * @param  float  $z_score  Nilai Z-score yang dihitung
     * @param  bool  $is_stunting  Status apakah anak mengalami stunting (Z <= -2.0)
     * @param  string  $status  Label status ("Stunting" atau "Tidak Stunting")
     * @param  string  $kategori  Kategori antropometri WHO
     * @param  string  $keterangan  Penjelasan lengkap hasil analisis
     * @param  string|null  $umur  Keterangan umur anak
     * @param  string|null  $jenis_kelamin  Jenis kelamin anak
     */
    public function __construct(
        public float $tinggi_badan,
        public float $median,
        public float $simpangan_baku,
        public float $z_score,
        public bool $is_stunting,
        public string $status,
        public string $kategori,
        public string $keterangan,
        public ?string $umur = null,
        public ?string $jenis_kelamin = null,
    ) {}

    /**
     * Representasi string saat di-echo atau di-cast ke string.
     */
    public function __toString(): string
    {
        return $this->status;
    }

    /**
     * Mengubah data hasil ke format array asosiatif.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'tinggi_badan' => $this->tinggi_badan,
            'median' => $this->median,
            'simpangan_baku' => $this->simpangan_baku,
            'sd' => $this->simpangan_baku,
            'z_score' => $this->z_score,
            'is_stunting' => $this->is_stunting,
            'stunting' => $this->is_stunting,
            'status' => $this->status,
            'kategori' => $this->kategori,
            'keterangan' => $this->keterangan,
            'umur' => $this->umur,
            'jenis_kelamin' => $this->jenis_kelamin,
        ];
    }

    /**
     * Serialisasi ke format JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * ArrayAccess: offsetExists
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    /**
     * ArrayAccess: offsetGet
     */
    public function offsetGet(mixed $offset): mixed
    {
        $data = $this->toArray();

        return $data[$offset] ?? null;
    }

    /**
     * ArrayAccess: offsetSet
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Read-only Value Object
    }

    /**
     * ArrayAccess: offsetUnset
     */
    public function offsetUnset(mixed $offset): void
    {
        // Read-only Value Object
    }
}
