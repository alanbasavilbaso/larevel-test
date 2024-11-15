<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
use App\Http\Controllers\FavoriteGifController;
use App\Http\Controllers\ErrorController;

Route::prefix('v1')->group(function () {    
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:api')->group(function () {
        Route::get('gifs/search', [GifController::class, 'search']);
        Route::get('gifs/{id}', [GifController::class, 'show']);
        Route::post('gifs/favorite', [FavoriteGifController::class, 'store']);
    });
});

Route::fallback([ErrorController::class, 'handleNotFound']);
