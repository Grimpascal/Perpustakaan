<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,      // HARUS sebelum BookSeeder
            PenerbitSeeder::class,      // HARUS sebelum BookSeeder
            BookSeeder::class,
            PeminjamanSeeder::class,    // Comment dulu
            FavoriteSeeder::class,      // Comment dulu
        ]);
    }
}