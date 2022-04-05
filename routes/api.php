<?php

use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TableroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/partida/add', [PartidaController::class, 'store']);
Route::get('/partida/{id}', [TableroController::class, 'show']);
Route::put('/tablero/update/{id}/{campo}/{clear}', [TableroController::class, 'update']);
