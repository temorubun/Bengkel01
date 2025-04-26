<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get monthly totals
        $monthlyTotals = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        // Get previous month totals for comparison
        $previousMonthTotals = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->select(
                DB::raw('SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        // Calculate percentages
        $incomePercentage = $previousMonthTotals->total_income > 0
            ? (($monthlyTotals->total_income - $previousMonthTotals->total_income) / $previousMonthTotals->total_income) * 100
            : 0;

        $expensePercentage = $previousMonthTotals->total_expense > 0
            ? (($monthlyTotals->total_expense - $previousMonthTotals->total_expense) / $previousMonthTotals->total_expense) * 100
            : 0;

        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Get spending by category
        $spendingByCategory = Transaction::where('user_id', auth()->id())
            ->where('type', 'Pengeluaran')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'monthlyTotals',
            'incomePercentage',
            'expensePercentage',
            'recentTransactions',
            'spendingByCategory'
        ));
    }
} 