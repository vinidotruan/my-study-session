<?php

use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\TwitchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("auth/login", [TwitchController::class, 'handleTwitchCallback']);
Route::get("auth/url", [TwitchController::class, 'getAuthUrl']);

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('sessions')->group(function () {
        Route::post('{id}/start', [StudySessionController::class, 'start']);
        Route::get('{studySession:uri}', [StudySessionController::class, 'show']);
        Route::get('', [StudySessionController::class, 'index']);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
