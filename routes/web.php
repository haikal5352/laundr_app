<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === HALAMAN DEPAN (GUEST) ===
Route::get('/', [LaundryController::class, 'index'])->name('home');
Route::post('/transaction', [LaundryController::class, 'store'])->name('transaction.store');
Route::get('/tracking', [LaundryController::class, 'tracking'])->name('tracking');

// 👇 INI DIA RUTE BARU BUAT BATAL PESANAN 👇
Route::post('/tracking/cancel/{id}', [LaundryController::class, 'cancelOrder'])->name('transaction.cancel');
Route::get('/tracking/print/{id}', [LaundryController::class, 'downloadInvoice'])->name('transaction.print');


// === RUTE DASHBOARD (LOGIKA PEMISAH) ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    // 1. Kalau ADMIN, lempar ke halaman admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.index');
    }

    // 2. Kalau USER BIASA, ambil data pesanannya dia saja
    $transactions = App\Models\Transaction::with('service')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

    return view('dashboard', compact('transactions'));
})->middleware(['auth', 'verified'])->name('dashboard');


// === RUTE KHUSUS ADMIN (Dilindungi Middleware) ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Admin (Memanggil index di controller)
    Route::get('/', [AdminController::class, 'index'])->name('index'); 
    Route::get('/export', [AdminController::class, 'exportLaporan'])->name('export');
    
    // Fitur Update Status (Patch)
    Route::patch('/transaction/{transaction}', [AdminController::class, 'updateStatus'])->name('transaction.update');
});

require __DIR__ . '/auth.php';

// ... di dalam group middleware 'admin' ...

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ... route index dan update yang lama ...
    Route::get('/', [AdminController::class, 'index'])->name('index'); 
    Route::patch('/transaction/{transaction}', [AdminController::class, 'updateStatus'])->name('transaction.update');
    
    // 👇 TAMBAHKAN INI (Route Export)
    Route::get('/export', [AdminController::class, 'exportLaporan'])->name('export');
});

// Cari bagian Route Admin (paling bawah file biasanya)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Route Dashboard Admin (yg sudah ada)
    Route::get('/', [AdminController::class, 'index'])->name('index'); 
    
    // Route Update Status (yg sudah ada)
    Route::patch('/transaction/{transaction}', [AdminController::class, 'updateStatus'])->name('transaction.update');

    // 👇 TAMBAHKAN INI (Route Export Excel)
    Route::get('/export', [AdminController::class, 'exportLaporan'])->name('export');
});