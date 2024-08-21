<?php

use App\Http\Controllers\ProductDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dummy route to collect the product data for the test
Route::get('product-data', [ProductDataController::class, 'index']);
