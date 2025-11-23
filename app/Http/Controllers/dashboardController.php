<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dashboardController extends Controller
{
    public function showDB(){
        $user = Auth::user();
        $title = 'Dashboard';

        if ($user->role == 'admin'){
            return view('dashboardAdmin', compact('user', 'title'));
        }

        return view('dashboardUser', compact('title', 'user'));
    }
}
