<?php

use App\Http\Controllers\CaisseController; // Ensure this controller exists in the specified namespace
use App\Http\Controllers\UserController; // Ensure this controller exists in the specified namespace
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // Ensure this controller exists in the specified namespace
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::prefix('caisses')->group(function () {
        Route::get('/', [CaisseController::class, 'index'])->name('caisse.index');
    });
// Nouvelle route pour les appels AJAX
Route::get('/caisses/{id_caisse}/mouvements', [CaisseController::class, 'getMouvementsHtml'])->name('caisses.mouvements.html');
});

require __DIR__ . '/auth.php';
