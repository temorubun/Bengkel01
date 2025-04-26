@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kategori</h1>
        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 rounded-lg {{ $category->type == 'Pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                        <svg class="w-6 h-6 {{ $category->type == 'Pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm {{ $category->type == 'Pemasukan' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $category->type }}
                    </span>
                </div>
                <h3 class="text-lg font-semibold mb-2">{{ $category->name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                <div class="flex space-x-2">
                    <button onclick="editCategory({{ $category->id }})" class="text-blue-600 hover:underline text-sm">Edit</button>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold">Tambah Kategori</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Nama Kategori</label>
                        <input type="text" name="name" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan nama kategori">
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
                        <label class="block text-gray-700 text-base mb-2">Icon</label>
                        <input type="text" name="icon" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan emoji icon">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-base mb-2">Deskripsi</label>
                        <textarea name="description" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan deskripsi kategori..." rows="3"></textarea>
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
            document.getElementById('categoryModal').classList.remove('hidden');
            document.getElementById('categoryModal').classList.add('flex');
            resetForm();
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
            document.getElementById('categoryModal').classList.remove('flex');
            resetForm();
        }

        function resetForm() {
            const form = document.getElementById('categoryForm');
            form.reset();
            form.action = "{{ route('categories.store') }}";
            document.getElementById('modalTitle').textContent = 'Tambah Kategori';
            
            // Remove any existing method field
            const existingMethodField = form.querySelector('input[name="_method"]');
            if (existingMethodField) {
                existingMethodField.remove();
            }
        }

        async function editCategory(id) {
            try {
                const response = await fetch(`/categories/${id}`);
                if (!response.ok) throw new Error('Failed to fetch category');
                
                const category = await response.json();
                
                // Open modal
                openModal();
                
                // Update form
                const form = document.getElementById('categoryForm');
                form.action = `/categories/${id}`;
                document.getElementById('modalTitle').textContent = 'Edit Kategori';
                
                // Fill form fields
                form.querySelector('input[name="name"]').value = category.name;
                form.querySelector('input[name="description"]').value = category.description || '';
                form.querySelector('select[name="type"]').value = category.type;
                
                // Add method spoofing for PUT request
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data kategori');
            }
        }
    </script>
@endsection 