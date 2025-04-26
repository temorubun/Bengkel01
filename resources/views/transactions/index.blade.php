@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Transaksi</h1>
        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Transaksi
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('transactions.index') }}" method="GET" class="flex gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tipe</label>
                <select name="type" class="border rounded-lg px-3 py-2 w-40">
                    <option value="Semua" {{ request('type') == 'Semua' ? 'selected' : '' }}>Semua</option>
                    <option value="Pemasukan" {{ request('type') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="Pengeluaran" {{ request('type') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" class="border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Kategori</label>
                <select name="category" class="border rounded-lg px-3 py-2 w-40">
                    <option value="Semua">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="self-end">
                <button type="submit" class="bg-gray-100 px-4 py-2 rounded-lg hover:bg-gray-200">Filter</button>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $transaction->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 rounded-full {{ $transaction->type == 'Pemasukan' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $transaction->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="editTransaction({{ $transaction->id }})" class="text-blue-600 hover:underline mr-3">Edit</button>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 id="modalTitle" class="text-2xl font-bold mb-6">Tambah Transaksi</h2>
            <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Tanggal</label>
                        <input type="date" name="date" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="mm/dd/yyyy">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Deskripsi</label>
                        <input type="text" name="description" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan deskripsi transaksi">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Kategori</label>
                        <select name="category" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white">
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Tipe</label>
                        <select name="type" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white">
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Jumlah</label>
                        <input type="number" name="amount" required min="0" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan jumlah">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Tambahkan catatan..." rows="3"></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-8 gap-4">
                    <button type="button" onclick="closeModal()" 
                        class="px-6 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg border border-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('transactionModal').classList.remove('hidden');
            document.getElementById('transactionModal').classList.add('flex');
            resetForm();
        }

        function closeModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            document.getElementById('transactionModal').classList.remove('flex');
            resetForm();
        }

        function resetForm() {
            const form = document.getElementById('transactionForm');
            form.reset();
            form.action = "{{ route('transactions.store') }}";
            document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
            
            // Remove any existing method field
            const existingMethodField = form.querySelector('input[name="_method"]');
            if (existingMethodField) {
                existingMethodField.remove();
            }
        }

        async function editTransaction(id) {
            try {
                const response = await fetch(`/transactions/${id}`);
                if (!response.ok) throw new Error('Failed to fetch transaction');
                
                const transaction = await response.json();
                
                // Open modal
                openModal();
                
                // Update form
                const form = document.getElementById('transactionForm');
                form.action = `/transactions/${id}`;
                document.getElementById('modalTitle').textContent = 'Edit Transaksi';
                
                // Fill form fields
                form.querySelector('input[name="date"]').value = transaction.date;
                form.querySelector('input[name="description"]').value = transaction.description;
                form.querySelector('input[name="category"]').value = transaction.category;
                form.querySelector('select[name="type"]').value = transaction.type;
                form.querySelector('input[name="amount"]').value = transaction.amount;
                
                // Add method spoofing for PUT request
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data transaksi');
            }
        }
    </script>
@endsection 