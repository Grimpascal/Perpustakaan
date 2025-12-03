<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function buku()
    {
        $books = Book::all();
        $title = "Daftar Buku";

        return view('user/buku', compact('title', 'books'));
    }

    public function detail($id)
{
    $book = Book::findOrFail($id);

    return view('user.detail', [
        'title' => 'Detail Buku',
        'book' => $book
    ]);
}

    public function peminjaman()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with('book')
            ->orderBy('status', 'asc')
            ->get();

        return view('user.peminjaman', [
            'title' => 'Peminjaman Saya',
            'peminjaman' => $peminjaman
        ]);
    }



    // ============================
    // PINJAM BUKU
    // ============================
    public function pinjam(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($book->stok < 1) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        // Kurangi stok buku
        $book->decrement('stok');

        Peminjaman::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dipinjam!');
    }

    // ============================
    // PENGEMBALIAN BUKU
    // ============================
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Set tanggal pengembalian saat ini
        $tanggalKembali = Carbon::now();
        $peminjaman->tanggal_kembali = $tanggalKembali;

        // Hitung jatuh tempo (7 hari setelah tanggal pinjam)
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_pinjam)->addDays(7);

        // Hitung telat hari
        if ($tanggalKembali->greaterThan($jatuhTempo)) {
            $hariTelat = $tanggalKembali->diffInDays($jatuhTempo);
            $peminjaman->status = 'telat';
            $peminjaman->denda = $hariTelat * 10000; // 10k per hari
        } else {
            $peminjaman->status = 'selesai';
            $peminjaman->denda = 0;
        }

        $peminjaman->save();

        // Tambahkan stok buku karena telah dikembalikan
        $peminjaman->book->increment('stok');

        return redirect()->route('user.pinjam.history')
            ->with('success', 'Buku berhasil dikembalikan!');
    }


    // ============================
    // FAVORIT (WISHLIST)
    // ============================
    public function favorite()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('book')
            ->get();

        return view('user.favorite', [
            'title' => 'Wishlist Buku',
            'favorites' => $favorites
        ]);
    }

    public function addFavorite($id)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $id
        ]);

        return redirect()->back()->with('success', 'Buku ditambahkan ke favorit!');
    }

   public function removeFavorite($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Buku dihapus dari favorit.');
    }

    public function profil()
    {
        return view('user.profil', [
            'title' => 'Profil Saya',
            'user' => Auth::user()
        ]);
    }

    public function history()
    {
        $peminjaman = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', [
            'title' => 'Riwayat Peminjaman',
            'peminjaman' => $peminjaman
        ]);
    }

}
