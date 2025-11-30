<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan DB facade untuk hindari model issues
        $kategories = [
            [
                'nama_kategori' => 'Programming', 
                'slug' => 'programming', 
                'deskripsi' => 'Buku pemrograman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Self-Development', 
                'slug' => 'self-development', 
                'deskripsi' => 'Buku pengembangan diri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Fantasy', 
                'slug' => 'fantasy', 
                'deskripsi' => 'Buku fantasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Education', 
                'slug' => 'education', 
                'deskripsi' => 'Buku pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategories')->insert($kategories);
        
        $this->command->info('Kategori seeder berhasil! ' . count($kategories) . ' kategori ditambahkan.');
    }
}