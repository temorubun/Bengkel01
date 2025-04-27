@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Transaksi Baru</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input 
                        type="date" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror" 
                        id="date" 
                        name="date" 
                        value="{{ old('date', date('Y-m-d')) }}" 
                        required
                    >
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Nama Transaksi</label>
                    <input 
                        type="text" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                        id="description" 
                        name="description" 
                        value="{{ old('description') }}" 
                        placeholder="Masukkan nama transaksi"
                        required
                    >
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                    <select 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" 
                        id="type" 
                        name="type" 
                        required
                    >
                        <option value="">Pilih Tipe Transaksi</option>
                        <option value="Pemasukan" {{ old('type') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="Pengeluaran" {{ old('type') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" 
                        id="category_id" 
                        name="category_id" 
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input 
                            type="number" 
                            class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror" 
                            id="amount" 
                            name="amount" 
                            value="{{ old('amount') }}" 
                            placeholder="0"
                            min="1"
                            step="1"
                            required
                        >
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror" 
                        id="notes" 
                        name="notes" 
                        rows="3" 
                        placeholder="Tambahkan catatan..."
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                            id="is_recurring" 
                            name="is_recurring" 
                            value="1"
                        >
                        <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-900">
                            Transaksi Berulang
                        </label>
                    </div>
                </div>

                <div id="recurring-options" class="mb-6" style="display: none;">
                    <div class="mb-4">
                        <label for="recurring_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pengulangan</label>
                        <select 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            id="recurring_type" 
                            name="recurring_type"
                        >
                            <option value="monthly">Bulanan</option>
                            <option value="weekly">Mingguan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="next_transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berikutnya</label>
                        <input 
                            type="date" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            id="next_transaction_date" 
                            name="next_transaction_date"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('is_recurring').addEventListener('change', function() {
    const recurringOptions = document.getElementById('recurring-options');
    recurringOptions.style.display = this.checked ? 'block' : 'none';
    
    const nextTransactionDate = document.getElementById('next_transaction_date');
    const recurringType = document.getElementById('recurring_type');
    
    if (this.checked) {
        nextTransactionDate.required = true;
        recurringType.required = true;
    } else {
        nextTransactionDate.required = false;
        recurringType.required = false;
    }
});

// Hapus semua event listener yang ada sebelumnya
const amountInput = document.getElementById('amount');

// Hapus karakter selain angka saat input
amountInput.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^\d]/g, '');
});

// Tampilkan pesan error dengan SweetAlert2 jika ada
@if($errors->any())
    const errorMessages = [];
    @foreach($errors->all() as $error)
        errorMessages.push("{{ $error }}");
    @endforeach
    
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: errorMessages.join('<br>'),
    });
@endif
</script>
@endpush
@endsection 