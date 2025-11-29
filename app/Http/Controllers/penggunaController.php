<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class penggunaController extends Controller
{
    public function showPengguna() {
        $title = 'Pengguna';
        $users = User::paginate(5);

        return view('admin/pengguna', compact('title','users'));
    }
}
