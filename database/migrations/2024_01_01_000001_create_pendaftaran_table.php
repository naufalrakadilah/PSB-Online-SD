<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel pendaftaran siswa baru.
 *
 * Kompetensi:
 * - J.620100.020.02 - Menggunakan SQL (DDL untuk membuat tabel)
 * - J.620100.021.02 - Menerapkan Akses Basis Data (migrasi database)
 * - J.620100.024.02 - Melakukan Migrasi Ke Teknologi Baru (Laravel Migration System)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel pendaftaran dengan kolom-kolom yang diperlukan.
     */
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Nomor pendaftaran unik (auto-generated)
            $table->string('no_pendaftaran')->unique();

            // Data Calon Siswa
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('nik', 16);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->text('alamat');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos', 10)->nullable();

            // Data Asal Sekolah (TK)
            $table->string('asal_sekolah')->nullable();

            // Data Orang Tua / Wali
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('no_hp_ayah', 20)->nullable();
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_hp_ibu', 20)->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('no_hp_wali', 20)->nullable();

            // Dokumen Upload (path file)
            $table->string('foto_siswa')->nullable();
            $table->string('akta_kelahiran')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('ijazah_tk')->nullable();

            // Status Pendaftaran
            $table->enum('status', ['menunggu', 'diverifikasi', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();

            // Tahun Ajaran
            $table->string('tahun_ajaran')->default('2026/2027');

            // Timestamps
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index('status');
            $table->index('tahun_ajaran');
            $table->index('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus tabel pendaftaran.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
