<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Public reviews listing
    public function index()
    {
        $reviews = Review::with(['user', 'transaction.service'])
            ->latest()
            ->paginate(10);
        
        // Get user's eligible transactions (taken status, not yet reviewed)
        $eligibleTransactions = collect();
        if (Auth::check()) {
            $eligibleTransactions = Transaction::with('service')
                ->where('user_id', Auth::id())
                ->where('status', 'taken')
                ->whereDoesntHave('review')
                ->latest()
                ->get();
        }
        
        return view('reviews.index', compact('reviews', 'eligibleTransactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if already reviewed
        $existing = Review::where('transaction_id', $request->transaction_id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk pesanan ini');
        }

        // Check if transaction belongs to user
        $transaction = Transaction::findOrFail($request->transaction_id);
        if ($transaction->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk review ini');
        }

        Review::create([
            'transaction_id' => $request->transaction_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas review Anda! ⭐');
    }
}
