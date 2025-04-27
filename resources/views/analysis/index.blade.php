@extends('layouts.app')

@section('title', 'Analisis Keuangan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Filter Periode -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('analysis.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" 
                    value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" 
                    value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
        </form>
    </div>

    <!-- Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Total Pemasukan</h3>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary->total_income ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Total Pengeluaran</h3>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($summary->total_expense ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Saldo Bersih</h3>
            <p class="text-2xl font-bold {{ ($summary->net_income ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($summary->net_income ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grafik Tren Bulanan -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Bulanan</h3>
            <canvas id="monthlyTrendChart" class="w-full"></canvas>
        </div>

        <!-- Grafik Kategori -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Kategori</h3>
            <canvas id="categoryChart" class="w-full"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Rata-rata Harian -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rata-rata Harian</h3>
            <div class="space-y-4">
                @foreach($dailyAverage as $avg)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $avg->type }}</span>
                        <div class="text-right">
                            <p class="font-semibold {{ $avg->type == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($avg->average_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $avg->transaction_count }} transaksi</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top 5 Pengeluaran -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Pengeluaran Terbesar</h3>
            <div class="space-y-4">
                @foreach($topExpenses as $expense)
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-800">{{ $expense->description }}</p>
                            <p class="text-sm text-gray-500">{{ $expense->date->format('d/m/Y') }}</p>
                        </div>
                        <span class="font-semibold text-red-600">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Perbandingan dengan Periode Sebelumnya -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan dengan Bulan Sebelumnya</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $currentPeriod = $comparison->where('period', 'current')->pluck('total_amount', 'type')->toArray();
                $previousPeriod = $comparison->where('period', 'previous')->pluck('total_amount', 'type')->toArray();
            @endphp
            
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Pemasukan</h4>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xl font-semibold text-green-600">
                            Rp {{ number_format($currentPeriod['Pemasukan'] ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Periode Ini</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-semibold text-gray-600">
                            Rp {{ number_format($previousPeriod['Pemasukan'] ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Periode Sebelumnya</p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-700 mb-3">Pengeluaran</h4>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xl font-semibold text-red-600">
                            Rp {{ number_format($currentPeriod['Pengeluaran'] ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Periode Ini</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-semibold text-gray-600">
                            Rp {{ number_format($previousPeriod['Pengeluaran'] ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Periode Sebelumnya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi date picker
    flatpickr("#start_date", {
        dateFormat: "Y-m-d"
    });
    flatpickr("#end_date", {
        dateFormat: "Y-m-d"
    });

    // Data untuk grafik tren bulanan
    const monthlyData = @json($monthlyTrends);
    const months = monthlyData.map(data => `${data.year}-${String(data.month).padStart(2, '0')}`);
    const incomeData = monthlyData.map(data => data.income);
    const expenseData = monthlyData.map(data => data.expense);

    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: months,
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
            plugins: {
                legend: {
                    position: 'top',
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

    // Data untuk grafik kategori
    const categoryData = @json($categoryAnalysis);
    const expenseCategories = categoryData
        .filter(cat => cat.type === 'Pengeluaran')
        .map(cat => ({
            name: cat.name,
            amount: cat.total_amount
        }));

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: expenseCategories.map(cat => cat.name),
            datasets: [{
                data: expenseCategories.map(cat => cat.amount),
                backgroundColor: [
                    '#3B82F6', '#059669', '#DC2626', '#F59E0B', '#6366F1',
                    '#10B981', '#F97316', '#8B5CF6', '#14B8A6', '#EC4899'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
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