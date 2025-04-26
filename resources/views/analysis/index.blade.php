@extends('layouts.app')

@section('title', 'Analisis Keuangan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Analisis Keuangan</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pemasukan</h3>
            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totals->total_income ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pengeluaran</h3>
            <div class="text-2xl font-bold text-red-600">Rp {{ number_format($totals->total_expense ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Saldo</h3>
            <div class="text-2xl font-bold {{ ($totals->total_income - $totals->total_expense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(($totals->total_income ?? 0) - ($totals->total_expense ?? 0), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Income vs Expense Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Perbandingan Pemasukan & Pengeluaran</h2>
            <div class="h-64">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Distribusi Kategori Pengeluaran</h2>
            <div class="h-64">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>

        <!-- Daily Balance Trend -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Tren Saldo Harian</h2>
            <div class="h-64">
                <canvas id="balanceTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Spending Categories -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">5 Kategori Pengeluaran Terbesar</h2>
            <div class="space-y-4">
                @foreach($spendingByCategory->take(5) as $category)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $category->category }}</span>
                            <span>Rp {{ number_format($category->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($category->total / $spendingByCategory->sum('total')) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Category Trend -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Tren Kategori per Bulan</h2>
            <div class="h-64">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Spending Percentage by Category -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Persentase Pengeluaran per Kategori</h2>
            <div class="h-64">
                <canvas id="spendingPercentageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Income vs Expense Chart
        new Chart(document.getElementById('incomeExpenseChart'), {
            type: 'bar',
            data: {
                labels: ['Bulan Ini'],
                datasets: [{
                    label: 'Pemasukan',
                    data: [{{ $totals->total_income ?? 0 }}],
                    backgroundColor: '#10B981',
                    barPercentage: 0.5,
                }, {
                    label: 'Pengeluaran',
                    data: [{{ $totals->total_expense ?? 0 }}],
                    backgroundColor: '#EF4444',
                    barPercentage: 0.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Category Distribution Chart
        new Chart(document.getElementById('categoryDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($spendingByCategory->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($spendingByCategory->pluck('total')) !!},
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                        '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Balance Trend Chart
        new Chart(document.getElementById('balanceTrendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyBalances->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d M'); })) !!},
                datasets: [{
                    label: 'Saldo',
                    data: {!! json_encode($dailyBalances->pluck('balance')) !!},
                    borderColor: '#3B82F6',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });

        // Monthly Category Trend Chart
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const monthlyData = {!! json_encode($monthlyTrendByCategory) !!};
        const categories = [...new Set({!! json_encode($spendingByCategory->pluck('category')) !!})];
        const datasets = categories.map((category, index) => ({
            label: category,
            data: Array(12).fill(0).map((_, month) => {
                const monthData = monthlyData[month + 1] || [];
                const categoryData = monthData.find(d => d.category === category && d.type === 'Pengeluaran');
                return categoryData ? categoryData.total : 0;
            }),
            borderColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#6366F1', '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4'][index % 10],
            tension: 0.4,
            fill: false
        }));

        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Spending Percentage Chart
        const totalSpending = {!! json_encode($spendingByCategory->sum('total')) !!};
        const percentages = {!! json_encode($spendingByCategory->pluck('total')) !!}.map(value => ((value / totalSpending) * 100).toFixed(1));

        new Chart(document.getElementById('spendingPercentageChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($spendingByCategory->pluck('category')) !!},
                datasets: [{
                    data: percentages,
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                        '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection 