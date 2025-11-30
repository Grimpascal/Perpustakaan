<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\UserController;
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

    Route::post('/user/buku/pinjam/{id}', [UserController::class, 'pinjam'])
        ->name('user.pinjam');

    Route::post('/user/buku/kembalikan/{id}', [UserController::class, 'kembalikan'])
        ->name('user.kembalikan');

    Route::get('/user/favorite', [UserController::class, 'favorite'])
        ->name('user.favorite');

    Route::post('/user/favorite/add/{id}', [UserController::class, 'tambahFavorite'])
        ->name('user.favorite.add');

    Route::delete('/user/favorite/delete/{id}', [UserController::class, 'hapusFavorite'])
        ->name('user.favorite.delete');

    Route::get('/user/profil', [UserController::class, 'profil'])
        ->name('user.profil');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pengguna', [penggunaController::class, 'showPengguna'])->name('pengguna');
    Route::delete('/pengguna/{user}', [penggunaController::class, 'hapus'])->name('hapus');
    Route::post('/pengguna', [penggunaController::class, 'tambah'])->name('pengguna.tambah');
    Route::put('/pengguna/{user}', [penggunaController::class, 'update'])->name('pengguna.update');
});