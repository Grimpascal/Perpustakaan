<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\penggunaController;

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

Route::middleware(['auth', 'role:user'])->group(function () {
    
    Route::get('buku', [UserController::class, 'buku'])
        ->name('buku');

    Route::get('/user/buku', [UserController::class, 'buku'])->name('user.buku');

    Route::get('/book/{id}', [UserController::class, 'detail'])->name('book.detail');

     Route::get('/peminjaman', [UserController::class, 'peminjaman'])->name('user.peminjaman');

    Route::post('/user/buku/pinjam/{id}', [UserController::class, 'pinjam'])
        ->name('user.pinjam');

    Route::post('/user/buku/kembalikan/{id}', [UserController::class, 'kembalikan'])
        ->name('user.kembalikan');

    Route::get('/favorite', [UserController::class, 'favorite'])->name('user.favorite');
    Route::post('/favorite/add/{id}', [UserController::class, 'addFavorite'])->name('user.favorite.add');
    Route::delete('/favorite/remove/{id}', [UserController::class, 'removeFavorite'])->name('user.favorite.remove');


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/history', [UserController::class, 'history'])->name('user.pinjam.history');
    Route::post('/peminjaman/kembalikan/{id}', [UserController::class, 'kembalikan'])->name('user.kembalikan');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pengguna', [penggunaController::class, 'showPengguna'])->name('pengguna');
    Route::delete('/pengguna/{user}', [penggunaController::class, 'hapus'])->name('hapus');
    Route::post('/pengguna', [penggunaController::class, 'tambah'])->name('pengguna.tambah');
    Route::put('/pengguna/{user}', [penggunaController::class, 'update'])->name('pengguna.update');
});