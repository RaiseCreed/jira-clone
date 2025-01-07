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

//Zarządzanie pracownikami
Route::get('/workers', [App\Http\Controllers\WorkersController::class, 'show'])->name('workers.show');
Route::get('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmail"])->name('password.email');
Route::post('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmailPost"])->name('password.email.post');
Route::get('password/reset/{token}', [App\Http\Controllers\PasswordEmail::class, "passwordReset"])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\PasswordEmail::class, "passwordResetPost"])->name('password.reset.post');

//Dodawanie użytkownika
Route::get('/users/add', [App\Http\Controllers\UserController::class, 'addUser'])->name('users.add');
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'addUserPost'])->name('users.add.post');
Route::get('/password/set/{name}/{token}/{email}', [App\Http\Controllers\UserController::class, 'passwordSet'])->name('password.set');
Route::post('/password/set', [App\Http\Controllers\UserController::class, 'passwordSetPost'])->name('password.set.post');