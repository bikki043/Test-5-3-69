<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// เส้นทางไป Google
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');;

// เส้นทางรับข้อมูลกลับ
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';
