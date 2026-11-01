<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GigController;
use App\Http\Controllers\MerchFilterController;
use App\Http\Controllers\MerchItemController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VumeProductController;
use Illuminate\Support\Facades\Route;

// ===== Publik (Home, Merch, Vume, Gigs) =====
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/merch', [MerchItemController::class, 'index']);
Route::get('/merch-filters', [MerchFilterController::class, 'index']);
Route::get('/promo', [PromoController::class, 'show']);
Route::get('/vume', [VumeProductController::class, 'index']);
Route::get('/gigs', [GigController::class, 'index']);
Route::get('/gigs/{gig}', [GigController::class, 'show']);

// ===== Auth admin =====
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

// ===== Admin (/admin) =====
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/schedules', [ScheduleController::class, 'bulkUpdate']);

    Route::put('/promo', [PromoController::class, 'update']);

    Route::post('/merch', [MerchItemController::class, 'store']);
    Route::put('/merch/{merch}', [MerchItemController::class, 'update']);
    Route::delete('/merch/{merch}', [MerchItemController::class, 'destroy']);

    Route::post('/merch-filters', [MerchFilterController::class, 'store']);
    Route::delete('/merch-filters', [MerchFilterController::class, 'destroy']);

    Route::put('/vume/{vume}', [VumeProductController::class, 'update']);

    Route::post('/gigs', [GigController::class, 'store']);
    Route::put('/gigs/{gig}', [GigController::class, 'update']);
    Route::delete('/gigs/{gig}', [GigController::class, 'destroy']);
});
