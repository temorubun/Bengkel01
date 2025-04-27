<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyMoney')</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; /* w-64 in rem */
            overflow-y: auto;
            z-index: 40;
        }
        .main-content {
            margin-left: 16rem; /* Same as sidebar width */
            width: calc(100% - 16rem);
            padding-top: 4rem; /* Add padding to prevent content from being hidden under header */
        }
        .header {
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - 16rem);
            background-color: white;
            z-index: 30;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    @auth
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="sidebar bg-white shadow-lg">
            <!-- Logo -->
            <div class="p-4 border-b flex items-center justify-center">
                <div class="flex items-center space-x-2">
                    <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="h-8 w-auto">
                    <span class="text-xl font-bold">MyMoney</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-tags"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analysis.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('analysis.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Analisis</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reminders.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('reminders.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-bell"></i>
                            <span>Pengingat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-2xl font-bold">@yield('title')</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-600 hover:text-blue-600 relative">
                                <i class="fas fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
                                    <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                                    @if(auth()->user()->notifications()->count() > 0)
                                        <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                Hapus Semua
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <!-- Notification List with Scroll -->
                                <div class="overflow-y-auto" style="max-height: 400px;">
                                    @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
                                        <a href="{{ route('notifications.show', $notification) }}" class="block">
                                            <div class="px-4 py-3 hover:bg-gray-50 border-b last:border-0 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                                                <div class="flex items-start">
                                                    @if($notification->type === 'App\Notifications\TransactionReminder')
                                                        <i class="fas fa-exchange-alt text-blue-500 mt-1 mr-3"></i>
                                                    @elseif($notification->type === 'App\Notifications\ReminderNotification')
                                                        <i class="fas fa-bell text-blue-500 mt-1 mr-3"></i>
                                                    @endif
                                                    <div class="flex-1">
                                                        <p class="text-sm text-gray-800 font-medium">
                                                            {{ $notification->data['title'] }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-600 text-center">
                                            Tidak ada notifikasi
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 border-t text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">
                                        Lihat Semua Notifikasi
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button id="profileButton" class="flex items-center space-x-3 focus:outline-none">
                                <div class="text-gray-600">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="w-8 h-8 rounded-full overflow-hidden">
                                    @if(Auth::user()->photo)
                                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-cog mr-2"></i>
                                    Pengaturan Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @else
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-bold mb-4">Silakan Login Terlebih Dahulu</h1>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Login</a>
                <a href="{{ route('register') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">Register</a>
            </div>
        </div>
    </div>
    @endauth

    @stack('scripts')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileButton && profileDropdown) {
            // Toggle dropdown
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }
    });
    </script>

    @if(session('reminder_notification'))
        <script>
        Swal.fire({
            title: 'Pengingat!',
            html: `{!! session('reminder_notification') !!}`,
            icon: 'info',
            confirmButtonText: 'OK',
            timer: 5000,
            timerProgressBar: true
        });
        </script>
    @endif
</body>
</html> 