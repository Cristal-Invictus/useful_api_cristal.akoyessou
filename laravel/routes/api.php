<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controller\ModuleController;
use App\Http\Controller\WalletController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [ModuleController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::post('/modules/{id}/activate', [ModuleController::class, 'activate']);
    Route::post('/modules/{id}/deactivate', [ModuleController::class, 'deactivate']);


    Route::get('/wallet', [WalletController::class, 'index'])
         ->middleware('check.module:Wallet');
});

