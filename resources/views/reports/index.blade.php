@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Laporan Keuangan</h1>
        <div class="flex items-center gap-2">
            <select id="month" class="border rounded-lg px-4 py-2">
                @foreach($months as $month)
                    <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                @endforeach
            </select>
            <button onclick="exportReport('pdf')" class="bg-red-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-red-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </button>
            <button onclick="exportReport('excel')" class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pemasukan</h3>
            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</div>
            <div class="text-sm text-gray-500">{{ $totalIncomeTransactions ?? 0 }} transaksi</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pengeluaran</h3>
            <div class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</div>
            <div class="text-sm text-gray-500">{{ $totalExpenseTransactions ?? 0 }} transaksi</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Saldo Akhir</h3>
            <div class="text-2xl font-bold {{ ($totalIncome - $totalExpense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                -Rp {{ number_format(abs(($totalIncome ?? 0) - ($totalExpense ?? 0)), 0, ',', '.') }}
            </div>
            <div class="text-sm text-gray-500">Per {{ now()->format('d F Y') }}</div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold">Rincian Transaksi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions ?? [] as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 rounded-full {{ $transaction->type == 'Pemasukan' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $transaction->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Catatan Laporan</h3>
    </div>

    <script>
        function exportReport(type) {
            const month = document.getElementById('month').value;
            const url = type === 'excel' 
                ? "{{ route('reports.export-excel') }}"
                : "{{ route('reports.export-pdf') }}";
            
            window.location.href = `${url}?month=${month}`;
        }
    </script>
@endsection 