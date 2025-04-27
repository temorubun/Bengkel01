<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        // Set periode analisis (default: bulan ini)
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // 1. Ringkasan Keuangan
        $summary = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense,
                (SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) - 
                 SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END)) as net_income
            ')
            ->first();

        // 2. Analisis Kategori
        $categoryAnalysis = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name', 'transactions.type')
            ->select(
                'categories.name',
                'transactions.type',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->get();

        // 3. Tren Bulanan (6 bulan terakhir)
        $monthlyTrends = Transaction::where('user_id', auth()->id())
            ->where('date', '>=', Carbon::now()->subMonths(6))
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

        // 4. Rata-rata Harian
        $dailyAverage = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('type')
            ->select(
                'type',
                DB::raw('AVG(amount) as average_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->get();

        // 5. Top 5 Pengeluaran Terbesar
        $topExpenses = Transaction::where('user_id', auth()->id())
            ->where('type', 'Pengeluaran')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('amount', 'desc')
            ->take(5)
            ->get();

        // 6. Perbandingan dengan Periode Sebelumnya
        $previousPeriodStart = (clone $startDate)->subMonth();
        $previousPeriodEnd = (clone $endDate)->subMonth();

        $comparison = Transaction::where('user_id', auth()->id())
            ->select(
                DB::raw("CASE 
                    WHEN date BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}' THEN 'current'
                    ELSE 'previous'
                END as period"),
                'type',
                DB::raw('SUM(amount) as total_amount')
            )
            ->where(function($query) use ($startDate, $endDate, $previousPeriodStart, $previousPeriodEnd) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->orWhereBetween('date', [$previousPeriodStart, $previousPeriodEnd]);
            })
            ->groupBy('period', 'type')
            ->get();

        return view('analysis.index', compact(
            'summary',
            'categoryAnalysis',
            'monthlyTrends',
            'dailyAverage',
            'topExpenses',
            'comparison',
            'startDate',
            'endDate'
        ));
    }
}
