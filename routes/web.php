<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\penggunaController;
use App\Http\Controllers\bukuController;
use App\Http\Controllers\peminjamanController;

Route::get('/', function () {
    return view('landingPage');
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [loginController::class, 'showLogin'])->name('Login');
    Route::post('/login', [loginController::class, 'verifLogin'])->name('verifLogin');

    Route::get('/register', [loginController::class, 'showRegister'])->name('register');
    Route::post('/register', [loginController::class, 'regisUser'])->name('regisUser');
});

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [dashboardController::class, 'showDB'])->name('dashboard');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/books', [UserController::class, 'buku'])->name('books');
    Route::get('/book/{id}', [UserController::class, 'showBook'])->name('book.show');
    
    // Peminjaman
    Route::post('/borrow/{id}', [UserController::class, 'pinjam'])->name('pinjam');
    Route::get('/borrowings', [UserController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/return/{id}', [UserController::class, 'kembalikan'])->name('kembalikan');
    Route::get('/history', [UserController::class, 'history'])->name('history');
    
    // Favorit
    Route::get('/favorites', [UserController::class, 'favorite'])->name('favorites');
    Route::post('/favorite/add/{id}', [UserController::class, 'tambahFavorite'])->name('favorite.add');
    Route::post('/favorite/remove/{id}', [UserController::class, 'hapusFavorite'])->name('favorite.remove');
    
    // Profil
    Route::get('/profile', [UserController::class, 'profil'])->name('profil');
    Route::put('/profile/update', [UserController::class, 'updateProfil'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    
    // Notifikasi
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pengguna', [penggunaController::class, 'showPengguna'])->name('pengguna');
    Route::delete('/pengguna/{user}', [penggunaController::class, 'hapus'])->name('hapus');
    Route::post('/pengguna', [penggunaController::class, 'tambah'])->name('pengguna.tambah');
    Route::put('/pengguna/{user}', [penggunaController::class, 'update'])->name('pengguna.update');

    Route::get('/buku/admin', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/tambah', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [BukuController::class, 'hapus'])->name('buku.destroy');

    // Peminjaman Routes - Sesuai dengan route yang ada
    Route::resource('peminjaman', peminjamanController::class)->except(['create', 'store', 'edit', 'update']);
   
    // Ganti semua route peminjaman dengan ini:
    Route::resource('peminjaman', peminjamanController::class)->except(['create', 'store', 'edit', 'update']);
    Route::get('/peminjaman/{id}/detail', [peminjamanController::class, 'detail'])->name('peminjaman.detail');
    Route::get('/peminjaman/{id}/calculate-denda', [peminjamanController::class, 'calculateDenda'])->name('peminjaman.calculate-denda');
    Route::put('/peminjaman/{id}/return', [peminjamanController::class, 'returnBook'])->name('peminjaman.return');
    Route::get('/peminjaman/export', [peminjamanController::class, 'export'])->name('peminjaman.export');
}); 