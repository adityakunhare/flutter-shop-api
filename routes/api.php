<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('V1')->group(function () {

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('brands', [BrandController::class, 'index']);
});
