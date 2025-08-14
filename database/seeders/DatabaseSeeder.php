<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'is_superadmin' => true
        ]);

        Department::insert(
            [
                [
                    'user_id' => User::first()->id,
                    'name' => 'Sekretariat',
                    'description' => 'Sekretariat',
                    'created_at' => now()
                ],
                [
                    'user_id' => User::first()->id,
                    'name' => 'Bidang Komunikasi dan Informasi Publik',
                    'description' => 'Bidang Komunikasi dan Informasi Publik',
                    'created_at' => now()
                ],
                [
                    'user_id' => User::first()->id,
                    'name' => 'Bidang Aplikasi Informatika',
                    'description' => 'Bidang Aplikasi Informatika',
                    'created_at' => now()
                ],
                [
                    'user_id' => User::first()->id,
                    'name' => 'Bidang Persandian dan Statistik',
                    'description' => 'Bidang Persandian dan Statistik',
                    'created_at' => now()
                ],
            ]
        );
    }
}
