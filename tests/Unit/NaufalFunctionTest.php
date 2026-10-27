<?php

namespace Tests\Unit;

use App\Helpers\StuntingResult;
use App\Models\Pendaftaran;
use InvalidArgumentException;
use Tests\TestCase;

/**
 * NaufalFunctionTest - Pengujian Unit fungsi naufalfunction
 * untuk mengukur status stunting anak berdasarkan tinggi badan dan standar WHO 2007.
 *
 * Kompetensi:
 * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
 * - J.620100.036.02 - Melaksanakan Pengujian Kode Program Secara Statis
 * - J.620100.025.02 - Melakukan Debugging
 */
class NaufalFunctionTest extends TestCase
{
    /**
     * Menguji perhitungan rumus Z-score sesuai contoh pada Gambar 1:
     * Tinggi (X) = 80 cm, Median (mu) = 86 cm, SD (sigma) = 3 cm.
     * Z = (80 - 86) / 3 = -2.00 -> Status: Stunting (Pendek).
     */
    public function test_formula_matches_example_from_image_1(): void
    {
        $hasil = naufalfunction(80, 86, 3);

        $this->assertInstanceOf(StuntingResult::class, $hasil);
        $this->assertEquals(-2.00, $hasil->z_score);
        $this->assertTrue($hasil->is_stunting);
        $this->assertEquals('Stunting', $hasil->status);
        $this->assertEquals('Pendek (Stunted)', $hasil->kategori);
        $this->assertEquals(80.0, $hasil->tinggi_badan);
        $this->assertEquals(86.0, $hasil->median);
        $this->assertEquals(3.0, $hasil->simpangan_baku);
    }

    /**
     * Menguji data referensi WHO 2007 dari tabel pada Gambar 2:
     * - 5 tahun 1 bulan | Laki-laki: Median 110.3, SD 4.59
     * - 5 tahun 1 bulan | Perempuan: Median 109.6, SD 4.77
     * - 6 tahun         | Laki-laki: Median 116.0, SD 4.93
     * - 6 tahun         | Perempuan: Median 115.1, SD 5.12
     * - 7 tahun         | Laki-laki: Median 121.7, SD 5.29
     * - 7 tahun         | Perempuan: Median 120.8, SD 5.47
     */
    public function test_who_2007_reference_table_from_image_2(): void
    {
        // 1. Usia 5 tahun 1 bulan - Laki-laki
        $anak1 = naufalfunction(110.3, '5 tahun 1 bulan', 'Laki-laki');
        $this->assertEquals(110.3, $anak1->median);
        $this->assertEquals(4.59, $anak1->simpangan_baku);
        $this->assertEquals(0.00, $anak1->z_score);
        $this->assertFalse($anak1->is_stunting);
        $this->assertEquals('Tidak Stunting', $anak1->status);

        // 2. Usia 5 tahun 1 bulan - Perempuan
        $anak2 = naufalfunction(109.6, '5 tahun 1 bulan', 'Perempuan');
        $this->assertEquals(109.6, $anak2->median);
        $this->assertEquals(4.77, $anak2->simpangan_baku);
        $this->assertEquals(0.00, $anak2->z_score);

        // 3. Usia 6 tahun - Laki-laki
        $anak3 = naufalfunction(116.0, '6 tahun', 'Laki-laki');
        $this->assertEquals(116.0, $anak3->median);
        $this->assertEquals(4.93, $anak3->simpangan_baku);
        $this->assertEquals(0.00, $anak3->z_score);

        // 4. Usia 6 tahun - Perempuan
        $anak4 = naufalfunction(115.1, '6 tahun', 'Perempuan');
        $this->assertEquals(115.1, $anak4->median);
        $this->assertEquals(5.12, $anak4->simpangan_baku);
        $this->assertEquals(0.00, $anak4->z_score);

        // 5. Usia 7 tahun - Laki-laki
        $anak5 = naufalfunction(121.7, '7 tahun', 'Laki-laki');
        $this->assertEquals(121.7, $anak5->median);
        $this->assertEquals(5.29, $anak5->simpangan_baku);
        $this->assertEquals(0.00, $anak5->z_score);

        // 6. Usia 7 tahun - Perempuan
        $anak6 = naufalfunction(120.8, '7 tahun', 'Perempuan');
        $this->assertEquals(120.8, $anak6->median);
        $this->assertEquals(5.47, $anak6->simpangan_baku);
        $this->assertEquals(0.00, $anak6->z_score);
    }

