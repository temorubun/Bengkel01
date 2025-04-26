<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        // Get available months for dropdown
        $months = Transaction::selectRaw('DISTINCT MONTH(date) as month, YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'value' => $date->format('Y-m'),
                    'label' => $date->format('F Y')
                ];
            });

        // Get transactions for selected month
        $transactions = Transaction::whereMonth('date', substr($month, 5, 2))
            ->whereYear('date', substr($month, 0, 4))
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $transactions->where('type', 'Pemasukan')->sum('amount');
        $totalExpense = $transactions->where('type', 'Pengeluaran')->sum('amount');
        $totalIncomeTransactions = $transactions->where('type', 'Pemasukan')->count();
        $totalExpenseTransactions = $transactions->where('type', 'Pengeluaran')->count();

        return view('reports.index', compact(
            'months',
            'transactions',
            'totalIncome',
            'totalExpense',
            'totalIncomeTransactions',
            'totalExpenseTransactions'
        ));
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $export = new TransactionsExport($month);
        $filename = $export->export();

        return response()->download(
            storage_path('app/public/' . $filename),
            $filename,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ]
        )->deleteFileAfterSend();
    }

    public function exportPDF(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        $transactions = Transaction::whereMonth('date', substr($month, 5, 2))
            ->whereYear('date', substr($month, 0, 4))
            ->orderBy('date', 'desc')
            ->get();

        $pdf = PDF::loadView('reports.pdf', compact('transactions', 'month'));
        
        return $pdf->download('transactions_' . $month . '.pdf');
    }
}
