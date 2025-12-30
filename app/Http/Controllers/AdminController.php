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
        $totalTransactions = Transaction::count();
        
        // PENTING: Pendapatan cuma dihitung kalau status bayar = '2' (Lunas)
        $income = Transaction::where('payment_status', '2')->sum('total_price');
        
        // Pesanan yg perlu dicuci (Status 'pending' TAPI sudah Bayar '2')
        $pendingOrders = Transaction::where('status', 'pending')
                                    ->where('payment_status', '2') 
                                    ->count();
                                    
        $totalCustomers = User::where('role', 'user')->count();

        // 2. Ambil Transaksi Terbaru (HANYA YANG SUDAH BAYAR)
        $recentTransactions = Transaction::with(['user', 'service'])
            ->where('payment_status', '2') // <--- FILTER SAKTI (Cuma tampilkan yang Lunas)
            ->latest()
            ->take(10)
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

        // Balik lagi ke halaman admin
        return redirect()->back()->with('success', 'Status cucian berhasil diperbarui!');
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
}