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
                'cover' => 'books/clean_code.jpg',
            ],
            [
                'judul' => 'The Pragmatic Programmer',
                'penulis' => 'Andrew Hunt',
                'penerbit' => 'Addison-Wesley',
                'tahun' => 1999,
                'kategori' => 'Programming',
                'stok' => 4,
                'cover' => 'books/pragmatic_programmer.jpg',
            ],
            [
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Penguin',
                'tahun' => 2018,
                'kategori' => 'Self-Development',
                'stok' => 6,
                'cover' => 'books/atomic_habits.jpg',
            ],
            [
                'judul' => 'Harry Potter and the Philosopher\'s Stone',
                'penulis' => 'J.K. Rowling',
                'penerbit' => 'Bloomsbury',
                'tahun' => 1997,
                'kategori' => 'Fantasy',
                'stok' => 3,
                'cover' => 'books/harry_potter.jpg',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
