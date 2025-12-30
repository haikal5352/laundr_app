<?php

namespace App\Http\Controllers;

// === IMPORT LIBRARY (HARUS DI ATAS SINI) ===
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // <--- INI POSISI YANG BENAR

class LaundryController extends Controller
{
    // === 1. HALAMAN UTAMA ===
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            }
        }

        $services = Service::all();
        return view('laundry.index', compact('services'));
    }

    // === 2. SIMPAN PESANAN BARU ===
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'service_id' => 'required',
            'qty' => 'required|numeric',
            'type' => 'required|in:dropoff,pickup_delivery',
            'address' => 'required_if:type,pickup_delivery',
        ]);

        $service = Service::find($request->service_id);
        $total_price = $service->price * $request->qty;

        // B. Buat Transaksi di Database
        $transaction = Transaction::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_id' => $request->service_id,
            'user_id' => Auth::id(),
            'qty' => $request->qty,
            'total_price' => $total_price,
            'type' => $request->type,
            'address' => $request->address,
            'payment_method' => $request->payment_method ?? 'qris',
            'status' => 'pending',
            'payment_status' => '1',
        ]);

        // C. Konfigurasi Midtrans
        $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        $serverKey = trim($serverKey); 

        Config::$serverKey = $serverKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Bypass SSL & Paksa Notifikasi ke Ngrok
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERPWD => $serverKey . ':',
            CURLOPT_HTTPHEADER => [ 
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Override-Notification: https://elida-apheretic-maegan.ngrok-free.dev/api/midtrans-callback', 
            ]
        ]; 

        // D. Request Snap Token
        $midtransParams = [
            'transaction_details' => [
                'order_id' => 'TRX-' . $transaction->id . '-' . time(),
                'gross_amount' => (int) $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($midtransParams);
            $transaction->snap_token = $snapToken;
            $transaction->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }

        return redirect()->route('tracking', ['id' => $transaction->id])
            ->with('success', 'Pesanan berhasil! Silakan bayar sekarang.');
    }

    // === 3. HALAMAN TRACKING ===
    public function tracking(Request $request)
    {
        $transaction = null;
        if ($request->has('id')) {
            $transaction = Transaction::with('service')->find($request->id);
        }

        return view('tracking', compact('transaction'));
    }

    // === 4. FITUR BATALKAN PESANAN (User) ===
    public function cancelOrder($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction || $transaction->status != 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        $transaction->update([
            'status' => 'cancelled', 
            'payment_status' => '3' 
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // === 5. FITUR DOWNLOAD INVOICE PDF ===
    public function downloadInvoice($id)
    {
        $transaction = Transaction::with('service')->find($id);
        
        // Validasi: Cuma boleh print kalau data ada & sudah lunas
        if (!$transaction || $transaction->payment_status != '2') {
            return redirect()->back()->with('error', 'Invoice belum tersedia (Wajib Lunas).');
        }

        // Load view PDF
        $pdf = Pdf::loadView('pdf.invoice', compact('transaction'));
        
        // Download file
        return $pdf->download('Invoice-Laundry-'.$transaction->id.'.pdf');
    }

    // === 6. CALLBACK (Jalur Belakang Midtrans) ===
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if($hashed == $request->signature_key){
            
            $orderIdParts = explode('-', $request->order_id);
            $transactionId = $orderIdParts[1];
            $transaction = Transaction::find($transactionId);

            if($transaction){
                
                if($transaction->status == 'cancelled') {
                    return response()->json(['message' => 'Order was cancelled by user'], 200);
                }

                if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement'){
                    $transaction->update(['payment_status' => '2']); // Lunas
                }
                elseif($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny'){
                    $transaction->update(['payment_status' => '3', 'status' => 'cancelled']); 
                }
            }
        }
    }
}