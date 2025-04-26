<?php

namespace App\Exports;

use App\Models\Transaction;
use League\Csv\Writer;

class TransactionsExport
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function export()
    {
        $transactions = Transaction::whereMonth('date', substr($this->month, 5, 2))
            ->whereYear('date', substr($this->month, 0, 4))
            ->orderBy('date', 'desc')
            ->get();

        // Generate filename and path
        $filename = 'transactions_' . $this->month . '.csv';
        $path = storage_path('app/public/' . $filename);

        // Create CSV writer
        $csv = Writer::createFromPath($path, 'w+');
        $csv->setDelimiter(',');
        
        // Optional: Set UTF-8 BOM for Excel
        $csv->setOutputBOM(Writer::BOM_UTF8);

        // Set CSV header
        $csv->insertOne([
            'Tanggal',
            'Deskripsi',
            'Kategori',
            'Tipe',
            'Jumlah'
        ]);

        // Add transactions
        foreach ($transactions as $transaction) {
            $csv->insertOne([
                $transaction->date->format('d/m/Y'),
                $transaction->description,
                $transaction->category,
                $transaction->type,
                number_format($transaction->amount, 0, ',', '.')
            ]);
        }

        return $filename;
    }
} 