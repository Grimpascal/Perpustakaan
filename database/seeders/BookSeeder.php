<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'judul' => 'Clean Code',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun' => 2008,
                'kategori' => 'Programming',
                'stok' => 5,
                'cover' => null,
            ],
            [
                'judul' => 'The Pragmatic Programmer',
                'penulis' => 'Andrew Hunt',
                'penerbit' => 'Addison-Wesley',
                'tahun' => 1999,
                'kategori' => 'Programming',
                'stok' => 4,
                'cover' => null,
            ],
            [
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Penguin',
                'tahun' => 2018,
                'kategori' => 'Self-Development',
                'stok' => 6,
                'cover' => null,
            ],
            [
                'judul' => 'Harry Potter and the Philosopher\'s Stone',
                'penulis' => 'J.K. Rowling',
                'penerbit' => 'Bloomsbury',
                'tahun' => 1997,
                'kategori' => 'Fantasy',
                'stok' => 3,
                'cover' => null,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
