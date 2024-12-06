<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\AuthController;

Route::options('{any}', function () {
    return response()->json(['message' => 'CORS preflight response'], Response::HTTP_OK);
})->where('any', '.*');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::middleware([
    'auth:sanctum',
    ThrottleRequests::class . ':60,1', // Membatasi 60 permintaan per menit
])->group(function () {
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('players', PlayerController::class);
    Route::apiResource('matches', MatchesController::class);
    //Route::delete('/teams/{team}', [TeamController::class, 'destroy']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
