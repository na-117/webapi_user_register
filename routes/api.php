<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LoginController;
use Symfony\Component\HttpFoundation\Response;
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

Route::get('/', function () {
    return response()->json('このページへのアクセスにはログインが必要です', Response::HTTP_UNAUTHORIZED);
})->name('login');

Route::post('users', [UserController::class, 'add']);
Route::get('users', [UserController::class, 'fetchAll'])->middleware('auth:sanctum');
Route::get('users/{id}', [UserController::class, 'fetchById'])->middleware('auth:sanctum');


// ログイン
Route::post('/login', [LoginController::class, 'login']);
