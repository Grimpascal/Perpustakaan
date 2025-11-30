<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penerbit;

class PenerbitSeeder extends Seeder
{
    public function run(): void
    {
        $penerbits = [
            ['nama_penerbit' => 'Prentice Hall', 'alamat' => 'New Jersey, USA'],
            ['nama_penerbit' => 'Addison-Wesley', 'alamat' => 'Boston, USA'],
            ['nama_penerbit' => 'Penguin Random House', 'alamat' => 'New York, USA'],
            ['nama_penerbit' => 'Gramedia Pustaka Utama', 'alamat' => 'Jakarta, Indonesia'],
        ];

        foreach ($penerbits as $penerbit) {
            Penerbit::create($penerbit);
        }
        
        $this->command->info('Penerbit seeder berhasil!');
    }
}