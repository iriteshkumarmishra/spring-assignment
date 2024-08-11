<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\User\UserController;


Route::middleware('api')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::patch('/{id}/points', [UserController::class, 'updatePoints']);
    Route::get('/grouping/score', [UserController::class, 'getUsersGroupedByScore']);
});

