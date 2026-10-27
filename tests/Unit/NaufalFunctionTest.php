<?php

namespace Tests\Unit;

use App\Helpers\StuntingResult;
use App\Models\Pendaftaran;
use InvalidArgumentException;
use Tests\TestCase;

class NaufalFunctionTest extends TestCase
{
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

    public function test_who_2007_reference_table_from_image_2(): void
    {
        $anak1 = naufalfunction(110.3, '5 tahun 1 bulan', 'Laki-laki');
        $this->assertEquals(110.3, $anak1->median);
        $this->assertEquals(4.59, $anak1->simpangan_baku);
        $this->assertEquals(0.00, $anak1->z_score);
        $this->assertFalse($anak1->is_stunting);
        $this->assertEquals('Tidak Stunting', $anak1->status);

        $anak2 = naufalfunction(109.6, '5 tahun 1 bulan', 'Perempuan');
        $this->assertEquals(109.6, $anak2->median);
        $this->assertEquals(4.77, $anak2->simpangan_baku);
        $this->assertEquals(0.00, $anak2->z_score);

        $anak3 = naufalfunction(116.0, '6 tahun', 'Laki-laki');
        $this->assertEquals(116.0, $anak3->median);
        $this->assertEquals(4.93, $anak3->simpangan_baku);
        $this->assertEquals(0.00, $anak3->z_score);

        $anak4 = naufalfunction(115.1, '6 tahun', 'Perempuan');
        $this->assertEquals(115.1, $anak4->median);
        $this->assertEquals(5.12, $anak4->simpangan_baku);
        $this->assertEquals(0.00, $anak4->z_score);

        $anak5 = naufalfunction(121.7, '7 tahun', 'Laki-laki');
        $this->assertEquals(121.7, $anak5->median);
        $this->assertEquals(5.29, $anak5->simpangan_baku);
        $this->assertEquals(0.00, $anak5->z_score);

        $anak6 = naufalfunction(120.8, '7 tahun', 'Perempuan');
        $this->assertEquals(120.8, $anak6->median);
        $this->assertEquals(5.47, $anak6->simpangan_baku);
        $this->assertEquals(0.00, $anak6->z_score);
    }

    public function test_stunting_detection_with_age_and_gender(): void
    {
        $anak = naufalfunction(105, '6 tahun', 'Laki-laki');

        $this->assertTrue($anak->is_stunting);
        $this->assertEquals('Stunting', $anak->status);
        $this->assertEquals(-2.23, $anak->z_score);
        $this->assertEquals('Pendek (Stunted)', $anak->kategori);
    }

    public function test_severely_stunted_category(): void
    {
        $anak = naufalfunction(95, '6 tahun', 'Perempuan');

        $this->assertTrue($anak->is_stunting);
        $this->assertEquals('Stunting', $anak->status);
        $this->assertEquals('Sangat Pendek (Severely Stunted)', $anak->kategori);
    }

    public function test_tall_category(): void
    {
        $anak = naufalfunction(135, '7 tahun', 'Laki-laki');

        $this->assertFalse($anak->is_stunting);
        $this->assertEquals('Tidak Stunting', $anak->status);
        $this->assertEquals('Tinggi', $anak->kategori);
    }

    public function test_result_can_be_used_as_string(): void
    {
        $hasil = naufalfunction(80, 86, 3);

        $this->assertEquals('Stunting', (string) $hasil);
        $this->assertTrue($hasil == 'Stunting');
        $this->assertEquals('Hasil: Stunting', "Hasil: {$hasil}");
    }

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

    public function test_return_as_native_array(): void
    {
        $hasil = naufalfunction(80, 86, 3, true);

        $this->assertIsArray($hasil);
        $this->assertEquals('Stunting', $hasil['status']);
        $this->assertEquals(-2.00, $hasil['z_score']);
    }

    public function test_calling_via_pendaftaran_model(): void
    {
        $pendaftaran = new Pendaftaran([
            'nama_lengkap' => 'Ahmad Fajar',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2020-04-15',
        ]);

        $methodCall = $pendaftaran->cekStunting(116.0);
        $this->assertInstanceOf(StuntingResult::class, $methodCall);
    }

    public function test_age_input_in_months_or_years_maps_to_correct_who_category(): void
    {
        $this->assertEquals('6 tahun', naufalfunction(116, '72 bulan', 'Laki-laki')->umur);
        $this->assertEquals('5 tahun 1 bulan', naufalfunction(110, '5 tahun', 'Laki-laki')->umur);
        $this->assertEquals('5 tahun 1 bulan', naufalfunction(110, 61, 'Laki-laki')->umur);
        $this->assertEquals('7 tahun', naufalfunction(121, 7, 'Laki-laki')->umur);
    }

    public function test_zero_or_negative_standard_deviation_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        naufalfunction(100, 110, 0);
    }
}
