<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 20px;
            color: #1F2937;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #E5E7EB;
        }
        .header h1 {
            color: #111827;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #6B7280;
            margin: 0;
        }
        .summary {
            margin-bottom: 40px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
        }
        .summary-box h4 {
            color: #6B7280;
            font-size: 14px;
            margin: 0 0 10px 0;
        }
        .summary-box .amount {
            font-size: 20px;
            font-weight: bold;
        }
        .income { color: #059669; }
        .expense { color: #DC2626; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        th {
            background-color: #F9FAFB;
            color: #6B7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #E5E7EB;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
        }
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        .type-badge.income {
            background-color: #D1FAE5;
            color: #059669;
        }
        .type-badge.expense {
            background-color: #FEE2E2;
            color: #DC2626;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-box">
                <h4>Total Pemasukan</h4>
                <div class="amount income">
                    Rp {{ number_format($transactions->where('type', 'Pemasukan')->sum('amount'), 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-box">
                <h4>Total Pengeluaran</h4>
                <div class="amount expense">
                    Rp {{ number_format($transactions->where('type', 'Pengeluaran')->sum('amount'), 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-box">
                <h4>Saldo</h4>
                <div class="amount {{ ($transactions->where('type', 'Pemasukan')->sum('amount') - $transactions->where('type', 'Pengeluaran')->sum('amount')) >= 0 ? 'income' : 'expense' }}">
                    Rp {{ number_format($transactions->where('type', 'Pemasukan')->sum('amount') - $transactions->where('type', 'Pengeluaran')->sum('amount'), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th style="text-align: right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date->format('d M Y') }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->category }}</td>
                    <td>
                        <span class="type-badge {{ strtolower($transaction->type) }}">
                            {{ $transaction->type }}
                        </span>
                    </td>
                    <td style="text-align: right" class="{{ $transaction->type == 'Pemasukan' ? 'income' : 'expense' }}">
                        {{ $transaction->type == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #6B7280;">
                        Tidak ada transaksi untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 