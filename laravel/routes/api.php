<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controller\ModuleController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [ModuleController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::post('/modules/{id}/activate', [ModuleController::class, 'activate']);
    Route::post('/modules/{id}/deactivate', [ModuleController::class, 'deactivate']);

    // Route::post('/shorten', [Short])
    Route::get('/s/{code}', [ShortLinkController::class, 'redirect']);

      // Auth + module actif (id=1)
    Route::middleware(['auth:sanctum', 'module.active:1'])->group(function () {
        Route::post('/shorten', [ShortLinkController::class, 'store']);
        Route::get('/links', [ShortLinkController::class, 'index']);
        Route::delete('/links/{id}', [ShortLinkController::class, 'destroy']);
    });

    // Pour le wallet
    Route::middleware(['auth:sanctum','module.active:2'])->group(function () {
    Route::get('/wallet',               [WalletController::class, 'show']);
    Route::post('/wallet/topup',        [WalletController::class, 'topup']);
    Route::post('/wallet/transfer',     [WalletController::class, 'transfer']);
    Route::get('/wallet/transactions',  [WalletController::class, 'transactions']);
});

   //Pour les produits et commandes
    Route::middleware(['auth:sanctum','module.active:3'])->group(function () {
// Produits
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products/{id}/restock', [ProductController::class, 'restock']);


// Commandes
    Route::post('/orders', [OrderController::class, 'store']);
});

});

