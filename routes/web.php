<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landingPage');
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [loginController::class, 'showLogin'])->name('Login');
    Route::post('/login', [loginController::class, 'verifLogin'])->name('verifLogin');
});

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [dashboardController::class, 'showDB'])->name('showDB');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});