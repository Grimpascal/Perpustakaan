<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $title = 'Profil Saya';

        if($user->role == 'admin'){
                return view('admin/profileAdmin', compact('title', 'user'));
            }

        return view('user.profile', [
            'title' => 'Profil Saya',
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email',
            'photo' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile'), $filename);
            $user->photo = $filename;
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
