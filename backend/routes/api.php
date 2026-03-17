<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Admin Only
    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
        Route::apiResource('suppliers', \App\Http\Controllers\SupplierController::class);
    });

    // Admin & Staff
    Route::middleware('role:Admin,Staff,Cashier')->group(function () {
        Route::get('products/low-stock', [\App\Http\Controllers\ProductController::class, 'lowStock']);
        Route::apiResource('products', \App\Http\Controllers\ProductController::class);

        Route::get('reports/dashboard', [\App\Http\Controllers\ReportController::class, 'dashboardStats']);
        Route::get('reports/daily-sales', [\App\Http\Controllers\ReportController::class, 'dailySales']);
        Route::get('reports/monthly-revenue', [\App\Http\Controllers\ReportController::class, 'monthlyRevenue']);
        Route::get('reports/inventory-by-category', [\App\Http\Controllers\ReportController::class, 'inventoryByCategory']);
        Route::get('reports/top-products', [\App\Http\Controllers\ReportController::class, 'topProducts']);
        Route::get('reports/low-stock-alerts', [\App\Http\Controllers\ReportController::class, 'lowStockAlerts']);

        Route::apiResource('sales', \App\Http\Controllers\SaleController::class)->only(['index', 'store', 'show']);
        Route::apiResource('stock-movements', \App\Http\Controllers\StockMovementController::class)->only(['index', 'store']);
    });
});
