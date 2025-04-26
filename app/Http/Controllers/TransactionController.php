<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query()->where('user_id', auth()->id());

        // Filter by type
        if ($request->type && $request->type !== 'Semua') {
            $query->where('type', $request->type);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        // Filter by category
        if ($request->category && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        $categories = Transaction::distinct()->pluck('category');

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        return response()->json($transaction);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|in:Pemasukan,Pengeluaran',
            'amount' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|in:Pemasukan,Pengeluaran',
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }
} 