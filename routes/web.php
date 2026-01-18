<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
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
Route::post('/tracking/paid/{id}', [LaundryController::class, 'markAsPaid'])->name('transaction.paid');


// === RUTE DASHBOARD (LOGIKA PEMISAH) ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    // 1. Kalau ADMIN, lempar ke halaman admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.index');
    }

    // 2. Kalau USER BIASA, ambil data pesanannya dia saja
    $transactions = App\Models\Transaction::with(['service', 'review'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

    return view('dashboard', compact('transactions'));
})->middleware(['auth', 'verified'])->name('dashboard');

// === RUTE ALAMAT (Profile) ===
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

// === RUTE NOTIFIKASI ===
Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

// === RUTE REVIEW ===
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::middleware('auth')->group(function () {
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});

// === RUTE VOUCHER (Check) ===
Route::post('/voucher/check', [VoucherController::class, 'check'])->name('voucher.check');
Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');

// === RUTE KHUSUS ADMIN (Dilindungi Middleware) ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Utama Admin (Memanggil index di controller)
    Route::get('/', [AdminController::class, 'index'])->name('index'); 
    Route::get('/export', [AdminController::class, 'exportLaporan'])->name('export');
    
    // Fitur Update Status (Patch)
    Route::patch('/transaction/{transaction}', [AdminController::class, 'updateStatus'])->name('transaction.update');
    
    // Fitur Update Pembayaran (Patch)
    Route::patch('/transaction/{transaction}/payment', [AdminController::class, 'updatePaymentStatus'])->name('transaction.payment');
    
    // Kelola Voucher
    Route::get('/vouchers', [AdminController::class, 'vouchers'])->name('vouchers.index');
    Route::post('/vouchers', [AdminController::class, 'storeVoucher'])->name('vouchers.store');
    Route::put('/vouchers/{id}', [AdminController::class, 'updateVoucher'])->name('vouchers.update');
    Route::delete('/vouchers/{id}', [AdminController::class, 'destroyVoucher'])->name('vouchers.destroy');
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