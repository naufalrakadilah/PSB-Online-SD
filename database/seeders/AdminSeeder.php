<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@psbsd.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        if (! $admin->isVerified()) {
            $admin->forceFill(['verified_at' => now()])->save();
        }

        $this->command->info('Admin user created: admin@psbsd.test / admin123');
    }
}
