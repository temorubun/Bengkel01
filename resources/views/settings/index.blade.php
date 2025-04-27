@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Profile Section -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-user-circle text-blue-500 mr-3"></i>
                Profil
            </h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input 
                                type="text" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required
                                placeholder="Masukkan nama lengkap"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if($user->photo)
                                        <img src="{{ Storage::url($user->photo) }}" alt="Profile Photo" class="h-16 w-16 rounded-full object-cover ring-2 ring-blue-500 ring-offset-2">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <label class="block">
                                    <span class="sr-only">Choose profile photo</span>
                                    <input 
                                        type="file" 
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200"
                                        id="photo" 
                                        name="photo"
                                        accept="image/*"
                                    >
                                </label>
                            </div>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Section -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-lock text-blue-500 mr-3"></i>
                Ganti Password
            </h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('settings.password.update') }}">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <input 
                            type="password" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('current_password') border-red-500 @enderror" 
                            id="current_password" 
                            name="current_password" 
                            required
                            placeholder="••••••••"
                        >
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input 
                                type="password" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="••••••••"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input 
                                type="password" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                placeholder="••••••••"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-key mr-2"></i>
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 mb-8 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bell text-blue-500 mr-3"></i>
                Pengaturan Notifikasi
            </h2>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('settings.notifications.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition duration-200" 
                                id="reminder_enabled" 
                                name="reminder_enabled" 
                                value="1"
                                {{ old('reminder_enabled', $user->reminder_enabled) ? 'checked' : '' }}
                            >
                            <label for="reminder_enabled" class="ml-3 block text-sm font-medium text-gray-700">
                                Aktifkan Pengingat
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="reminder_days" class="block text-sm font-medium text-gray-700 mb-2">Ingatkan Sebelum (Hari)</label>
                            <input 
                                type="number" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('reminder_days') border-red-500 @enderror" 
                                id="reminder_days" 
                                name="reminder_days" 
                                value="{{ old('reminder_days', $user->reminder_days) }}" 
                                min="1" 
                                max="30"
                            >
                            @error('reminder_days')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition duration-200" 
                                    id="email_notifications" 
                                    name="email_notifications" 
                                    value="1"
                                    {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                                >
                                <label for="email_notifications" class="ml-3 block text-sm font-medium text-gray-700">
                                    Terima Notifikasi Email
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-bell mr-2"></i>
                        Simpan Pengaturan Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-red-600 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                Hapus Akun
            </h2>
        </div>

        <div class="p-6">
            <div class="bg-red-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-600">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Peringatan: Menghapus akun akan menghapus semua data Anda secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form method="POST" action="{{ route('settings.account.delete') }}" id="delete-account-form">
                @csrf
                @method('DELETE')

                <div class="mb-6">
                    <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                        id="delete_password" 
                        name="password" 
                        required
                        placeholder="Masukkan password Anda untuk konfirmasi"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="confirmDelete()" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Hapus Akun',
        html: `
            <div class="text-left">
                <p class="mb-4">Anda akan menghapus akun Anda secara permanen. Tindakan ini akan:</p>
                <ul class="list-disc list-inside space-y-2 mb-4">
                    <li>Menghapus semua data transaksi Anda</li>
                    <li>Menghapus semua kategori yang Anda buat</li>
                    <li>Menghapus semua pengingat Anda</li>
                    <li>Menghapus semua notifikasi</li>
                    <li>Menghapus foto profil Anda</li>
                </ul>
                <p class="text-red-600 font-semibold">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus Akun Saya!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            content: 'text-left'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading state
            Swal.fire({
                title: 'Menghapus Akun...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            // Submit form
            document.getElementById('delete-account-form').submit();
        }
    });
}

// Show success message with SweetAlert2 if exists
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 1500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
@endif

// Show error message with SweetAlert2 if exists
@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        toast: true,
        position: 'top-end'
    });
@endif
</script>

<style>
.swal2-popup-custom {
    width: 32em !important;
}
</style>
@endpush
@endsection 