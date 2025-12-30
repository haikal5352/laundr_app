<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaundryController; // <--- PENTING: Panggil Controllernya

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// INI JALUR KHUSUS BUAT MIDTRANS (PINTU BELAKANG)
Route::post('/midtrans-callback', [LaundryController::class, 'callback']);