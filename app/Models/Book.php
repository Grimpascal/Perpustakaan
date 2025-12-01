<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit_id',
        'kategori_id',
        'tahun',
        'isbn',
        'deskripsi',
        'bahasa',
        'stok',
        'is_available',
        'total_dipinjam'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'book_id');
    }
}