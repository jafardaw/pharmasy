<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PharmacyAuthController;
use App\Http\Controllers\Api\Auth\WarehouseAuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\FavoriteController;




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
        Route::get('/medicines/search', [MedicineController::class, 'searchMedicines'])->middleware('auth:sanctum');
                    Route::apiResource('medicines', MedicineController::class)->middleware('auth:sanctum');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('auth:sanctum');
    Route::middleware('auth:sanctum')->get('/my-orders', [OrderController::class, 'myOrders']);
           Route::get('/getfavorite', [FavoriteController::class, 'index']);
        Route::post('/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/check/{medicineId}', [FavoriteController::class, 'check']);




});


//////////////
Route::prefix('warehouse-owner')->group(function () {
    Route::post('/login', [WarehouseAuthController::class, 'login']);
         Route::middleware('auth:sanctum')->group(function () {

            Route::apiResource('medicines', MedicineController::class);
                Route::post('/logout', [WarehouseAuthController::class, 'logout']);
                Route::get('/search', [MedicineController::class, 'searchMedicines']);
                Route::get('/orders', [OrderController::class, 'index']); // لعرض الطلبيات
Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus']); // لتغيير حالة الطلب
Route::put('/orders/{orderId}/payment-status', [OrderController::class, 'updatePaymentStatus']);

         

        });
});
Route::get('/ping', function () {
    return response()->json(['message' => 'API تعمل بنجاح']);
});

