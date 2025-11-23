<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/dashboard', [dashboardController::class, 'showDB'])->name('showDB');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    // dashboard
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])
        ->name('user.dashboard');

    // buku
    Route::get('/user/buku', [UserController::class, 'buku'])
        ->name('user.buku');

    // pinjam buku
    Route::post('/user/buku/pinjam/{id}', [UserController::class, 'pinjam'])
        ->name('user.pinjam');

    // pengembalian buku
    Route::post('/user/buku/kembalikan/{id}', [UserController::class, 'kembalikan'])
        ->name('user.kembalikan');

    // wishlist
    Route::get('/user/favorite', [UserController::class, 'favorite'])
        ->name('user.favorite');

    Route::post('/user/favorite/add/{id}', [UserController::class, 'tambahFavorite'])
        ->name('user.favorite.add');

    Route::delete('/user/favorite/delete/{id}', [UserController::class, 'hapusFavorite'])
        ->name('user.favorite.delete');

    // profil
    Route::get('/user/profil', [UserController::class, 'profil'])
        ->name('user.profil');

    // logout
    Route::post('/logout', [UserController::class, 'logout'])
        ->name('logout');
});
