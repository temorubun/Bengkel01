@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Pemasukan Card -->
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pemasukan Bulan Ini</h3>
            <div class="text-2xl font-bold mb-2">Rp {{ number_format($monthlyTotals->total_income ?? 0, 0, ',', '.') }}</div>
            <div class="flex items-center text-sm {{ $incomePercentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($incomePercentage >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    @endif
                </svg>
                {{ abs(round($incomePercentage)) }}% dari bulan lalu
            </div>
        </div>

        <!-- Pengeluaran Card -->
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Total Pengeluaran Bulan Ini</h3>
            <div class="text-2xl font-bold mb-2">Rp {{ number_format($monthlyTotals->total_expense ?? 0, 0, ',', '.') }}</div>
            <div class="flex items-center text-sm {{ $expensePercentage <= 0 ? 'text-green-600' : 'text-red-600' }}">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($expensePercentage <= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    @endif
                </svg>
                {{ abs(round($expensePercentage)) }}% dari bulan lalu
            </div>
        </div>

        <!-- Saldo Card -->
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-gray-600 text-sm mb-2">Saldo Bulan Ini</h3>
            <div class="text-2xl font-bold mb-2 {{ ($monthlyTotals->total_income - $monthlyTotals->total_expense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(($monthlyTotals->total_income ?? 0) - ($monthlyTotals->total_expense ?? 0), 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Transaksi Terbaru</h2>
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">Lihat Semua</a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Spending Categories -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold">Kategori Pengeluaran Terbesar</h2>
                <p class="text-sm text-gray-600">Bulan ini</p>
            </div>
            
            <div class="p-6">
                @forelse($spendingByCategory as $category)
                    <div class="mb-4 last:mb-0">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium">{{ $category->category }}</span>
                            <span class="text-sm text-gray-600">Rp {{ number_format($category->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($category->total / $monthlyTotals->total_expense) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500">Belum ada pengeluaran bulan ini</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection 