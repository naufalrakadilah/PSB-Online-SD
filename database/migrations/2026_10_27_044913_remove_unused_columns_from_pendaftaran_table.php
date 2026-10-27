<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'nisn',
                'rt',
                'rw',
                'kelurahan',
                'kecamatan',
                'kota',
                'kode_pos',
                'foto_siswa',
                'akta_kelahiran',
                'kartu_keluarga',
                'ijazah_tk',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('nisn')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('foto_siswa')->nullable();
            $table->string('akta_kelahiran')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('ijazah_tk')->nullable();
        });
    }
};
