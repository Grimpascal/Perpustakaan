<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategories'; // Specify table name

    protected $fillable = [
        'nama_kategori',
        'slug', 
        'deskripsi'
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'kategori_id');
    }
}