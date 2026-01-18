<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Public voucher listing page
    public function index()
    {
        $vouchers = Voucher::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function($q) {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->latest()
            ->get();
        
        return view('vouchers.index', compact('vouchers'));
    }

    // Check voucher validity (AJAX)
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak ditemukan'
            ]);
        }

        $check = $voucher->isValid($request->total);
        
        if (!$check['valid']) {
            return response()->json($check);
        }

        $discount = $voucher->calculateDiscount($request->total);

        return response()->json([
            'valid' => true,
            'message' => 'Voucher berhasil digunakan!',
            'discount' => $discount,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'final_total' => $request->total - $discount,
        ]);
    }
}
