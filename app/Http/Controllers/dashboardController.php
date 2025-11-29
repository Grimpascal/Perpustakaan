<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\User;
use App\Models\Peminjaman;

class dashboardController extends Controller
{
    public function showDB(Request $request){
        $user = Auth::user();
        $title = 'Dashboard';
        $query = Book::query();
        $total_buku = Book::count();
        $total_pengguna = User::count();
        $total_peminjam = Peminjaman::count();

        if ($search = $request->get('search')) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
        }

        $books = $query->orderBy('created_at', 'desc')->get();

        if ($user->role == 'admin'){
            return view('admin/dashboardAdmin', compact('user', 'title', 'total_buku', 'total_pengguna', 'total_peminjam'));
        }

        return view('user/dashboardUser', compact('title', 'user', 'books'));
    }
}
