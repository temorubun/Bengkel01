@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">{{ $notification->data['title'] }}</h2>
                <span class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i') }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                <!-- Icon and Type -->
                <div class="flex items-center space-x-3">
                    @if($notification->type === 'App\Notifications\TransactionReminder')
                        <i class="fas fa-exchange-alt text-blue-500 text-2xl"></i>
                        <span class="text-gray-600">Pengingat Transaksi</span>
                    @elseif($notification->type === 'App\Notifications\ReminderNotification')
                        <i class="fas fa-bell text-blue-500 text-2xl"></i>
                        <span class="text-gray-600">Pengingat Umum</span>
                    @endif
                </div>

                <!-- Message -->
                @if(isset($notification->data['message']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Pesan:</h3>
                        <p class="text-gray-600">{{ $notification->data['message'] }}</p>
                    </div>
                @endif

                <!-- Additional Details -->
                @if(isset($notification->data['amount']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Jumlah:</h3>
                        <p class="text-gray-600">Rp {{ number_format($notification->data['amount'], 0, ',', '.') }}</p>
                    </div>
                @endif

                @if(isset($notification->data['due_date']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Tanggal Jatuh Tempo:</h3>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($notification->data['due_date'])->format('d M Y') }}</p>
                    </div>
                @endif

                <!-- Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Status:</h3>
                    <p class="text-gray-600">
                        @if($notification->read_at)
                            <span class="text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                Sudah dibaca pada {{ \Carbon\Carbon::parse($notification->read_at)->format('d M Y H:i') }}
                            </span>
                        @else
                            <span class="text-blue-600">
                                <i class="fas fa-circle mr-1"></i>
                                Belum dibaca
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
                
                @unless($notification->read_at)
                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-check mr-2"></i>
                            Tandai sudah dibaca
                        </button>
                    </form>
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection 