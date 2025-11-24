<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@library.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User Biasa
        User::create([
            'nama_lengkap' => 'User Satu',
            'email' => 'user1@library.com',
            'username' => 'user1',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::factory()
        ->count(100)
        ->create();
    }
}
