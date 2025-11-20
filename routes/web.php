<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

Route::get('/add-product', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/all-products', [ProductController::class, 'allProducts']); // عرض + بحث
Route::get('/', function () { return redirect('/add-product'); });
Route::get('/search-barcode', [ProductController::class, 'searchByBarcode']);

Route::post('/update-quantity/{id}', [ProductController::class, 'updateQuantity']);
Route::post('/update-all-quantities', [ProductController::class, 'updateAllQuantities']);

Route::get('/report', [ReportController::class, 'index']);
Route::post('/report/pdf', [ReportController::class, 'exportPdf']);

Route::get('/remove-from-cart/{id}', [ProductController::class, 'removeFromCart']);
Route::get('/search-products', [ProductController::class, 'liveSearch']);
Route::post('/add-to-cart', [ProductController::class, 'addToCartAjax']);