    /**
     * Menguji deteksi anak stunting berdasarkan data umur WHO.
     */
    public function test_stunting_detection_with_age_and_gender(): void
    {
        // Anak laki-laki 6 tahun, tinggi 105 cm (Median 116.0, SD 4.93)
        // Z = (105 - 116.0) / 4.93 = -2.23 (Di bawah -2 SD -> Stunting)
        $anak = naufalfunction(105, '6 tahun', 'Laki-laki');

        $this->assertTrue($anak->is_stunting);
        $this->assertEquals('Stunting', $anak->status);
        $this->assertEquals(-2.23, $anak->z_score);
        $this->assertEquals('Pendek (Stunted)', $anak->kategori);
    }

    /**
     * Menguji kategori stunting berat (Severely Stunted) jika Z < -3.0.
     */
    public function test_severely_stunted_category(): void
    {
        // Anak perempuan 6 tahun, tinggi 95 cm (Median 115.1, SD 5.12)
        // Z = (95 - 115.1) / 5.12 = -3.93 (< -3 SD -> Sangat Pendek)
        $anak = naufalfunction(95, '6 tahun', 'Perempuan');

        $this->assertTrue($anak->is_stunting);
        $this->assertEquals('Stunting', $anak->status);
        $this->assertEquals('Sangat Pendek (Severely Stunted)', $anak->kategori);
    }

    /**
     * Menguji kategori anak tinggi jika Z > 2.0.
     */
    public function test_tall_category(): void
    {
        // Anak laki-laki 7 tahun, tinggi 135 cm (Median 121.7, SD 5.29)
        // Z = (135 - 121.7) / 5.29 = +2.51 (> +2 SD -> Tinggi)
        $anak = naufalfunction(135, '7 tahun', 'Laki-laki');

        $this->assertFalse($anak->is_stunting);
        $this->assertEquals('Tidak Stunting', $anak->status);
        $this->assertEquals('Tinggi', $anak->kategori);
    }

    /**
     * Menguji kemampuan objek hasil untuk di-cast sebagai string.
     */
    public function test_result_can_be_used_as_string(): void
    {
        $hasil = naufalfunction(80, 86, 3);

        $this->assertEquals('Stunting', (string) $hasil);
        $this->assertTrue($hasil == 'Stunting');
        $this->assertEquals('Hasil: Stunting', "Hasil: {$hasil}");
    }

    /**
     * Menguji akses array dan serialisasi JSON.
     */
    public function test_array_access_and_json_serialization(): void
    {
        $hasil = naufalfunction(80, 86, 3);

        $this->assertEquals('Stunting', $hasil['status']);
        $this->assertEquals(-2.00, $hasil['z_score']);
        $this->assertTrue($hasil['is_stunting']);
        $this->assertEquals(80.0, $hasil['tinggi_badan']);

        $json = json_encode($hasil);
        $this->assertJson($json);
        $this->assertStringContainsString('"is_stunting":true', $json);
        $this->assertStringContainsString('"z_score":-2', $json);
    }

    /**
     * Menguji opsi return murni array asosiatif.
     */
    public function test_return_as_native_array(): void
    {
        $hasil = naufalfunction(80, 86, 3, true);

        $this->assertIsArray($hasil);
        $this->assertEquals('Stunting', $hasil['status']);
        $this->assertEquals(-2.00, $hasil['z_score']);
    }

    /**
     * Menguji pemanggilan naufalfunction melalui model Pendaftaran.
     */
    public function test_calling_via_pendaftaran_model(): void
    {
        $pendaftaran = new Pendaftaran([
            'nama_lengkap' => 'Ahmad Fajar',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2020-04-15',
        ]);

        $staticCall = Pendaftaran::naufalfunction(80, 86, 3);
        $this->assertEquals('Stunting', $staticCall->status);

        $methodCall = $pendaftaran->cekStunting(116.0);
        $this->assertInstanceOf(StuntingResult::class, $methodCall);
    }

    /**
     * Menguji simpangan baku <= 0 melempar InvalidArgumentException.
     */
    public function test_zero_or_negative_standard_deviation_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        naufalfunction(100, 110, 0);
    }
}
