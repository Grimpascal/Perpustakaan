<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    
    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_dikembalikan',
        'tanggal_harus_kembali',
        'status',
        'denda',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function getDendaAttribute()
    {
        if (!$this->tanggal_dikembalikan) {
            return 0;
        }

        $tanggalDeadline = \Carbon\Carbon::parse($this->tanggal_pinjam)->addDays(7);
        $tanggalKembali = \Carbon\Carbon::parse($this->tanggal_dikembalikan);

        if ($tanggalKembali->isAfter($tanggalDeadline)) {
            $hariTelat = $tanggalKembali->diffInDays($tanggalDeadline);
            return $hariTelat * 10000; // denda per hari
        }

        return 0;
    }

}
