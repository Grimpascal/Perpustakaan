<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class denda extends Model
{
    protected $fillable = [
    'peminjaman_id',
    'jumlah_denda',
    'status',
    'tanggal_bayar',
    'keterangan'
];
    
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
