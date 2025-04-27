<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1a56db;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .summary-card {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            color: #374151;
        }
        .amount {
            font-size: 20px;
            font-weight: bold;
        }
        .income {
            color: #059669;
        }
        .expense {
            color: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        <p>Dibuat pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <h2>Ringkasan</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Total Pemasukan</h3>
                <div class="amount income">Rp {{ number_format($summary->total_income, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <h3>Total Pengeluaran</h3>
                <div class="amount expense">Rp {{ number_format($summary->total_expense, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <h3>Saldo Akhir</h3>
                <div class="amount {{ $summary->net_balance >= 0 ? 'income' : 'expense' }}">
                    Rp {{ number_format($summary->net_balance, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="category-summary">
        <h2>Ringkasan per Kategori</h2>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="text-right">Pemasukan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorySummary as $summary)
                    <tr>
                        <td>{{ $summary->category_name }}</td>
                        <td class="text-right income">Rp {{ number_format($summary->total_income, 0, ',', '.') }}</td>
                        <td class="text-right expense">Rp {{ number_format($summary->total_expense, 0, ',', '.') }}</td>
                        <td class="text-right {{ ($summary->total_income - $summary->total_expense) >= 0 ? 'income' : 'expense' }}">
                            Rp {{ number_format(($summary->total_income - $summary->total_expense), 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="transactions">
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->date->format('d/m/Y') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->category->name }}</td>
                        <td>{{ $transaction->type }}</td>
                        <td class="text-right {{ $transaction->type == 'Pemasukan' ? 'income' : 'expense' }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 