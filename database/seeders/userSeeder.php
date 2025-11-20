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
        DB::table('pengguna')->insert([ // <-- Ubah dari 'users' ke 'pengguna'
            'Nama Lengkap' => 'Administrator Testing', // <-- Nama kolom
            'Email' => 'admin@example.com', // <-- Nama kolom
            'username' => 'admin_test',
            'Password' => Hash::make('password'), // <-- Nama kolom
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10), 
        ]);

        User::factory()
        ->count(10)
        ->create();
    }
}
