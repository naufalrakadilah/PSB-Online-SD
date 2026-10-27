<?php

namespace App\Models;

use App\Helpers\StuntingHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'no_pendaftaran',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'tinggi_badan',
        'agama',
        'alamat',
        'asal_sekolah',
        'nama_ayah',
        'pekerjaan_ayah',
        'no_hp_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_hp_ibu',
        'nama_wali',
        'pekerjaan_wali',
        'no_hp_wali',
        'status',
        'catatan_admin',
        'tahun_ajaran',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tinggi_badan' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function generateNoPendaftaran(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $prefix = "PSB-{$tahun}-{$bulan}";

        $lastRecord = self::where('no_pendaftaran', 'like', "{$prefix}%")
            ->orderBy('no_pendaftaran', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->no_pendaftaran, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $noPendaftaran = $prefix.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Log::info('Generated nomor pendaftaran', ['no_pendaftaran' => $noPendaftaran]);

        return $noPendaftaran;
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%");
        });
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'color' => 'warning'],
            'diverifikasi' => ['label' => 'Diverifikasi', 'color' => 'info'],
            'diterima' => ['label' => 'Diterima', 'color' => 'success'],
            'ditolak' => ['label' => 'Ditolak', 'color' => 'danger'],
            default => ['label' => 'Tidak Diketahui', 'color' => 'secondary'],
        };
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    public function cekStunting(float $tinggiBadan, bool $asArray = false): mixed
    {
        return StuntingHelper::naufalfunction($tinggiBadan, $this, null, $asArray);
    }
}
