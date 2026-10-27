<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PsbOnlineTest extends TestCase
{
    use RefreshDatabase;

    private const TANGGAL_PENDAFTARAN = '2026-10-27';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdminSeeder::class);
    }

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PSB Online SD');
    }

    public function test_registration_form_can_be_rendered(): void
    {
        $response = $this->get('/pendaftaran');
        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran');
    }

    public function test_student_can_register_with_valid_data(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

        $formData = [
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nik' => '3201019901190009',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'tinggi_badan' => 118.5,
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10',
            'nama_ayah' => 'Budi Santoso',
            'nama_ibu' => 'Siti Nurhaliza',
        ];

        $response = $this->post('/pendaftaran', $formData);

        $this->assertDatabaseHas('pendaftaran', [
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nik' => '3201019901190009',
            'tinggi_badan' => 118.5,
            'status' => 'menunggu',
        ]);

        $pendaftaran = Pendaftaran::where('nik', '3201019901190009')->first();
        $this->assertNotNull($pendaftaran);
        $this->assertStringStartsWith('PSB-2026-', $pendaftaran->no_pendaftaran);

        $response->assertRedirect(route('pendaftaran.success', $pendaftaran->no_pendaftaran));
    }

    public function test_registration_fails_if_nik_is_not_16_digits(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

        $formData = [
            'nama_lengkap' => 'Anak Tanpa NIK Benar',
            'nik' => '123',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'tinggi_badan' => 118.5,
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10',
            'nama_ayah' => 'Budi',
            'nama_ibu' => 'Siti',
        ];

        $response = $this->post('/pendaftaran', $formData);
        $response->assertSessionHasErrors(['nik']);
    }

    public function test_registration_fails_if_nik_already_exists(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

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
            'tinggi_badan' => 116.0,
            'agama' => 'Islam',
            'alamat' => 'Jl. Uji Duplication 2',
            'nama_ayah' => 'Ayah Kedua',
            'nama_ibu' => 'Ibu Kedua',
        ]);

        $response->assertSessionHasErrors(['nik']);
    }

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

    public function test_pengumuman_page_can_be_rendered(): void
    {
        $response = $this->get('/pengumuman');
        $response->assertStatus(200);
        $response->assertSee('Pengumuman Hasil Seleksi');
    }

    public function test_admin_can_login(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@psbsd.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_check_stunting_with_naufalfunction(): void
    {
        $admin = User::first();

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

    public function test_registration_fails_without_tinggi_badan(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

        $response = $this->post('/pendaftaran', [
            'nama_lengkap' => 'Anak Tanpa Tinggi',
            'nik' => '3201019901190010',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10',
            'nama_ayah' => 'Budi',
            'nama_ibu' => 'Siti',
        ]);

        $response->assertSessionHasErrors(['tinggi_badan']);
    }

    public function test_admin_detail_shows_stunting_result_from_registered_height(): void
    {
        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => 'PSB-2026-0001',
            'nama_lengkap' => 'Siswa Pendek',
            'nik' => '3201019901190011',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(6)->subMonths(2)->toDateString(),
            'jenis_kelamin' => 'Laki-laki',
            'tinggi_badan' => 104.0,
            'agama' => 'Islam',
            'alamat' => 'Jl. Uji Stunting',
            'nama_ayah' => 'Ayah Uji',
            'nama_ibu' => 'Ibu Uji',
            'status' => 'menunggu',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->actingAs(User::first())->get(route('admin.detail', $pendaftaran->id));

        $response->assertOk();
        $response->assertSee('104 cm');
        $response->assertSee('Pendek (Stunted)');
        $response->assertSee('-2.43');
    }

    public function test_registration_form_does_not_show_removed_fields(): void
    {
        $response = $this->get('/pendaftaran');

        $response->assertOk();
        $response->assertSee('Alamat Lengkap');
        $response->assertDontSee('name="nisn"', false);
        $response->assertDontSee('name="kelurahan"', false);
        $response->assertDontSee('type="file"', false);
    }

    #[DataProvider('invalidRegistrationInputs')]
    public function test_registration_rejects_invalid_input(string $field, string $value, string $message): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

        $response = $this->post('/pendaftaran', $this->validRegistrationData([$field => $value]));

        $response->assertSessionHasErrors([$field => $message]);
        $this->assertDatabaseCount('pendaftaran', 0);
    }

    public static function invalidRegistrationInputs(): array
    {
        return [
            'nama lengkap berisi angka' => ['nama_lengkap', 'Budi 123', 'Nama lengkap hanya boleh berisi huruf.'],
            'nik kurang dari 16 digit' => ['nik', '320101990119000', 'NIK harus 16 digit angka.'],
            'nik lebih dari 16 digit' => ['nik', '32010199011900091', 'NIK harus 16 digit angka.'],
            'nik berisi huruf' => ['nik', '32010199011900AB', 'NIK harus 16 digit angka.'],
            'tempat lahir berisi angka' => ['tempat_lahir', 'Jakarta 2', 'Tempat lahir hanya boleh berisi huruf.'],
            'umur di bawah 5 tahun' => ['tanggal_lahir', '2021-10-28', 'Umur calon siswa minimal 5 tahun pada tanggal pendaftaran.'],
            'umur sudah 12 tahun' => ['tanggal_lahir', '2014-10-27', 'Umur calon siswa maksimal 11 tahun pada tanggal pendaftaran.'],
            'nama ayah berisi angka' => ['nama_ayah', 'Budi 2', 'Nama ayah hanya boleh berisi huruf.'],
            'pekerjaan ayah berisi angka' => ['pekerjaan_ayah', 'Karyawan 1', 'Pekerjaan ayah hanya boleh berisi huruf.'],
            'nama ibu berisi angka' => ['nama_ibu', 'Siti 2', 'Nama ibu hanya boleh berisi huruf.'],
            'pekerjaan ibu berisi angka' => ['pekerjaan_ibu', 'Guru 2', 'Pekerjaan ibu hanya boleh berisi huruf.'],
            'no hp ayah berisi huruf' => ['no_hp_ayah', '0812abc', 'No. HP ayah hanya boleh berisi angka.'],
            'no hp ayah lebih dari 12 digit' => ['no_hp_ayah', '0812345678901', 'No. HP ayah maksimal 12 digit.'],
            'no hp ibu berisi huruf' => ['no_hp_ibu', '0812abc', 'No. HP ibu hanya boleh berisi angka.'],
            'no hp ibu lebih dari 12 digit' => ['no_hp_ibu', '0812345678901', 'No. HP ibu maksimal 12 digit.'],
        ];
    }

    #[DataProvider('validBoundaryInputs')]
    public function test_registration_accepts_boundary_input(string $field, string $value): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);

        $response = $this->post('/pendaftaran', $this->validRegistrationData([$field => $value]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pendaftaran', ['nik' => '3201019901190009']);
    }

    public static function validBoundaryInputs(): array
    {
        return [
            'tepat 5 tahun' => ['tanggal_lahir', '2021-10-27'],
            'sehari sebelum 12 tahun' => ['tanggal_lahir', '2014-10-28'],
            'no hp tepat 12 digit' => ['no_hp_ayah', '081234567890'],
            'nama dengan titik dan apostrof' => ['nama_lengkap', "M. Rizky Nur'aini"],
        ];
    }

    public function test_bukti_pendaftaran_can_be_downloaded_as_pdf(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);
        $this->post('/pendaftaran', $this->validRegistrationData());
        $pendaftaran = Pendaftaran::where('nik', '3201019901190009')->firstOrFail();

        $response = $this->get(route('pendaftaran.cetak', $pendaftaran->no_pendaftaran));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertDownload("Bukti-Pendaftaran-{$pendaftaran->no_pendaftaran}.pdf");
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_bukti_pendaftaran_adds_referral_page_only_for_stunting_child(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);
        $this->post('/pendaftaran', $this->validRegistrationData(['nik' => '3201019901190001', 'tinggi_badan' => 104]));
        $this->post('/pendaftaran', $this->validRegistrationData(['nik' => '3201019901190002', 'tinggi_badan' => 122]));
        $anakStunting = Pendaftaran::where('nik', '3201019901190001')->firstOrFail();
        $anakNormal = Pendaftaran::where('nik', '3201019901190002')->firstOrFail();

        $pdfStunting = $this->get(route('pendaftaran.cetak', $anakStunting->no_pendaftaran))->getContent();
        $pdfNormal = $this->get(route('pendaftaran.cetak', $anakNormal->no_pendaftaran))->getContent();

        $this->assertSame(2, preg_match_all('#/Type\s*/Page(?!s)#', $pdfStunting));
        $this->assertSame(1, preg_match_all('#/Type\s*/Page(?!s)#', $pdfNormal));
    }

    public function test_bukti_pendaftaran_template_shows_referral_letter_for_stunting_child(): void
    {
        $this->travelTo(self::TANGGAL_PENDAFTARAN);
        $this->post('/pendaftaran', $this->validRegistrationData(['tinggi_badan' => 104]));
        $pendaftaran = Pendaftaran::where('nik', '3201019901190009')->firstOrFail();

        $view = $this->view('pendaftaran.cetak', [
            'pendaftaran' => $pendaftaran,
            'hasilStunting' => $pendaftaran->cekStunting($pendaftaran->tinggi_badan),
        ]);

        $view->assertSee('Pemberitahuan untuk Orang Tua / Wali');
        $view->assertSee('Rujukan Pemeriksaan Stunting');
        $view->assertSee('Kepala Puskesmas');
        $view->assertSee('-3,35 SD');
    }

    public function test_bukti_pendaftaran_returns_not_found_for_unknown_number(): void
    {
        $this->get(route('pendaftaran.cetak', 'PSB-2026-10-9999'))->assertNotFound();
    }

    private function validRegistrationData(array $overrides = []): array
    {
        return array_merge([
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nik' => '3201019901190009',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2019-03-10',
            'jenis_kelamin' => 'Laki-laki',
            'tinggi_badan' => 118.5,
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 10, RT 01/RW 02, Kel. Sukajaya, Kota Nusantara',
            'nama_ayah' => 'Budi Santoso',
            'pekerjaan_ayah' => 'Wiraswasta',
            'no_hp_ayah' => '081234567890',
            'nama_ibu' => 'Siti Nurhaliza',
            'pekerjaan_ibu' => 'Guru',
            'no_hp_ibu' => '081298765432',
        ], $overrides);
    }
}
