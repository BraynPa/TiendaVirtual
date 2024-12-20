<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
Auth::routes();


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('products', ProductController::class);
    
    Route::get('download/{order}', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::resource('orders', OrderController::class)->only('store');
    Route::get('callback/{order:uuid}', [OrderController::class, 'callback'])->name('config');
    Route::resource('invoices', InvoiceController::class)->only(['index', 'store']);
    Route::post('add-to-cart/{product}', [ShoppingCartController::class, 'store']);
    Route::get('checkout', [ShoppingCartController::class, 'index'])->name('checkout');
  });

