<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\SseMatchController;
use App\Http\Controllers\Api\MatchTimerController;

// Route standard pour les utilisateurs authentifiés
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les mises à jour en temps réel
Route::get('/matches/{match}/live-update', [MatchController::class, 'liveUpdate']);
Route::get('/matches/{match}/timeline', [MatchController::class, 'timeline']);
// routes/api.php
Route::get('/matches/{match}/realtime', [\App\Http\Controllers\Api\RealTimeMatchController::class, 'getLiveData'])
    ->name('api.matches.realtime');
// routes/api.php


Route::get('/matches/{match}/stream', [SseMatchController::class, 'stream'])
    ->name('api.matches.stream');
// routes/api.php


Route::get('/matches/{match}/timer', [MatchTimerController::class, 'getTimer']);
Route::post('/settings/password', [PasswordController::class, 'update']);