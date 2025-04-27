@extends('layouts.app')

@section('title', 'Daftar Notifikasi')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Notifikasi</h2>
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-4 focus:ring-red-200 transition-all duration-200 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Hapus Semua
                </button>
            </form>
        @endif
    </div>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="p-4 rounded-lg border {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->type === 'App\Notifications\TransactionReminder')
                                <i class="fas fa-exchange-alt text-blue-500 text-xl"></i>
                            @elseif($notification->type === 'App\Notifications\ReminderNotification')
                                <i class="fas fa-bell text-blue-500 text-xl"></i>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('notifications.show', $notification) }}" class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                    {{ $notification->data['title'] }}
                                </a>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i') }}
                                </span>
                            </div>
                            @if(isset($notification->data['message']))
                                <p class="mt-1 text-gray-600">{{ $notification->data['message'] }}</p>
                            @endif
                            @unless($notification->read_at)
                                <div class="mt-2">
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                            Tandai sudah dibaca
                                        </button>
                                    </form>
                                </div>
                            @endunless
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection 