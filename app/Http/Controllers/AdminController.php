<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Statistik
        // Total transaksi yang valid (bukan cancelled)
        $totalTransactions = Transaction::where('status', '!=', 'cancelled')->count();
        
        // PENTING: Pendapatan cuma dihitung kalau status bayar = '2' (Lunas)
        $income = Transaction::where('payment_status', '2')->sum('total_price');
        
        // Pesanan pending (belum diproses)
        $pendingOrders = Transaction::where('status', 'pending')
                                    ->where('payment_status', '!=', '3') // Bukan expired/cancelled
                                    ->count();
                                    
        $totalCustomers = User::where('role', 'user')->count();

        // 2. Ambil SEMUA Transaksi Terbaru (termasuk yg belum bayar, kecuali cancelled)
        $recentTransactions = Transaction::with(['user', 'service'])
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        // 3. Kirim ke View
        return view('admin.index', compact(
            'totalTransactions',
            'income',
            'pendingOrders',
            'totalCustomers',
            'recentTransactions'
        ));
    }

    // FUNGSI UPDATE STATUS PESANAN (Cucian: Pending -> Process -> Done)
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // Validasi
        $request->validate([
            'status' => 'required|in:pending,process,done,taken'
        ]);

        // Update database
        $transaction->update([
            'status' => $request->status
        ]);

        // Kirim notifikasi ke customer jika ada user_id
        if ($transaction->user_id) {
            $user = \App\Models\User::find($transaction->user_id);
            if ($user) {
                $statusMessages = [
                    'pending' => ['title' => '⏳ Pesanan Menunggu', 'message' => 'Pesanan Anda sedang menunggu diproses'],
                    'process' => ['title' => '🧼 Sedang Dicuci', 'message' => 'Cucian Anda sedang dalam proses pencucian'],
                    'done' => ['title' => '✅ Selesai', 'message' => 'Cucian Anda sudah selesai! Silakan diambil'],
                    'taken' => ['title' => '👋 Sudah Diambil', 'message' => 'Cucian sudah diambil. Terima kasih!'],
                ];
                
                $msg = $statusMessages[$request->status];
                $user->notify(new \App\Notifications\StatusNotification($transaction, $msg['title'], $msg['message']));
            }
        }

        // Balik lagi ke halaman admin
        return redirect()->back()->with('success', 'Status cucian berhasil diperbarui!');
    }

    // FUNGSI UPDATE STATUS PEMBAYARAN (Khusus Cash: Belum Bayar -> Lunas)
    public function updatePaymentStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_status' => 'required|in:1,2' // 1: Belum Bayar, 2: Lunas
        ]);

        $transaction->update([
            'payment_status' => $request->payment_status
        ]);

        // Kirim notifikasi jika Lunas
        if ($request->payment_status == '2' && $transaction->user_id) {
            $user = \App\Models\User::find($transaction->user_id);
            if ($user) {
                $user->notify(new \App\Notifications\StatusNotification(
                    $transaction,
                    '💰 Pembayaran Diterima',
                    'Pembayaran tunai Anda telah dikonfirmasi oleh Admin.'
                ));
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    // ... function index dan updateStatus ada di atas ...

    public function exportLaporan()
    {
        // 1. Ambil data yang statusnya LUNAS (payment_status = 2)
        // Kalau mau semua data, hapus bagian ->where(...)
        $transactions = \App\Models\Transaction::with('service')
                        ->where('payment_status', '2') 
                        ->latest()
                        ->get();

        // 2. Siapkan nama file
        $filename = "Laporan-Keuangan-" . date('Y-m-d-H-i-s') . ".xls";

        // 3. Header Ajaib (Biar browser download sebagai Excel)
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // 4. Tampilkan View Khusus Excel
        return view('admin.export', compact('transactions'));
    }

    // === VOUCHER MANAGEMENT ===
    
    public function vouchers()
    {
        $vouchers = \App\Models\Voucher::latest()->get();
        return view('admin.vouchers', compact('vouchers'));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        \App\Models\Voucher::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order' => $request->min_order ?? 0,
            'max_discount' => $request->max_discount ?? null,
            'quota' => $request->quota ?? null,
            'used_count' => 0,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Voucher berhasil ditambahkan!');
    }

    public function updateVoucher(Request $request, $id)
    {
        $voucher = \App\Models\Voucher::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order' => $request->min_order ?? 0,
            'max_discount' => $request->max_discount ?? null,
            'quota' => $request->quota ?? null,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroyVoucher($id)
    {
        $voucher = \App\Models\Voucher::findOrFail($id);
        $voucher->delete();
        
        return redirect()->back()->with('success', 'Voucher berhasil dihapus!');
    }
}