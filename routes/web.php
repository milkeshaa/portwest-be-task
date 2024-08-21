<?php

use App\Http\Controllers\ProductDataController;
use App\Http\Controllers\SkuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dummy route to collect the product data for the test
Route::get('product-data', [ProductDataController::class, 'index']);

Route::get('skus', [SkuController::class, 'index']);

// simple dummy route for authentication to check box_qty, and other "hidden" props
Route::withoutMiddleware(['verify_csrf'])->post('login', function (Request $request) {
    $credentials = ['email' => 'test@example.com', 'password' => 'password'];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user(),
        ]);
    }

    return response()->json([
        'message' => 'Invalid credentials',
    ], 401);
});

// simple dummy route to invalidate the session and hide box_qty, and other fields
Route::middleware(['auth'])->post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'Logged out successfully']);
});
