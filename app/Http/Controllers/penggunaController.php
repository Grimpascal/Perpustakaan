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

    public function hapus(User $user)
{
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
}