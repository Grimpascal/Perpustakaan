<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class dashboardController extends Controller
{
    public function showDB(Request $request){
        $user = Auth::user();
        $title = 'Dashboard';
        $query = Book::query();

        if ($search = $request->get('search')) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
        }

        $books = $query->orderBy('created_at', 'desc')->get();

        if ($user->role == 'admin'){
            return view('dashboardAdmin', compact('user', 'title'));
        }

        return view('user/dashboardUser', compact('title', 'user', 'books'));
    }
}
