<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;

// Route untuk Halaman Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Contoh Halaman Setelah Login (Dashboard)
// Middleware 'auth' memastikan hanya user yang login yang bisa akses
Route::get('/dashboard', function () {
    return "<h1>Selamat Datang, " . Auth::user()->username . "!</h1><br> <form action='/logout' method='POST'>". csrf_field() ."<button type='submit'>Logout</button></form>";
})->middleware('auth');

Route::get('/', function () {
    return redirect('/login');
});

// Route Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware(['auth'])->group(function () {
    
    // Ganti dashboard atau buat link baru ke form ini
    Route::get('/dashboard', function () {
         return redirect()->route('applications.create');
    })->name('dashboard');

    // Route Form Aplikasi
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    // Halaman list semua aplikasi
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    // Halaman detail aplikasi
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    
});
