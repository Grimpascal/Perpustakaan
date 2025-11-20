<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginController extends Controller
{
    public function showLogin(){
        $title = 'Login';

        return view('login', compact('title'));
    }
}
