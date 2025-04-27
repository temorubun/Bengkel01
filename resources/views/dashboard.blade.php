@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Ringkasan Bulan Ini -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Pemasukan Bulan Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pemasukan Bulan Ini</h3>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-up text-green-600"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($currentMonth->total_income ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $currentMonth->income_count ?? 0 }} transaksi</p>
        </div>

        <!-- Total Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pengeluaran Bulan Ini</h3>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-down text-red-600"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($currentMonth->total_expense ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $currentMonth->expense_count ?? 0 }} transaksi</p>
        </div>

        <!-- Rata-rata Harian -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Rata-rata Harian</h3>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calculator text-blue-600"></i>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pemasukan</span>
                    <span class="text-sm font-semibold text-green-600">Rp {{ number_format($dailyAvgIncome ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pengeluaran</span>
                    <span class="text-sm font-semibold text-red-600">Rp {{ number_format($dailyAvgExpense ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Tahun Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Total Tahun {{ date('Y') }}</h3>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pemasukan</span>
                    <span class="text-sm font-semibold text-green-600">Rp {{ number_format($yearlyData->total_income ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pengeluaran</span>
                    <span class="text-sm font-semibold text-red-600">Rp {{ number_format($yearlyData->total_expense ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grafik Tren Bulanan -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren 6 Bulan Terakhir</h3>
            <canvas id="monthlyTrendChart" class="w-full"></canvas>
        </div>

        <!-- Distribusi Kategori -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Pengeluaran per Kategori</h3>
            <canvas id="categoryChart" class="w-full"></canvas>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                {{ $transaction->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $transaction->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $transaction->type == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada transaksi
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik tren bulanan
    const monthlyData = @json($monthlyTrends);
    const months = monthlyData.map(data => {
        const date = new Date(data.year, data.month - 1);
        return date.toLocaleString('id-ID', { month: 'short', year: 'numeric' });
    });
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
    const categoryData = @json($expenseByCategory);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(cat => cat.name),
            datasets: [{
                data: categoryData.map(cat => cat.total_amount),
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