<?php

use App\Helpers\StuntingHelper;
use App\Helpers\StuntingResult;

if (! function_exists('naufalfunction')) {
    /**
     * naufalfunction - Mengukur tinggi badan anak apakah stunting atau tidak.
     *
     * Berdasarkan rumus standar WHO 2007:
     * Z = (X - mu) / sigma
     *
     * Keterangan:
     * - Z     = Nilai Z-score
     * - X     = Tinggi badan anak yang diukur (cm)
     * - mu    = Nilai median tinggi badan anak berdasarkan standar WHO (cm)
     * - sigma = Simpangan baku berdasarkan standar WHO (cm)
     *
     * Contoh pemanggilan:
     * 1. Nilai langsung (Gambar 1):
     *    naufalfunction(80, 86, 3); // Z = (80 - 86) / 3 = -2.00 (Stunting)
     *
     * 2. Berdasarkan data WHO 2007 (Gambar 2):
     *    naufalfunction(105, '5 tahun 1 bulan', 'Laki-laki');
     *    naufalfunction(112, '6 tahun', 'Perempuan');
     *    naufalfunction(118, 7, 'Laki-laki');
     *
     * 3. Pemanggilan via Model:
     *    naufalfunction(110, $pendaftaran);
     *
     * Kompetensi:
     * - J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
     * - J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek
     * - J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
     *
     * @param  mixed  $tinggi  Tinggi badan (cm)
     * @param  mixed  $param2  Median (float) atau Umur (string/int) atau Model Pendaftaran
     * @param  mixed  $param3  Simpangan baku / SD (float) atau Jenis kelamin (string)
     * @param  bool  $asArray  True jika ingin hasil murni array asosiatif
     * @return StuntingResult|array Hasil kalkulasi status stunting
     */
    function naufalfunction(
        mixed $tinggi,
        mixed $param2 = null,
        mixed $param3 = null,
        bool $asArray = false
    ): StuntingResult|array {
        return StuntingHelper::naufalfunction($tinggi, $param2, $param3, $asArray);
    }
}
