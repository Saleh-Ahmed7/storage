<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
   ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth')->group(function () {

    Route::get('/add-product', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/all-products', [ProductController::class, 'allProducts'])->name('all.products'); // عرض + بحث
    Route::get('/', function () {
        return redirect('/add-product');
    });
    Route::get('/search-barcode', [ProductController::class, 'search']);

    Route::post('/update-quantity/{id}', [ProductController::class, 'updateQuantity']);
    Route::post('/update-all-quantities', [ProductController::class, 'updateAllQuantities']);

    Route::get('/report', [ReportController::class, 'index']);
    Route::post('/report/pdf', [ReportController::class, 'exportPdf']);
    Route::post('allProduct/pdf}', [ReportController::class, 'allProductPDF'])->name('allProductPDF');

    Route::get('/remove-from-cart/{id}', [ProductController::class, 'removeFromCart']);
    Route::get('/search-products', [ProductController::class, 'liveSearch']);
    Route::post('/add-to-cart', [ProductController::class, 'addToCartAjax']);

    Route::delete('/product/{id}', [ProductController::class, 'deleteQuantitie'])->name('product.delete');

    Route::get('/live-search', [ProductController::class, 'liveSearch']);

    Route::get('/resteCart', [ProductController::class, 'resteCart']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
