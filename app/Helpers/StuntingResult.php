<?php

namespace App\Helpers;

use ArrayAccess;
use JsonSerializable;
use Stringable;

class StuntingResult implements ArrayAccess, JsonSerializable, Stringable
{
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

    public function __toString(): string
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'tinggi_badan' => $this->tinggi_badan,
            'median' => $this->median,
            'simpangan_baku' => $this->simpangan_baku,
            'z_score' => $this->z_score,
            'is_stunting' => $this->is_stunting,
            'status' => $this->status,
            'kategori' => $this->kategori,
            'keterangan' => $this->keterangan,
            'umur' => $this->umur,
            'jenis_kelamin' => $this->jenis_kelamin,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    public function offsetGet(mixed $offset): mixed
    {
        $data = $this->toArray();

        return $data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void {}

    public function offsetUnset(mixed $offset): void {}
}
