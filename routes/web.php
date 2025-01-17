<?php

use App\Http\Controllers\admin\admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Widoki profilu
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

//Zarządzanie pracownikami i klientami
Route::get('/workers', [App\Http\Controllers\WorkersController::class, 'show'])->name('workers.show');
Route::get('/customers', [App\Http\Controllers\CustomersController::class, 'show'])->name('customers.show');

//Reset hasła
Route::get('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmail"])->name('password.email');
Route::post('/password/email', [App\Http\Controllers\PasswordEmail::class, "passwordEmailPost"])->name('password.email.post');
Route::get('/password/reset/{token}/{email}', [App\Http\Controllers\PasswordEmail::class, "passwordReset"])->name('password.reset.new');
Route::post('/password/reset', [App\Http\Controllers\PasswordEmail::class, "passwordResetPost"])->name('password.reset.post');

//Dodawanie użytkownika
Route::get('/users/add', [App\Http\Controllers\UserController::class, 'addUser'])->name('users.add');
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'addUserPost'])->name('users.add.post');
Route::get('/password/set/{name}/{token}/{email}', [App\Http\Controllers\UserController::class, 'passwordSet'])->name('password.set');
Route::post('/password/set', [App\Http\Controllers\UserController::class, 'passwordSetPost'])->name('password.set.post');

//Usuwanie użytkownika
Route::delete('/users/{email}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('users.delete');

// Widoki ticketów
Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
Route::get('/tickets/{id}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
Route::post('/tickets/{id}/assign', [App\Http\Controllers\TicketController::class, 'assignWorker'])->name('tickets.assignWorker');
Route::get('/tickets/{ticket}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update');
Route::delete('/tickets/{id}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy');
Route::post('/tickets/{id}', [App\Http\Controllers\TicketController::class, 'changePhase'])->name('tickets.changePhase');

// Komentarze
Route::delete('/tickets/delete-comment/{comment}', [App\Http\Controllers\TicketController::class, 'deleteComment'])->name('tickets.delete-comment');
Route::post('/tickets/{id}/add-comment', [App\Http\Controllers\TicketController::class, 'addComment'])->name('tickets.add-comment');
