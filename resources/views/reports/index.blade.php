@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
            <!-- Period Buttons -->
            <div class="grid grid-cols-4 gap-4">
                <button type="submit" name="period" value="daily" 
                    class="px-4 py-2 rounded-lg {{ $period === 'daily' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Harian
                </button>
                <button type="submit" name="period" value="weekly" 
                    class="px-4 py-2 rounded-lg {{ $period === 'weekly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-calendar-week mr-2"></i>
                    Mingguan
                </button>
                <button type="submit" name="period" value="monthly" 
                    class="px-4 py-2 rounded-lg {{ $period === 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Bulanan
                </button>
                <button type="submit" name="period" value="yearly" 
                    class="px-4 py-2 rounded-lg {{ $period === 'yearly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-calendar mr-2"></i>
                    Tahunan
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <div class="flex gap-2">
                    <a href="{{ route('reports.export-pdf', request()->all()) }}" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('reports.export-excel', request()->all()) }}" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Total Pemasukan</h3>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-plus text-xl"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary->total_income ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $summary->income_count ?? 0 }} transaksi</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Total Pengeluaran</h3>
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-minus text-xl"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($summary->total_expense ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $summary->expense_count ?? 0 }} transaksi</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Saldo Akhir</h3>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-balance-scale text-xl"></i>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $summary->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(abs($summary->net_balance), 0, ',', '.') }}
                {{ $summary->net_balance >= 0 ? '(Surplus)' : '(Defisit)' }}
            </p>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Tren {{ ucfirst($period) === 'Daily' ? 'Harian' : 
                   (ucfirst($period) === 'Weekly' ? 'Mingguan' : 
                   (ucfirst($period) === 'Monthly' ? 'Bulanan' : 'Tahunan')) }}
        </h2>
        <div class="chart-container">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Category Summary -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan per Kategori</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categorySummary as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->category_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">
                            Rp {{ number_format($category->total_income, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                            Rp {{ number_format($category->total_expense, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ ($category->total_income - $category->total_expense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($category->total_income - $category->total_expense), 0, ',', '.') }}
                            {{ ($category->total_income - $category->total_expense) >= 0 ? '(+)' : '(-)' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $transaction->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $transaction->category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'Pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaction->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $transaction->type === 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            Tidak ada transaksi untuk periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date pickers
    flatpickr("input[type=date]", {
        dateFormat: "Y-m-d"
    });

    // Prepare trend chart data
    const trends = @json($trends);
    const labels = trends.map(trend => trend.label);
    const incomeData = trends.map(trend => trend.income);
    const expenseData = trends.map(trend => trend.expense);

    // Create trend chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pemasukan',
                data: incomeData,
                borderColor: '#059669',
                backgroundColor: '#059669',
                tension: 0.4
            }, {
                label: 'Pengeluaran',
                data: expenseData,
                borderColor: '#DC2626',
                backgroundColor: '#DC2626',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 