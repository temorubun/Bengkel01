<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        // Get current month's data
        $currentMonth = Transaction::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('
                SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense,
                COUNT(CASE WHEN type = "Pemasukan" THEN 1 END) as income_count,
                COUNT(CASE WHEN type = "Pengeluaran" THEN 1 END) as expense_count
            ')
            ->first();

        // Get yearly summary
        $yearlyData = Transaction::where('user_id', auth()->id())
            ->whereYear('date', now()->year)
            ->selectRaw('
                SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense
            ')
            ->first();

        // Get expense by category for current month
        $expenseByCategory = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->where('transactions.type', 'Pengeluaran')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name',
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->orderBy('total_amount', 'desc')
            ->get();

        // Get monthly trends for the last 6 months
        $monthlyTrends = Transaction::where('user_id', auth()->id())
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('YEAR(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as expense')
            )
            ->get();

        // Get recent transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate daily averages
        $daysInMonth = now()->daysInMonth;
        $dailyAvgIncome = $currentMonth->total_income / $daysInMonth;
        $dailyAvgExpense = $currentMonth->total_expense / $daysInMonth;

        return view('dashboard', compact(
            'currentMonth',
            'yearlyData',
            'expenseByCategory',
            'monthlyTrends',
            'recentTransactions',
            'dailyAvgIncome',
            'dailyAvgExpense'
        ));
    }
} 