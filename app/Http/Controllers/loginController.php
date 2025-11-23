<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class loginController extends Controller
{
    public function showLogin(){
        $title = 'Login';

        return view('login', compact('title'));
    }

    public function showRegister(){
        $title = 'Register';

        return view('register', compact('title'));
    }

    public function verifLogin(Request $request){
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();

            if($user->role == 'admin'){
                return redirect()->intended('/dashboard');
            }
        
            return redirect()->intended('/user/dashboard');
        } 

        return back()->with('errorLogin', 'Username atau password anda salah!');
    }

    public function regisUser(Request $request){

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'min' => ':attribute minimal :min karakter.',
            'unique' => ':attribute sudah ada',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'password' => hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        if (empty($request->nama_lengkap) || empty($request->username) || empty($request->email) || empty($request->password)) {
         return back()->with('errorRegister', 'Ada kolom yang kosong harus diisi!');
        }

        return redirect('login');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
