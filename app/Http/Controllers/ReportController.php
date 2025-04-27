<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Set default date range to current month if not specified
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $period = $request->period ?? 'monthly'; // Default to monthly view

        // Get categories for filter
        $categories = Category::where('user_id', auth()->id())->get();

        // Build transaction query
        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        // Apply category filter if specified
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Get transactions
        $transactions = $query->orderBy('date', 'desc')->get();

        // Calculate summary
        $summary = (object) [
            'total_income' => $transactions->where('type', 'Pemasukan')->sum('amount'),
            'total_expense' => $transactions->where('type', 'Pengeluaran')->sum('amount'),
            'income_count' => $transactions->where('type', 'Pemasukan')->count(),
            'expense_count' => $transactions->where('type', 'Pengeluaran')->count(),
            'net_balance' => $transactions->where('type', 'Pemasukan')->sum('amount') - 
                           $transactions->where('type', 'Pengeluaran')->sum('amount')
        ];

        // Get category summary
        $categorySummary = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(CASE WHEN transactions.type = "Pemasukan" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN transactions.type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense')
            )
            ->get();

        // Get trend data based on selected period
        $trendQuery = Transaction::where('user_id', auth()->id());

        switch ($period) {
            case 'daily':
                $trendQuery->where('date', '>=', now()->subDays(30))
                    ->selectRaw('DATE(date) as period')
                    ->groupBy('date');
                break;
            case 'weekly':
                $trendQuery->where('date', '>=', now()->subWeeks(24))
                    ->selectRaw('YEARWEEK(date) as period')
                    ->groupBy(DB::raw('YEARWEEK(date)'));
                break;
            case 'yearly':
                $trendQuery->where('date', '>=', now()->subYears(5))
                    ->selectRaw('YEAR(date) as period')
                    ->groupBy(DB::raw('YEAR(date)'));
                break;
            default: // monthly
                $trendQuery->where('date', '>=', now()->subMonths(6))
                    ->selectRaw('DATE_FORMAT(date, "%Y-%m") as period')
                    ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'));
        }

        $trends = $trendQuery->selectRaw('
                SUM(CASE WHEN type = "Pemasukan" THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = "Pengeluaran" THEN amount ELSE 0 END) as expense
            ')
            ->orderBy('period')
            ->get()
            ->map(function ($trend) use ($period) {
                switch ($period) {
                    case 'daily':
                        $date = Carbon::parse($trend->period);
                        $trend->label = $date->format('d/m/Y');
                        break;
                    case 'weekly':
                        $year = substr($trend->period, 0, 4);
                        $week = substr($trend->period, 4);
                        $date = Carbon::now()->setISODate($year, $week);
                        $trend->label = 'Minggu ' . $week . ' ' . $year;
                        break;
                    case 'yearly':
                        $trend->label = $trend->period;
                        break;
                    default: // monthly
                        $date = Carbon::parse($trend->period . '-01');
                        $trend->label = $date->format('M Y');
                }
                return $trend;
            });

        return view('reports.index', compact(
            'transactions',
            'categories',
            'summary',
            'categorySummary',
            'startDate',
            'endDate',
            'period',
            'trends'
        ));
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Get data similar to index method
        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $summary = (object) [
            'total_income' => $transactions->where('type', 'Pemasukan')->sum('amount'),
            'total_expense' => $transactions->where('type', 'Pengeluaran')->sum('amount'),
            'net_balance' => $transactions->where('type', 'Pemasukan')->sum('amount') - 
                           $transactions->where('type', 'Pengeluaran')->sum('amount')
        ];

        $categorySummary = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(CASE WHEN transactions.type = "Pemasukan" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN transactions.type = "Pengeluaran" THEN amount ELSE 0 END) as total_expense')
            )
            ->get();

        $pdf = PDF::loadView('reports.pdf', compact(
            'transactions',
            'summary',
            'categorySummary',
            'startDate',
            'endDate'
        ));

        return $pdf->download('laporan-keuangan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Get data similar to index method
        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        // Prepare CSV headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=laporan-keuangan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Create the CSV
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, ['Tanggal', 'Deskripsi', 'Kategori', 'Tipe', 'Jumlah']);
            
            // Add data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('d/m/Y'),
                    $transaction->description,
                    $transaction->category->name,
                    $transaction->type,
                    $transaction->amount
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
