<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Book;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->limit(10)->get();
        $books = Book::all();

        foreach ($users as $user) {
            $favoriteBooks = $books->random(3);
            
            foreach ($favoriteBooks as $book) {
                Favorite::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ]);
            }
        }
    }
}