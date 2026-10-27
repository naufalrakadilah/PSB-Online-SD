<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'no_pendaftaran' => 'PSB-2026-10-0001',
                'nama_lengkap' => 'Ahmad Fauzi Pratama',
                'nik' => '3201011504190001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2019-04-15',
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 122.5,
                'agama' => 'Islam',
                'alamat' => 'Jl. Kenanga No. 12, RT 03/RW 05, Kel. Sukajaya, Kec. Nusantara, Kota Nusantara 13450',
                'asal_sekolah' => 'TK Pertiwi 01',
                'nama_ayah' => 'Bambang Pratama',
                'pekerjaan_ayah' => 'Wiraswasta',
                'no_hp_ayah' => '081234567890',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'no_hp_ibu' => '081234567891',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-10-0002',
                'nama_lengkap' => 'Anindya Putri Kirana',
                'nik' => '3273015608200002',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2020-08-20',
                'jenis_kelamin' => 'Perempuan',
                'tinggi_badan' => 112.0,
                'agama' => 'Islam',
                'alamat' => 'Jl. Mawar Indah Blok B3, RT 01/RW 02, Kel. Cemara, Kec. Coblong, Kota Bandung 40132',
                'asal_sekolah' => 'TK Islam Al Azhar',
                'nama_ayah' => 'Hendra Kusuma',
                'pekerjaan_ayah' => 'Pegawai Negeri Sipil',
                'no_hp_ayah' => '082112345678',
                'nama_ibu' => 'Rina Marlina',
                'pekerjaan_ibu' => 'Guru',
                'no_hp_ibu' => '082187654321',
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-10-0003',
                'nama_lengkap' => 'Daniel Kevin Siregar',
                'nik' => '1271011211190003',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2019-11-12',
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 104.0,
                'agama' => 'Kristen',
                'alamat' => 'Komplek Harapan Mulia No. 45, RT 04/RW 07, Kel. Sei Sikambing, Kec. Medan Sunggal, Kota Medan 20122',
                'asal_sekolah' => 'TK Methodist 2',
                'nama_ayah' => 'Robert Siregar',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'no_hp_ayah' => '085261234567',
                'nama_ibu' => 'Maria Simanjuntak',
                'pekerjaan_ibu' => 'Perawat',
                'no_hp_ibu' => '085267654321',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-10-0004',
                'nama_lengkap' => 'Ni Putu Ayu Lestari',
                'nik' => '5171015403210004',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '2021-03-14',
                'jenis_kelamin' => 'Perempuan',
                'tinggi_badan' => 108.5,
                'agama' => 'Hindu',
                'alamat' => 'Jl. Gatot Subroto Barat Gg. Melati No. 7, Kel. Padangsambian, Kec. Denpasar Barat, Kota Denpasar 80117',
                'asal_sekolah' => null,
                'nama_ayah' => 'I Made Suardana',
                'pekerjaan_ayah' => 'Petani',
                'no_hp_ayah' => '081337123456',
                'nama_ibu' => 'Ni Kadek Sari',
                'pekerjaan_ibu' => 'Pedagang',
                'no_hp_ibu' => null,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'no_pendaftaran' => 'PSB-2026-10-0005',
                'nama_lengkap' => 'Kevin Wijaya',
                'nik' => '3578010907180005',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2018-07-09',
                'jenis_kelamin' => 'Laki-laki',
                'tinggi_badan' => 128.0,
                'agama' => 'Buddha',
                'alamat' => 'Jl. Kertajaya Indah No. 88, RT 06/RW 03, Kel. Manyar Sabrangan, Kec. Mulyorejo, Kota Surabaya 60116',
                'asal_sekolah' => 'TK Kristen Petra',
                'nama_ayah' => 'Hartono Wijaya',
                'pekerjaan_ayah' => 'Pengusaha',
                'no_hp_ayah' => '081931234567',
                'nama_ibu' => 'Lina Gunawan',
                'pekerjaan_ibu' => 'Dokter',
                'no_hp_ibu' => '081937654321',
                'nama_wali' => 'Susanto Wijaya',
                'pekerjaan_wali' => 'Pensiunan',
                'no_hp_wali' => '081311223344',
                'created_at' => Carbon::now()->subDay(),
            ],
        ];

        foreach ($samples as $data) {
            Pendaftaran::updateOrCreate(
                ['no_pendaftaran' => $data['no_pendaftaran']],
                $data + ['status' => 'menunggu', 'tahun_ajaran' => '2026/2027']
            );
        }
    }
}
