@extends('layouts.app')

@section('title', 'Daftar Pengingat')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 flex justify-between items-center border-b">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Pengingat</h2>
        <a href="{{ route('reminders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengingat
        </a>
    </div>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="min-w-full">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                        <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($reminders as $reminder)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-4 text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-800">
                                <div class="font-medium">{{ $reminder->title }}</div>
                                <!-- Mobile-only description -->
                                <div class="md:hidden text-xs text-gray-500 mt-1">
                                    {{ Str::limit($reminder->description, 30) }}
                                </div>
                                <!-- Mobile-only date and type -->
                                <div class="sm:hidden text-xs text-gray-500 mt-1">
                                    {{ $reminder->reminder_date->format('d/m/Y') }}
                                    <span class="mx-1">•</span>
                                    @switch($reminder->reminder_type)
                                        @case('once')
                                            Sekali
                                            @break
                                        @case('daily')
                                            Harian
                                            @break
                                        @case('weekly')
                                            Mingguan
                                            @break
                                        @case('monthly')
                                            Bulanan
                                            @break
                                    @endswitch
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-3 py-4 text-sm text-gray-800">
                                {{ Str::limit($reminder->description, 50) }}
                            </td>
                            <td class="hidden sm:table-cell px-3 py-4 text-sm text-gray-500">
                                {{ $reminder->reminder_date->format('d/m/Y') }}
                            </td>
                            <td class="hidden sm:table-cell px-3 py-4 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    @switch($reminder->reminder_type)
                                        @case('once')
                                            Sekali
                                            @break
                                        @case('daily')
                                            Harian
                                            @break
                                        @case('weekly')
                                            Mingguan
                                            @break
                                        @case('monthly')
                                            Bulanan
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-3 py-4 text-sm">
                                @if($reminder->is_completed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reminder->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $reminder->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if(!$reminder->is_completed)
                                        <form action="{{ route('reminders.complete', $reminder) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-800">
                                                <i class="fas fa-check"></i>
                                                <span class="sr-only">Selesai</span>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('reminders.edit', $reminder) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit</span>
                                    </a>
                                    <button onclick="deleteReminder({{ $reminder->id }})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                        <span class="sr-only">Hapus</span>
                                    </button>
                                    <form id="delete-form-{{ $reminder->id }}" action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-4 text-sm text-gray-500 text-center">
                                Tidak ada pengingat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteReminder(reminderId) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus pengingat ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + reminderId).submit();
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
        showConfirmButton: false
    });
@endif
</script>
@endpush
@endsection 