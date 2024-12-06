<?php

use App\Http\Controllers\PartnersController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\TwitchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("auth/login", [TwitchController::class, 'handleTwitchCallback']);
Route::get("auth/url", [TwitchController::class, 'getAuthUrl']);

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('sessions')->group(function () {
        Route::post('', [StudySessionController::class, 'store']);
        Route::post('{id}/start', [StudySessionController::class, 'start']);
        Route::get('{studySession:uri}', [StudySessionController::class, 'show']);
        Route::get('', [StudySessionController::class, 'index']);
    });
    Route::post('partner/entered', [PartnersController::class, 'followerEntered']);
    Route::post('partner/leave', [PartnersController::class, 'followerLeave']);
    Route::apiResource('partner', PartnersController::class);
    Route::post('auth/logout', [TwitchController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
