<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PharmacyAuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/testww', function () {
    return response()->json(['message' => 'This is a test message!'], 200);
});

Route::prefix('pharmacy')->group(function () {
    Route::post('/register', [PharmacyAuthController::class, 'register']);
    Route::post('/login', [PharmacyAuthController::class, 'login']);
});