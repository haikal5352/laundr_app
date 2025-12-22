<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('laundry.index', compact('services'));
    }

    public function store(Request $request)
    {
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

        $transaction = Transaction::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_id' => $request->service_id,
            'user_id' => auth()->id(), // Nullable if guest
            'qty' => $request->qty,
            'total_price' => $total_price,
            'type' => $request->type,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        return redirect()->route('tracking', ['id' => $transaction->id])->with('success', 'Order placed successfully! Your Transaction ID: ' . $transaction->id);
    }

    public function tracking(Request $request)
    {
        $transaction = null;
        if ($request->has('id')) {
            $transaction = Transaction::with('service')->find($request->id);
        }

        return view('tracking', compact('transaction'));
    }
}
