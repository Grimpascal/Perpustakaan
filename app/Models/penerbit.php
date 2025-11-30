<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penerbit',
        'alamat',
        'telepon', 
        'email'
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'penerbit_id');
    }
}