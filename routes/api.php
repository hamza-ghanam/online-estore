<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use \App\Http\Controllers\CustomerController;
use \App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/all', [ProductController::class, 'getAll']);

Route::prefix('product')->group(function () {
    Route::get('/{id}', [ProductController::class, 'getById']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::get('/orders', [OrderController::class, 'index']);

Route::prefix('order')->group(function () {
    Route::get('/{id}', [OrderController::class, 'getById']);
    Route::post('/store', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});

Route::get('/customers', [CustomerController::class, 'index']);

Route::get('/suppliers', [SupplierController::class, 'index']);
