<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'service'])->latest()->paginate(10);
        return view('admin.index', compact('transactions'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,process,done,taken'
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully!');
    }
}
