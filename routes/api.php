<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DeviceServiceVariantController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PriceLogController;
use App\Http\Controllers\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🧾 AUTH
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// 🛡️ Protected Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // 🚪 Logout
    Route::post('logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | 🔧 DEVICE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index']); // ?search=iphone
        Route::get('/{id}', [DeviceController::class, 'show']);
        Route::post('/', [DeviceController::class, 'store']);
        Route::put('/{id}', [DeviceController::class, 'update']);
        Route::delete('/{id}', [DeviceController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | 🧰 SERVICE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']); // ?search=lcd
        Route::get('/{id}', [ServiceController::class, 'show']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::put('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | 💰 DEVICE-SERVICE VARIANTS
    |--------------------------------------------------------------------------
    */
    Route::prefix('variants')->group(function () {
        Route::get('/', [DeviceServiceVariantController::class, 'index']); // ?device_id=1&se`rvice_id=2
        Route::get('/{id}', [DeviceServiceVariantController::class, 'show']);
        Route::post('/', [DeviceServiceVariantController::class, 'store']);
        Route::put('/{id}', [DeviceServiceVariantController::class, 'update']);
        Route::delete('/{id}', [DeviceServiceVariantController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | 🧾 TRANSACTIONS
    |--------------------------------------------------------------------------
    */
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']); // ?status=done
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::put('/{id}/status', [TransactionController::class, 'updateStatus']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
        Route::delete('/{id}/force', [TransactionController::class, 'forceDelete']);
        Route::post('/{id}/restore', [TransactionController::class, 'restore']);
    });

    /*
    |--------------------------------------------------------------------------
    | 📈 PRICE LOGS
    |--------------------------------------------------------------------------
    */
    Route::prefix('price-logs')->group(function () {
        Route::get('/', [PriceLogController::class, 'index']);
        Route::get('/{id}', [PriceLogController::class, 'show']);
    });

    Route::get('stats', [StatsController::class, 'index']);
    Route::get('transaction/report', [StatsController::class, 'report']);
    Route::apiResource('brands', BrandController::class);

});
