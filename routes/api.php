<?php

use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\TwitchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("auth/callback", [TwitchController::class, 'handleTwitchCallback']);
Route::get("auth/url", [TwitchController::class, 'getAuthUrl']);

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('sessions', StudySessionController::class);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
