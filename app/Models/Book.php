<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Peminjaman;
use Favorites;

class Book extends Model
{
     protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'kategori',
        'stok',
        'cover'
    ];

     public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'book_id');
    }
}
