<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ============================
    // DASHBOARD USER
    // ============================
    public function dashboard(Request $request)
    {
         $search = $request->input('search');
        $books = Book::query();

        if ($search) {
            $books->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
        }

        $books = $books->get();

        return view('user.dashboard', compact('books'));
    }

    // ============================
    // LIST BUKU
    // ============================
    public function buku()
    {
        $books = Book::all();

        return view('user.buku', [
            'title' => 'Daftar Buku',
            'books' => $books
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

        // Hitung denda jika telat
        $jatuh_tempo = Carbon::parse($peminjaman->tanggal_kembali);
        $hari_telat = now()->diffInDays($jatuh_tempo, false) * -1;

        if ($hari_telat > 0) {
            $peminjaman->denda = $hari_telat * 10000; // 10k per hari
        }

        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();

        // Tambah stok buku kembali
        $peminjaman->book->increment('stok');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
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

    public function tambahFavorite($id)
    {
        $existing = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Buku sudah ada di wishlist!');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'book_id' => $id
        ]);

        return redirect()->back()->with('success', 'Ditambahkan ke wishlist!');
    }

    public function hapusFavorite($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Berhasil dihapus dari wishlist!');
    }

    // ============================
    // PROFIL USER
    // ============================
    public function profil()
    {
        return view('user.profil', [
            'title' => 'Profil Saya',
            'user' => Auth::user()
        ]);
    }

    // ============================
    // LOGOUT
    // ============================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout');
    }
}
