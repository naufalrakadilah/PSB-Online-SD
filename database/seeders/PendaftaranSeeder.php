<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * PendaftaranSeeder - Mengisi data contoh pendaftar calon siswa SD
 * untuk demonstrasi seleksi, pengumuman, dan panel admin.
 *
 * Kompetensi:
 * - J.620100.021.02 - Menerapkan Akses Basis Data (Seeding)
 */
class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'no_pendaftaran' => 'PSB-2026-0001',
                'nama_lengkap' => 'Ahmad Fauzi Pratama',
                'nisn' => '0151234567',
                'nik' => '3201011504190001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2019-04-15', // Usia 7 tahun
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 122.5,
                'agama' => 'Islam',
                'alamat' => 'Jl. Kenanga No. 12',
                'rt' => '03',
                'rw' => '05',
                'kelurahan' => 'Sukajaya',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13450',
                'asal_sekolah' => 'TK Pertiwi 01',
                'nama_ayah' => 'Bambang Pratama',
                'pekerjaan_ayah' => 'Wiraswasta',
                'no_hp_ayah' => '081234567890',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'no_hp_ibu' => '081234567891',
                'status' => 'diterima',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => 'Usia memenuhi kriteria prioritas (7 tahun). Berkas lengkap dan sah. Harap daftar ulang membawa Akta asli.',
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-0002',
                'nama_lengkap' => 'Anindya Putri Kirana',
                'nisn' => '0157654321',
                'nik' => '3201015608190002',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2019-08-20', // Usia 6.5 tahun
                'jenis_kelamin' => 'Perempuan',
                'tinggi_badan' => 117.0,
                'agama' => 'Islam',
                'alamat' => 'Jl. Mawar Indah Blok B3',
                'rt' => '01',
                'rw' => '02',
                'kelurahan' => 'Cemara',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13451',
                'asal_sekolah' => 'RA Al-Falah',
                'nama_ayah' => 'Dedi Iskandar',
                'pekerjaan_ayah' => 'Pegawai Swasta',
                'no_hp_ayah' => '081398765432',
                'nama_ibu' => 'Rina Sulistiawati',
                'pekerjaan_ibu' => 'Guru',
                'no_hp_ibu' => '081398765433',
                'status' => 'diterima',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => 'Lolos verifikasi berkas dan usia zona 1.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-0003',
                'nama_lengkap' => 'Daniel Kevin Siregar',
                'nisn' => null,
                'nik' => '3201011211190003',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2019-11-12', // Usia 6 tahun 4 bulan
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 104.0,
                'agama' => 'Kristen',
                'alamat' => 'Komplek Harapan Mulia No. 45',
                'rt' => '04',
                'rw' => '07',
                'kelurahan' => 'Sukajaya',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13450',
                'asal_sekolah' => 'TK Kasih Ibu',
                'nama_ayah' => 'Rudi Siregar',
                'pekerjaan_ayah' => 'PNS',
                'no_hp_ayah' => '085211223344',
                'nama_ibu' => 'Marta Simanjuntak',
                'pekerjaan_ibu' => 'Bidan',
                'no_hp_ibu' => '085211223345',
                'status' => 'diverifikasi',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => 'Berkas telah diverifikasi lengkap. Menunggu rapat pleno kuota kelulusan.',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-0004',
                'nama_lengkap' => 'Zahra Aulia Rahmadani',
                'nisn' => null,
                'nik' => '3201014502200004',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2020-02-14', // Usia 6 tahun
                'jenis_kelamin' => 'Perempuan',
                'tinggi_badan' => 115.5,
                'agama' => 'Islam',
                'alamat' => 'Jl. Flamboyan No. 8',
                'rt' => '02',
                'rw' => '03',
                'kelurahan' => 'Mekarwangi',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13452',
                'asal_sekolah' => 'TK Islam Terpadu An-Nur',
                'nama_ayah' => 'Ahmad Suhendar',
                'pekerjaan_ayah' => 'TNI/Polri',
                'no_hp_ayah' => '087812345678',
                'nama_ibu' => 'Dewi Sartika',
                'pekerjaan_ibu' => 'PNS',
                'no_hp_ibu' => '087812345679',
                'status' => 'menunggu',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => null,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-0005',
                'nama_lengkap' => 'Muhammad Rizky Ramadhan',
                'nisn' => null,
                'nik' => '3201012501210005',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2021-01-25', // Usia 5 tahun (kurang dari syarat 6 th)
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 111.0,
                'agama' => 'Islam',
                'alamat' => 'Jl. Dahlia Raya No. 19',
                'rt' => '05',
                'rw' => '01',
                'kelurahan' => 'Cemara',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13451',
                'asal_sekolah' => 'PAUD Melati',
                'nama_ayah' => 'Hendra Setiawan',
                'pekerjaan_ayah' => 'Karyawan',
                'no_hp_ayah' => '089611223344',
                'nama_ibu' => 'Fitri Handayani',
                'pekerjaan_ibu' => 'Pedagang',
                'no_hp_ibu' => '089611223345',
                'status' => 'ditolak',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => 'Usia calon siswa per 1 Juli 2026 belum genap 6 tahun dan tidak menyertakan rekomendasi psikolog anak (Permendikbud No. 1 Tahun 2021).',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-0006',
                'nama_lengkap' => 'Gita Maharani',
                'nisn' => null,
                'nik' => '3201016705190006',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2019-05-18', // Usia 7 tahun
                'jenis_kelamin' => 'Perempuan',
                'tinggi_badan' => 101.5,
                'agama' => 'Hindu',
                'alamat' => 'Jl. Cempaka Putih No. 3',
                'rt' => '01',
                'rw' => '04',
                'kelurahan' => 'Sukajaya',
                'kecamatan' => 'Nusantara',
                'kota' => 'Kota Nusantara',
                'kode_pos' => '13450',
                'asal_sekolah' => 'TK Dharma Wanita',
                'nama_ayah' => 'Wayan Sudarma',
                'pekerjaan_ayah' => 'Seniman',
                'no_hp_ayah' => '082155667788',
                'nama_ibu' => 'Ni Ketut Ratna',
                'pekerjaan_ibu' => 'Wiraswasta',
                'no_hp_ibu' => '082155667789',
                'status' => 'menunggu',
                'tahun_ajaran' => '2026/2027',
                'catatan_admin' => null,
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($samples as $data) {
            Pendaftaran::updateOrCreate(
                ['no_pendaftaran' => $data['no_pendaftaran']],
                $data
            );
        }
    }
}
