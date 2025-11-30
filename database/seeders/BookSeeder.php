<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\kategori;
use App\Models\Penerbit;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Skip jika sudah ada data books
        if (Book::count() > 0) {
            $this->command->info('Books already exist. Skipping seeder.');
            return;
        }

        // Validasi: Pastikan kategori dan penerbit ada
        if (kategori::count() === 0) {
            $this->command->error('ERROR: Tabel kategories kosong! Jalankan KategoriSeeder dulu.');
            return;
        }

        if (Penerbit::count() === 0) {
            $this->command->error('ERROR: Tabel penerbits kosong! Jalankan PenerbitSeeder dulu.');
            return;
        }

        // Ambil ID yang valid
        $kategoriProgramming = kategori::where('nama_kategori', 'Programming')->first();
        $kategoriSelfDev = kategori::where('nama_kategori', 'Self-Development')->first();
        
        $penerbit1 = Penerbit::where('nama_penerbit', 'Prentice Hall')->first();
        $penerbit2 = Penerbit::where('nama_penerbit', 'Addison-Wesley')->first();
        $penerbit3 = Penerbit::where('nama_penerbit', 'Penguin Random House')->first();

        // Fallback jika tidak ditemukan
        if (!$kategoriProgramming) $kategoriProgramming = Kategory::first();
        if (!$kategoriSelfDev) $kategoriSelfDev = Kategory::skip(1)->first() ?? Kategory::first();
        if (!$penerbit1) $penerbit1 = Penerbit::first();
        if (!$penerbit2) $penerbit2 = Penerbit::skip(1)->first() ?? Penerbit::first();
        if (!$penerbit3) $penerbit3 = Penerbit::skip(2)->first() ?? Penerbit::first();

        $books = [
            [
                'judul' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'penulis' => 'Robert C. Martin',
                'penerbit_id' => $penerbit1->id, // Gunakan ID yang valid
                'kategori_id' => $kategoriProgramming->id,
                'tahun' => 2008,
                'isbn' => '9780132350884',
                'deskripsi' => 'Buku wajib untuk setiap programmer yang ingin menulis kode yang bersih, mudah dipahami, dan mudah dirawat.',
                'bahasa' => 'English',
                'stok' => 5,
                'is_available' => true,
                'total_dipinjam' => 12,
            ],
            [
                'judul' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'penulis' => 'Andrew Hunt, David Thomas',
                'penerbit_id' => $penerbit2->id,
                'kategori_id' => $kategoriProgramming->id,
                'tahun' => 1999,
                'isbn' => '9780201616224',
                'deskripsi' => 'Panduan praktis untuk menjadi programmer yang lebih efektif dan produktif.',
                'bahasa' => 'English',
                'stok' => 4,
                'is_available' => true,
                'total_dipinjam' => 8,
            ],
            [
                'judul' => 'Atomic Habits: An Easy & Proven Way to Build Good Habits & Break Bad Ones',
                'penulis' => 'James Clear',
                'penerbit_id' => $penerbit3->id,
                'kategori_id' => $kategoriSelfDev->id,
                'tahun' => 2018,
                'isbn' => '9780735211292',
                'deskripsi' => 'Framework terbukti untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'bahasa' => 'English',
                'stok' => 6,
                'is_available' => true,
                'total_dipinjam' => 15,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
        
        $this->command->info('Book seeder completed successfully! ' . count($books) . ' books added.');
    }
}