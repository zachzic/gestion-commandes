<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommandeController;

Route::get('/', function () {
    return redirect()->route('commandes.index');
});

Route::get('/commandes/{filter?}', [CommandeController::class, 'index'])->name('commandes.index');
Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
Route::delete('/commandes/{id}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
// Route pour confirmer une commande (mettre paye à oui)
Route::patch('/commandes/{id}/confirmer', [CommandeController::class, 'confirmer'])->name('commandes.confirmer');
// Route pour afficher les détails d'une commande
Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
