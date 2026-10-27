<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->decimal('tinggi_badan', 5, 1)->nullable()->after('jenis_kelamin');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('tinggi_badan');
        });
    }
};
