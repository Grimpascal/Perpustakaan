<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa user dan book
        $users = User::where('role', 'user')->limit(5)->get();
        $books = Book::where('is_available', true)->limit(10)->get();

        $statuses = ['dipinjam', 'dikembalikan', 'terlambat'];
        
        foreach ($users as $user) {
            foreach ($books->random(2) as $book) {
                $tanggalPinjam = Carbon::now()->subDays(rand(1, 30));
                $tanggalHarusKembali = $tanggalPinjam->copy()->addDays(7);
                $status = $statuses[array_rand($statuses)];
                
                $tanggalDikembalikan = null;
                if ($status === 'dikembalikan') {
                    $tanggalDikembalikan = $tanggalHarusKembali->copy()->subDays(rand(0, 2));
                } elseif ($status === 'terlambat') {
                    $tanggalDikembalikan = $tanggalHarusKembali->copy()->addDays(rand(1, 5));
                }

                $denda = 0;
                if ($status === 'terlambat' && $tanggalDikembalikan) {
                    $hariTerlambat = $tanggalDikembalikan->diffInDays($tanggalHarusKembali);
                    $denda = $hariTerlambat * 5000; // Denda Rp 5.000 per hari
                }

                Peminjaman::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_harus_kembali' => $tanggalHarusKembali,
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'status' => $status,
                    'denda' => $denda,
                    'catatan' => $status === 'terlambat' ? 'Pengembalian terlambat' : null,
                ]);

                // Update total dipinjam di book
                if ($status === 'dipinjam') {
                    $book->increment('total_dipinjam');
                }
            }
        }
    }
}