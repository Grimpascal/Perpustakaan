<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class penggunaController extends Controller
{
    public function showPengguna(Request $request) {
        $title = 'Pengguna';

        $users = User::when($request->search, function($query, $search) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
        })
        ->when($request->role, function($query, $role) {
            $query->where('role', $role);
        })
        ->paginate(5);

        $users->appends($request->except('page'));

        return view('admin/pengguna', compact('title','users'));
    }

    public function hapus(User $user) {
    try {
        if ($user->id === auth()->id()) {
            return redirect()->route('pengguna')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        
        $user->delete();
        
        return redirect()->route('pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
            
    } catch (\Exception $e) {
        return redirect()->route('pengguna')
            ->with('error', 'Terjadi kesalahan saat menghapus pengguna.');
    }
    }

    public function tambah(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user'
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('pengguna')
            ->with('success', 'Pengguna berhasil ditambahkan.');

    }

    public function update(Request $request, User $user) {
        $request->validate([
            'nama_lengkap' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8|nullable',
            'role' => 'sometimes|in:admin,user'
        ]);

        $updateData = [];

        // Only update fields that are provided
        if ($request->has('nama_lengkap') && $request->nama_lengkap != '') {
            $updateData['nama_lengkap'] = $request->nama_lengkap;
        }

        if ($request->has('username') && $request->username != '') {
            $updateData['username'] = $request->username;
        }

        if ($request->has('email') && $request->email != '') {
            $updateData['email'] = $request->email;
        }

        if ($request->has('password') && $request->password != '') {
            $updateData['password'] = bcrypt($request->password);
        }

        if ($request->has('role') && $request->role != '') {
            $updateData['role'] = $request->role;
        }

        // Only update if there's data to update
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return redirect()->route('pengguna')
            ->with('success', 'Data pengguna berhasil diupdate.');
    }
}