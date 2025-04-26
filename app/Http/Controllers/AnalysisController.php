<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $now = Carbon::now();

        // Get total income and expense
        $totals = Transaction::where('user_id', $userId)
            ->whereMonth('date', $now->month)
            ->select(
                DB::raw('SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        // Get daily balance trend
        $dailyBalances = Transaction::where('user_id', $userId)
            ->whereMonth('date', $now->month)
            ->select(
                'date',
                DB::raw('SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE -amount END) as daily_balance')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'balance' => $item->daily_balance
                ];
            });

        // Get spending by category
        $spendingByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'Pengeluaran')
            ->whereMonth('date', $now->month)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Get monthly trend by category
        $monthlyTrendByCategory = Transaction::where('user_id', $userId)
            ->whereYear('date', $now->year)
            ->select(
                DB::raw('MONTH(date) as month'),
                'category',
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month', 'category', 'type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        return view('analysis.index', compact(
            'totals',
            'dailyBalances',
            'spendingByCategory',
            'monthlyTrendByCategory'
        ));
    }
}
