<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaundryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LaundryController::class, 'index'])->name('home');
Route::post('/transaction', [LaundryController::class, 'store'])->name('transaction.store');
Route::get('/tracking', [LaundryController::class, 'tracking'])->name('tracking');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::patch('/transaction/{transaction}', [App\Http\Controllers\AdminController::class, 'updateStatus'])->name('transaction.update');
});

require __DIR__ . '/auth.php';
