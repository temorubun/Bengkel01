<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('category')
            ->where('user_id', auth()->id());

        // Filter by type
        if ($request->type && $request->type !== 'Semua') {
            $query->where('type', $request->type);
        }

        // Filter by date
        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        $categories = Category::where('user_id', auth()->id())->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:Pemasukan,Pengeluaran',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_type' => 'required_if:is_recurring,1|in:weekly,monthly',
            'next_transaction_date' => 'required_if:is_recurring,1|date|after:date',
        ]);

        // Verify category belongs to user
        $category = Category::findOrFail($request->category_id);
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // Buat array data yang akan disimpan
        $data = [
            'date' => $validated['date'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
            'user_id' => auth()->id(),
            'is_recurring' => $request->boolean('is_recurring'),
        ];

        // Tambahkan data transaksi berulang jika diaktifkan
        if ($request->boolean('is_recurring')) {
            $data['recurring_type'] = $validated['recurring_type'];
            $data['next_transaction_date'] = $validated['next_transaction_date'];
            $data['reminder_sent'] = false;
        }

        Transaction::create($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::where('user_id', auth()->id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:Pemasukan,Pengeluaran',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_type' => 'required_if:is_recurring,1|in:weekly,monthly',
            'next_transaction_date' => 'required_if:is_recurring,1|date|after:date',
        ]);

        // Verify category belongs to user
        $category = Category::findOrFail($request->category_id);
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        // Buat array data yang akan diupdate
        $data = [
            'date' => $validated['date'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
            'is_recurring' => $request->boolean('is_recurring'),
        ];

        // Update data transaksi berulang
        if ($request->boolean('is_recurring')) {
            $data['recurring_type'] = $validated['recurring_type'];
            $data['next_transaction_date'] = $validated['next_transaction_date'];
            $data['reminder_sent'] = false;
        } else {
            $data['recurring_type'] = null;
            $data['next_transaction_date'] = null;
            $data['reminder_sent'] = false;
        }

        $transaction->update($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
} 