<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Widok profilu
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

Route::get('/workers', [App\Http\Controllers\WorkersController::class, 'show'])->name('workers.show');

Route::get('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmail"])->name('password.email');
Route::post('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmailPost"])->name('password.email.post');
Route::get('password/reset/{token}', [App\Http\Controllers\PasswordEmail::class, "passwordReset"])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\PasswordEmail::class, "passwordResetPost"])->name('password.reset.post');