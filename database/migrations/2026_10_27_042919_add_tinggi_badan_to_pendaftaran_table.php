<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk menambahkan kolom tinggi badan calon siswa,
 * dipakai untuk pemeriksaan stunting (naufalfunction).
 *
 * Kompetensi:
 * - J.620100.020.02 - Menggunakan SQL (DDL untuk mengubah tabel)
 * - J.620100.021.02 - Menerapkan Akses Basis Data (migrasi database)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom tinggi_badan (cm) setelah jenis_kelamin.
     */
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->decimal('tinggi_badan', 5, 1)->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom tinggi_badan.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('tinggi_badan');
        });
    }
};
