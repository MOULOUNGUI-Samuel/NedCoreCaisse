<?php

use App\Http\Controllers\CaisseController; // Ensure this controller exists in the specified namespace
use App\Http\Controllers\UserController; // Ensure this controller exists in the specified namespace
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // Ensure this controller exists in the specified namespace

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/caisse_externe_dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('caisse_externe_dashboard');


Route::middleware('auth')->group(function () {

    Route::prefix('caisses_externe')->group(function () {
        Route::get('/', [CaisseController::class, 'indexExterne'])->name('caisseExterne.index');
    });
    Route::prefix('content_application')->group(function () {
        Route::get('/', [CaisseController::class, 'index'])->name('caisse.index');
         Route::post('/store', [CaisseController::class, 'store'])->name('caisses.store');
         Route::post('/storecategorie', [CaisseController::class, 'storecategorie'])->name('categorie.store');
    });
    Route::get('/categorie/{id}/motifs', [CaisseController::class, 'getMotifs']);

// Nouvelle route pour les appels AJAX
Route::get('/caisses/{id_caisse}/mouvements', [CaisseController::class, 'getMouvementsHtml'])->name('caisses.mouvements.html');
Route::get('/caisses/{id_caisse}/mouvementsExterne', [CaisseController::class, 'getMouvementsHtmlExterne'])->name('caisses.mouvements.htmlExterne');
});

require __DIR__ . '/auth.php';
