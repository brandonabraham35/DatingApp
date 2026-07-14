<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'seeker@test.com'],
            [
                'role' => 'seeker',
                'username' => 'test-seeker',
                'password' => Hash::make('password'),
                'location' => 'Kampala',
                'is_verified' => true,
                'is_admin' => false,
            ],
        );

        User::updateOrCreate(
            ['email' => 'provider@test.com'],
            [
                'role' => 'provider',
                'username' => 'test-provider',
                'password' => Hash::make('password'),
                'location' => 'Kampala',
                'is_verified' => true,
                'is_admin' => false,
            ],
        );
    }
}
