<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class penggunaController extends Controller
{
    public function showPengguna() {
        $title = 'Pengguna';
        $users = User::all();

        return view('pengguna', compact('title','users'));
    }
}
