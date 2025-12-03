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
    // ============================
    // DASHBOARD USER
    // ============================
    public function dashboard(Request $request) {

    $search = $request->get('search');
    $status = $request->get('status');
    
    $books = Book::with('kategori')
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        })
        ->when($status == 'available', function($query) {
            $query->where('is_available', true)->where('stok', '>', 0);
        })
        ->where('is_available', true)
        ->orderBy('created_at', 'desc')
        ->paginate(12);
        
    $totalBooks = Book::where('is_available', true)->count();
    $availableBooks = Book::where('is_available', true)->where('stok', '>', 0)->count();
    
    return view('user.dashboardUser', [
        'title' => 'Dashboard - Library',
        'books' => $books,
        'totalBooks' => $totalBooks,
        'availableBooks' => $availableBooks
    ]);
}

    // ============================
    // DAFTAR BUKU SEMUA (FULL LIST)
    // ============================
    public function buku(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        
        $books = Book::with('kategori')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when($kategori, function($query) use ($kategori) {
                $query->where('kategori_id', $kategori);
            })
            ->where('is_available', true)
            ->orderBy('judul', 'asc')
            ->paginate(20);

        return view('user.buku', [
            'title' => 'Daftar Buku',
            'books' => $books
        ]);
    }

    // ============================
    // DETAIL BUKU
    // ============================
    public function showBook($id)
    {
        $book = Book::with(['kategori', 'penerbit'])
            ->findOrFail($id);

        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->exists();

        return view('user.book-detail', [
            'title'      => $book->judul,
            'book'       => $book,
            'isFavorite' => $isFavorite
        ]);
    }

    public function detail($id)
{
    $book = Book::with(['kategori', 'penerbit'])
            ->findOrFail($id);

        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->exists();

        return view('user.detail', [
            'title'      => $book->judul,
            'book'       => $book,
            'isFavorite' => $isFavorite
        ]);
}

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
            
        // Hitung sisa hari untuk setiap peminjaman
        $peminjamans->each(function($peminjaman) {
            $peminjaman->sisa_hari = Carbon::now()->diffInDays($peminjaman->tanggal_kembali, false);
        });
            
        return view('user.peminjaman', [
            'title' => 'Peminjaman Saya',
            'peminjamans' => $peminjamans
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

        // Cek apakah user sudah meminjam buku yang sama
        $existingPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->first();
            
        if ($existingPeminjaman) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini!');
        }

        // Kurangi stok buku
        $book->stok -= 1;
        if ($book->stok <= 0) {
            $book->is_available = false;
        }
        $book->save();

        // Create peminjaman
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_dikembalikan' => Carbon::now()->addDays(7),
            'tanggal_harus_kembali' => Carbon::now()->addDays(7),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        return redirect()->route('user.pinjam.history')
            ->with('success', 'Buku "' . $book->judul . '" berhasil dipinjam! Harap dikembalikan sebelum ' . $peminjaman->tanggal_harus_kembali->format('d M Y'));
    }


    // ============================
    // PENGEMBALIAN BUKU
    // ============================
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->with('book')
            ->firstOrFail();

        // Set tanggal pengembalian saat ini
        $tanggalKembali = Carbon::now();
        $peminjaman->tanggal_dikembalikan = $tanggalKembali;

        // Hitung jatuh tempo (7 hari setelah tanggal pinjam)
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_pinjam)->addDays(7);

        // Hitung telat hari
        if ($tanggalKembali->greaterThan($jatuhTempo)) {
            $hariTelat = $tanggalKembali->diffInDays($jatuhTempo);
            $peminjaman->status = 'terlambat';
            $peminjaman->denda = $hariTelat * 10000; // 10k per hari
        } else {
            $peminjaman->status = 'dikembalikan';
            $peminjaman->denda = 0;
        }

        $peminjaman->save();

        // Tambahkan stok buku karena telah dikembalikan
        $peminjaman->book->increment('stok');

        $book = $peminjaman->book;
        if ($book->stok > 0) {
            $book->is_available = true;
        }
        $book->save();

        return redirect()->route('user.pinjam.history')
            ->with('success', 'Buku berhasil dikembalikan!');
    }


    // ============================
    // FAVORIT (WISHLIST)
    // ============================
    public function favorite()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('book.kategori')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('user.favorite', [
            'title' => 'Buku Favorit',
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
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->firstOrFail();
            
        $bookTitle = $favorite->book->judul;
        $favorite->delete();

        return redirect()->back()->with('success', 'Buku dihapus dari favorit.');
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
