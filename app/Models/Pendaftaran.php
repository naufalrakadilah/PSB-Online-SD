<?php

namespace App\Models;

use App\Helpers\StuntingHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Model Pendaftaran - Representasi data pendaftaran siswa baru.
 *
 * Kompetensi:
 * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
 * - J.620100.021.02 - Menerapkan Akses Basis Data (Eloquent ORM)
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 *
 * @property int $id
 * @property string $no_pendaftaran
 * @property string $nama_lengkap
 * @property string $nisn
 * @property string $nik
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property float|null $tinggi_badan
 * @property string $agama
 * @property string $alamat
 * @property string $status
 * @property string $tahun_ajaran
 */
class Pendaftaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'pendaftaran';

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment.
     * Implementasi keamanan untuk mencegah mass assignment vulnerability.
     */
    protected $fillable = [
        'no_pendaftaran',
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'tinggi_badan',
        'agama',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'kode_pos',
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
        'foto_siswa',
        'akta_kelahiran',
        'kartu_keluarga',
        'ijazah_tk',
        'status',
        'catatan_admin',
        'tahun_ajaran',
    ];

    /**
     * Casting tipe data untuk atribut tertentu.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tinggi_badan' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate nomor pendaftaran secara otomatis.
     *
     * Algoritma: PSB-[TAHUN]-[BULAN]-[URUTAN 4 DIGIT]
     * Contoh: PSB-2026-10-0001
     *
     * Kompetensi J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
     *
     * @return string Nomor pendaftaran yang di-generate
     */
    public static function generateNoPendaftaran(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $prefix = "PSB-{$tahun}-{$bulan}";

        // Query untuk mendapatkan urutan terakhir
        $lastRecord = self::where('no_pendaftaran', 'like', "{$prefix}%")
            ->orderBy('no_pendaftaran', 'desc')
            ->first();

        if ($lastRecord) {
            // Ekstrak nomor urut dari pendaftaran terakhir
            $lastNumber = (int) substr($lastRecord->no_pendaftaran, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $noPendaftaran = $prefix.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Log::info('Generated nomor pendaftaran', ['no_pendaftaran' => $noPendaftaran]);

        return $noPendaftaran;
    }

    /**
     * Scope query untuk filter berdasarkan status.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query untuk pencarian berdasarkan nama atau nomor pendaftaran.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
        });
    }

    /**
     * Mendapatkan label status dengan warna badge.
     *
     * @return array{label: string, color: string}
     */
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

    /**
     * Mendapatkan umur calon siswa.
     *
     * @return int Umur dalam tahun
     */
    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    /**
     * Mendapatkan alamat lengkap yang sudah diformat.
     */
    public function getAlamatLengkapAttribute(): string
    {
        $parts = [$this->alamat];

        if ($this->rt && $this->rw) {
            $parts[] = "RT {$this->rt}/RW {$this->rw}";
        }
        if ($this->kelurahan) {
            $parts[] = "Kel. {$this->kelurahan}";
        }
        if ($this->kecamatan) {
            $parts[] = "Kec. {$this->kecamatan}";
        }
        if ($this->kota) {
            $parts[] = $this->kota;
        }
        if ($this->kode_pos) {
            $parts[] = $this->kode_pos;
        }

        return implode(', ', $parts);
    }

    /**
     * Mengukur status stunting untuk calon siswa pendaftar ini.
     *
     * @param  float  $tinggiBadan  Tinggi badan dalam cm
     * @param  bool  $asArray  True jika menginginkan format array
     */
    public function cekStunting(float $tinggiBadan, bool $asArray = false): mixed
    {
        return StuntingHelper::naufalfunction($tinggiBadan, $this, null, $asArray);
    }
}
