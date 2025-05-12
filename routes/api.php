<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PharmacyAuthController;
use App\Http\Controllers\Api\Auth\WarehouseAuthController;
use App\Http\Controllers\MedicineController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/testww', function () {
    return response()->json(['message' => 'This is a test message!'], 200);
});

Route::prefix('pharmacy')->group(function () {
    Route::post('/register', [PharmacyAuthController::class, 'register']);
    Route::post('/login', [PharmacyAuthController::class, 'login']);
    Route::post('/logout', [PharmacyAuthController::class, 'logout'])
    ->middleware('auth:sanctum');
    Route::get('/medicines/by-category', [MedicineController::class, 'getByCategory'])->middleware('auth:sanctum');
});


//////////////
Route::prefix('warehouse-owner')->group(function () {
    Route::post('/login', [WarehouseAuthController::class, 'login']);
         Route::middleware('auth:sanctum')->group(function () {
            Route::apiResource('medicines', MedicineController::class);
                Route::post('/logout', [WarehouseAuthController::class, 'logout']);
         

        });
});
Route::get('/ping', function () {
    return response()->json(['message' => 'API تعمل بنجاح']);
});

