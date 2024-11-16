<?php

use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;

Route::name('products.')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'list'])->name('list');
    Route::put('/{code}', [ProductController::class, 'update'])->name('update');
    Route::get('/{code}', [ProductController::class, 'show'])->name('show');
    Route::delete('/{code}', [ProductController::class, 'delete'])->name('delete');
});