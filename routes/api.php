<?php
use App\Http\Controllers\CaisseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/UserInfo', [CaisseController::class, 'UserInfo'])->name('api.users.UserInfo');
// Route publique pour se connecter et obtenir un token


// Groupe de routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Cette route ne sera accessible qu'avec un token valide
    Route::get('/UserInfo', [UserController::class, 'UserInfo']);

    // Vous pouvez obtenir les informations de l'utilisateur authentifié comme ceci
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Ajoutez ici toutes vos autres routes protégées...
});
Route::apiResource('users', UserController::class)->middleware('auth.apikey');
