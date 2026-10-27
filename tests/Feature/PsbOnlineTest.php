<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PsbOnlineTest - Pengujian fungsional dan statis fitur PSB Online SD.
 *
 * Kompetensi:
 * - J.620100.036.02 - Melaksanakan Pengujian Kode Program Secara Statis
 * - J.620100.032.01 - Menerapkan Code Review
 * - J.620100.025.02 - Melakukan Debugging
 */
class PsbOnlineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        AdminSeeder::class;
        $this->seed(AdminSeeder::class);
    }

    /**
     * Test 1: Halaman beranda dapat diakses publik.
     */
    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PSB Online SD');
    }

    /**
     * Test 2: Halaman form pendaftaran dapat diakses.
     */
    public function test_registration_form_can_be_rendered(): void
    {
        $response = $this->get('/pendaftaran');
        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran');
    }

    /**
     * Test 3: Pendaftaran siswa baru berhasil dengan data valid.
     * Menguji algoritma penomoran PSB-YYYY-XXXX (J.620100.022.02)
     */
    public function test_student_can_register_with_valid_data(): void
    {
        $formData = [
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nik' => '3201019901190009',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10',
            'nama_ayah' => 'Budi Santoso',
            'nama_ibu' => 'Siti Nurhaliza',
        ];

        $response = $this->post('/pendaftaran', $formData);

        $this->assertDatabaseHas('pendaftaran', [
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nik' => '3201019901190009',
            'status' => 'menunggu',
        ]);

        $pendaftaran = Pendaftaran::where('nik', '3201019901190009')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertStringStartsWith('PSB-2026-', $pendaftaran->no_pendaftaran);

        $response->assertRedirect(route('pendaftaran.success', $pendaftaran->no_pendaftaran));
    }

    /**
     * Test 4: Validasi NIK harus 16 digit.
     */
    public function test_registration_fails_if_nik_is_not_16_digits(): void
    {
        $formData = [
            'nama_lengkap' => 'Anak Tanpa NIK Benar',
            'nik' => '123', // Salah, bukan 16 digit
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10',
            'nama_ayah' => 'Budi',
            'nama_ibu' => 'Siti',
        ];

        $response = $this->post('/pendaftaran', $formData);
        $response->assertSessionHasErrors(['nik']);
    }

    /**
     * Test 5: NIK yang sudah terdaftar tidak dapat didaftarkan ulang.
     */
    public function test_registration_fails_if_nik_already_exists(): void
    {
        Pendaftaran::create([
            'no_pendaftaran' => 'PSB-2026-0001',
            'nama_lengkap' => 'Siswa Pertama',
            'nik' => '3201019901190009',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Jl. Uji Duplication',
            'nama_ayah' => 'Ayah Duplication',
            'nama_ibu' => 'Ibu Duplication',
            'status' => 'menunggu',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Siswa Kedua',
            'nik' => '3201019901190009',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-04-12',
            'jenis_kelamin' => 'Perempuan',
            'agama' => 'Islam',
            'alamat' => 'Jl. Uji Duplication 2',
            'nama_ayah' => 'Ayah Kedua',
            'nama_ibu' => 'Ibu Kedua',
        ]);

        $response->assertSessionHasErrors(['nik']);
    }

    /**
     * Test 6: Cek status pendaftaran menemukan nomor pendaftaran valid.
     */
    public function test_cek_status_finds_existing_registration(): void
    {
        $pendaftar = Pendaftaran::create([
            'no_pendaftaran' => 'PSB-2026-9999',
            'nama_lengkap' => 'Tes Siswa Status',
            'nik' => '3201019999999999',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Jl. Uji Status',
            'nama_ayah' => 'Ayah Tes',
            'nama_ibu' => 'Ibu Tes',
            'status' => 'diverifikasi',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->get('/cek-status?no_pendaftaran='.$pendaftar->no_pendaftaran);
        $response->assertStatus(200);
        $response->assertSee('Tes Siswa Status');
    }

    /**
     * Test 6: Halaman pengumuman dapat diakses publik.
     */
    public function test_pengumuman_page_can_be_rendered(): void
    {
        $response = $this->get('/pengumuman');
        $response->assertStatus(200);
        $response->assertSee('Pengumuman Hasil Seleksi');
    }

    /**
     * Test 7: Admin dapat login dengan kredensial yang valid.
     */
    public function test_admin_can_login(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@psbsd.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * Test 8: Tamu yang tidak login dialihkan saat mengakses dashboard admin.
     */
    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test 9: Admin dapat memeriksa status stunting siswa via route admin.cek-stunting.
     */
    public function test_admin_can_check_stunting_with_naufalfunction(): void
    {
        $admin = User::first();

        // Tes JSON response
        $response = $this->actingAs($admin)->postJson(route('admin.cek-stunting'), [
            'tinggi' => 80,
            'median' => 86,
            'sd' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Stunting',
            'is_stunting' => true,
            'z_score' => -2.0,
        ]);
    }
}
