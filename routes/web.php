<?php

use App\Http\Controllers\admin\admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;


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


//widok admina do zrobienia jeszcze
Route::get('/admin/dashboard', [admin::class, 'showDashboard'])->name('admin.dashboard');

Route::resource('tickets', TicketController::class);

// Trasa do usuwania załączników
Route::delete('tickets/{ticket}/attachment/{attachment}', [TicketController::class, 'destroyAttachment'])->name('tickets.destroyAttachment');

// Trasa do dodawania załączników do ticketu
Route::post('/tickets/{ticket}/attachments', [TicketController::class, 'addAttachment'])->name('tickets.addAttachment');
