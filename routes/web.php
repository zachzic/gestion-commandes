<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommandeController;

Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
Route::delete('/commandes/{id}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
Route::get('/commandes/paid', [CommandeController::class, 'showPaid'])->name('commandes.paid');
Route::get('/commandes/unpaid', [CommandeController::class, 'showUnpaid'])->name('commandes.unpaid');

// Route::get('/', function () {
//     return view('welcome');
// });
