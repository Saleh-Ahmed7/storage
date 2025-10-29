<?php

use App\Http\Controllers\ProductController;


Route::get('/add-product', [ProductController::class, 'index']);   // صفحة إضافة منتج
Route::post('/products', [ProductController::class, 'store']);     // حفظ البيانات
Route::get('/all-products', [ProductController::class, 'allProducts']); // عرض + بحث
Route::get('/', function () { return redirect('/add-product'); });
Route::get('/search-barcode', [ProductController::class, 'searchByBarcode']);

Route::post('/update-quantity/{id}', [ProductController::class, 'updateQuantity']);
Route::post('/update-all-quantities', [ProductController::class, 'updateAllQuantities']);


