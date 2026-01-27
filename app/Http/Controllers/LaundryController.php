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
        $addresses = Auth::check() ? Auth::user()->addresses()->latest()->get() : collect();
        
        return view('laundry.index', compact('services', 'addresses'));
    }

    // === 2. SIMPAN PESANAN BARU (SINGLE TRANSACTION, MULTIPLE ITEMS) ===
    public function store(Request $request)
    {
        // A. Parse items dari JSON
        $items = json_decode($request->items_json, true);
        
        if (!$items || !is_array($items) || count($items) == 0) {
            return redirect()->back()->with('error', 'Pilih minimal 1 layanan!');
        }

        // B. Validasi Input
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'type' => 'required|in:dropoff,pickup_delivery',
            'address' => 'required_if:type,pickup_delivery',
        ]);

        // C. Hitung total dan siapkan data items
        $grandTotal = 0;
        $itemsData = [];
        $totalQty = 0;
        $firstServiceId = null;
        
        foreach ($items as $item) {
            $service = Service::find($item['service_id']);
            if (!$service) continue;
            
            $subtotal = $service->price * $item['qty'];
            $grandTotal += $subtotal;
            $totalQty += $item['qty'];
            
            if ($firstServiceId === null) {
                $firstServiceId = $service->id;
            }
            
            $itemsData[] = [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'price' => $service->price,
                'qty' => $item['qty'],
                'subtotal' => $subtotal,
            ];
        }

        // Handle Voucher Logic
        $voucherCode = null;
        $discountAmount = 0;

        if ($request->voucher_code) {
            $voucher = \App\Models\Voucher::where('code', strtoupper($request->voucher_code))->first();
            if ($voucher) {
                $check = $voucher->isValid($grandTotal);
                if ($check['valid']) {
                    $discountAmount = $voucher->calculateDiscount($grandTotal);
                    $grandTotal -= $discountAmount;
                    $voucherCode = $voucher->code;
                    $voucher->increment('used_count'); // Increment usage
                }
            }
        }

        // D. Buat 1 Transaksi dengan total gabungan
        $transaction = Transaction::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_id' => $firstServiceId, // Untuk backward compatibility
            'user_id' => Auth::id(),
            'qty' => $totalQty,
            'total_price' => $grandTotal,
            'type' => $request->type,
            'address' => $request->address,
            'payment_method' => $request->payment_method ?? 'epayment',
            'status' => 'pending',
            'payment_status' => '1', // Belum bayar
            'items_data' => json_encode($itemsData),
            'estimated_done_at' => now()->addHours(24), // Default estimasi 24 jam
            'voucher_code' => $voucherCode,
            'discount_amount' => $discountAmount,
        ]);

        // E. Midtrans HANYA untuk E-Payment, bukan Cash
        if ($request->payment_method !== 'cash') {
            $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
            $serverKey = trim($serverKey); 

            Config::$serverKey = $serverKey;
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;
            
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_USERPWD => $serverKey . ':',
                CURLOPT_HTTPHEADER => [ 
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]
            ]; 

            // Siapkan Item Details untuk Midtrans
            $midtransItems = array_map(function($item) {
                return [
                    'id' => 'SVC-' . $item['service_id'],
                    'name' => $item['service_name'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['qty'],
                ];
            }, $itemsData);

            // Jika ada voucher, masukkan sebagai item negatif (Diskon)
            if ($discountAmount > 0) {
                $midtransItems[] = [
                    'id' => 'DISC-' . ($voucherCode ?? 'PROMO'),
                    'name' => 'Voucher Diskon (' . $voucherCode . ')',
                    'price' => - (int) $discountAmount,
                    'quantity' => 1,
                ];
            }

            // Request Snap Token untuk TOTAL keseluruhan
            $midtransParams = [
                'transaction_details' => [
                    'order_id' => 'TRX-' . $transaction->id . '-' . time(),
                    'gross_amount' => (int) $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                ],
                'item_details' => $midtransItems,
            ];

            try {
                $snapToken = Snap::getSnapToken($midtransParams);
                $transaction->snap_token = $snapToken;
                $transaction->save();
            } catch (\Exception $e) {
                \Log::error('Midtrans Error: ' . $e->getMessage());
            }
        }

        $itemCount = count($items);
        $message = $itemCount > 1 
            ? "Pesanan dengan {$itemCount} layanan berhasil dibuat!" 
            : 'Pesanan berhasil! Silakan bayar sekarang.';

        return redirect()->route('tracking', ['id' => $transaction->id])
            ->with('success', $message);
    }

    // === 3. HALAMAN TRACKING ===
    public function tracking(Request $request)
    {
        $transaction = null;
        if ($request->has('id')) {
            $transaction = Transaction::with(['service', 'review'])->find($request->id);
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
                    
                    // Notifikasi Pembayaran Berhasil
                    if ($transaction->user_id) {
                        $user = \App\Models\User::find($transaction->user_id);
                        if ($user) {
                            $user->notify(new \App\Notifications\StatusNotification(
                                $transaction, 
                                '💰 Pembayaran Berhasil', 
                                'Terima kasih! Pembayaran Anda telah kami terima.'
                            ));
                        }
                    }
                }
                elseif($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny'){
                    $transaction->update(['payment_status' => '3', 'status' => 'cancelled']); 
                }
            }
        }
    }

    // === 7. MARK PAYMENT AS PAID (Client-side trigger) ===
    public function markAsPaid($id)
    {
        $transaction = Transaction::find($id);
        
        if ($transaction && $transaction->payment_status == '1') {
            $transaction->update(['payment_status' => '2']); // Lunas
            
            // Notifikasi Pembayaran Berhasil
            if ($transaction->user_id) {
                $user = \App\Models\User::find($transaction->user_id);
                if ($user) {
                    $user->notify(new \App\Notifications\StatusNotification(
                        $transaction, 
                        '💰 Pembayaran Berhasil', 
                        'Terima kasih! Pembayaran Anda telah kami terima.'
                    ));
                }
            }

            return redirect()->route('tracking', ['id' => $id])
                ->with('success', 'Pembayaran berhasil! Terima kasih.');
        }
        
        return redirect()->route('tracking', ['id' => $id]);
    }
}