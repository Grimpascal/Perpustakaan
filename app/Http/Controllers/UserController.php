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
    
    return view('user.dashboard', [
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
            'title' => $book->judul,
            'book' => $book,
            'isFavorite' => $isFavorite
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
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        return redirect()->route('user.peminjaman')
            ->with('success', 'Buku "' . $book->judul . '" berhasil dipinjam! Harap dikembalikan sebelum ' . $peminjaman->tanggal_kembali->format('d M Y'));
    }

    // ============================
    // DAFTAR PEMINJAMAN AKTIF
    // ============================
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
    // PENGEMBALIAN BUKU
    // ============================
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->with('book')
            ->firstOrFail();

        // Hitung denda jika telat
        $jatuh_tempo = Carbon::parse($peminjaman->tanggal_kembali);
        $hari_telat = Carbon::now()->diffInDays($jatuh_tempo, false) * -1;
        
        $denda = 0;
        if ($hari_telat > 0) {
            $denda = $hari_telat * 10000; // 10k per hari
        }

        // Update peminjaman
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_dikembalikan = Carbon::now();
        $peminjaman->denda = $denda;
        $peminjaman->save();

        // Tambah stok buku kembali
        $book = $peminjaman->book;
        $book->stok += 1;
        $book->is_available = true;
        $book->save();

        $message = 'Buku "' . $book->judul . '" berhasil dikembalikan!';
        if ($denda > 0) {
            $message .= ' Denda: Rp ' . number_format($denda, 0, ',', '.');
        }

        return redirect()->route('user.peminjaman')
            ->with('success', $message);
    }

    // ============================
    // RIWAYAT PEMINJAMAN
    // ============================
    public function history()
    {
        $peminjamans = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->where('status', 'dikembalikan')
            ->orderBy('tanggal_dikembalikan', 'desc')
            ->paginate(15);
            
        return view('user.history', [
            'title' => 'Riwayat Peminjaman',
            'peminjamans' => $peminjamans
        ]);
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

    public function tambahFavorite($id)
    {
        $book = Book::findOrFail($id);
        
        $existing = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Buku "' . $book->judul . '" sudah ada di favorit!');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'book_id' => $id
        ]);

        return redirect()->back()->with('success', 'Buku "' . $book->judul . '" ditambahkan ke favorit!');
    }

    public function hapusFavorite($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->firstOrFail();
            
        $bookTitle = $favorite->book->judul;
        $favorite->delete();

        return redirect()->back()->with('success', 'Buku "' . $bookTitle . '" dihapus dari favorit!');
    }

    // ============================
    // PROFIL USER
    // ============================
    public function profil()
    {
        $user = Auth::user();
        
        // Stats
        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();
        $totalFavorite = Favorite::where('user_id', $user->id)->count();
        
        return view('user.profil', [
            'title' => 'Profil Saya',
            'user' => $user,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanAktif' => $peminjamanAktif,
            'totalFavorite' => $totalFavorite
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        return redirect()->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai!');
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('user.profil')
            ->with('success', 'Password berhasil diperbarui!');
    }

    // ============================
    // NOTIFIKASI
    // ============================
    public function notifications()
    {
        // Cek peminjaman yang hampir jatuh tempo (2 hari lagi)
        $peminjamans = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->get();
            
        $notifications = [];
        
        foreach ($peminjamans as $peminjaman) {
            $sisaHari = Carbon::now()->diffInDays($peminjaman->tanggal_kembali, false);
            
            if ($sisaHari <= 2 && $sisaHari >= 0) {
                $notifications[] = [
                    'type' => 'warning',
                    'message' => 'Buku "' . $peminjaman->book->judul . '" harus dikembalikan dalam ' . $sisaHari . ' hari',
                    'book_id' => $peminjaman->book_id,
                    'days_left' => $sisaHari
                ];
            } elseif ($sisaHari < 0) {
                $notifications[] = [
                    'type' => 'danger',
                    'message' => 'Buku "' . $peminjaman->book->judul . '" terlambat ' . abs($sisaHari) . ' hari. Denda: Rp ' . number_format(abs($sisaHari) * 10000, 0, ',', '.'),
                    'book_id' => $peminjaman->book_id,
                    'days_overdue' => abs($sisaHari)
                ];
            }
        }
        
        return view('user.notifications', [
            'title' => 'Notifikasi',
            'notifications' => $notifications
        ]);
    }
}