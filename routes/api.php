<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InterestController;

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




Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/{userId}/my-users', [UserController::class, 'getMyUsers']);
    Route::get('/{userId}/my-interests', [UserController::class, 'getMyInterests']);
    Route::delete('/{userId}/delete-my-interests', [UserController::class, 'deleteMyInterests']);
    Route::post('/{userId}/add-my-interests', [UserController::class, 'addInterests']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/get-interests', [InterestController::class, 'getInterests']);


    Route::post('/{userId}/create-my-client', [UserController::class, 'createMyClient']);
    Route::put('/{userId}/update-my-client/{clientId}', [UserController::class, 'updateMyClientDetail']);
    Route::delete('/{userId}/delete-my-client/{clientId}', [UserController::class, 'deleteMyClientDetail']);
    Route::get('/{userId}/get-my-client/{clientId}', [UserController::class, 'getMyClientDetails']);
});

