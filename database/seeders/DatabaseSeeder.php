<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Membuat akun admin default dan data contoh pendaftar.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PendaftaranSeeder::class,
        ]);
    }
}
