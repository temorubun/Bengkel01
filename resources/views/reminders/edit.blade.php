@extends('layouts.app')

@section('title', 'Edit Pengingat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Edit Pengingat</h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('reminders.update', $reminder) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengingat</label>
                    <input 
                        type="text" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $reminder->title) }}" 
                        required 
                        placeholder="Masukkan judul pengingat"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                        id="description" 
                        name="description" 
                        rows="3" 
                        placeholder="Masukkan deskripsi pengingat"
                    >{{ old('description', $reminder->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="reminder_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengingat</label>
                    <input 
                        type="date" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reminder_date') border-red-500 @enderror" 
                        id="reminder_date" 
                        name="reminder_date" 
                        value="{{ old('reminder_date', $reminder->reminder_date->format('Y-m-d')) }}" 
                        required
                    >
                    @error('reminder_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="reminder_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pengulangan</label>
                    <select 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reminder_type') border-red-500 @enderror" 
                        id="reminder_type" 
                        name="reminder_type" 
                        required
                    >
                        <option value="">Pilih Tipe Pengulangan</option>
                        <option value="once" {{ old('reminder_type', $reminder->reminder_type) == 'once' ? 'selected' : '' }}>Sekali</option>
                        <option value="daily" {{ old('reminder_type', $reminder->reminder_type) == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ old('reminder_type', $reminder->reminder_type) == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ old('reminder_type', $reminder->reminder_type) == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                    @error('reminder_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                            id="is_active" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $reminder->is_active) ? 'checked' : '' }}
                        >
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                            Pengingat Aktif
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('reminders.index') }}" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
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