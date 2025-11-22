<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class loginController extends Controller
{
    public function showLogin(){
        $title = 'Login';

        return view('login', compact('title'));
    }

    public function verifLogin(Request $request){
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
        
            return redirect()->intended('dashboard');
        } 
    }
}
