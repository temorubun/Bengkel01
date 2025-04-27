<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMoney - Aplikasi Manajemen Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-background {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="h-8 w-auto">
                    <span class="ml-2 text-xl font-bold text-gray-800">MyMoney</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                        Kelola Keuangan Anda dengan Lebih Mudah
                    </h1>
                    <p class="text-lg md:text-xl mb-8 text-blue-100">
                        MyMoney membantu Anda melacak pemasukan, pengeluaran, dan mencapai tujuan keuangan Anda dengan lebih efektif.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg text-lg font-semibold inline-flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="text-white border-2 border-white hover:bg-white hover:text-blue-600 px-6 py-3 rounded-lg text-lg font-semibold inline-flex items-center transition duration-150 ease-in-out">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/illustration_yb0vsj.png" alt="Hero Image" class="w-full max-w-md mx-auto rounded-[10%]">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Utama</h2>
                <p class="text-lg text-gray-600">Berbagai fitur yang memudahkan pengelolaan keuangan Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Lacak Transaksi</h3>
                    <p class="text-gray-600">Catat dan pantau semua transaksi keuangan Anda dengan mudah dan terorganisir.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Pengingat</h3>
                    <p class="text-gray-600">Atur pengingat untuk pembayaran dan transaksi penting agar tidak terlewat.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-pie text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Analisis Keuangan</h3>
                    <p class="text-gray-600">Lihat laporan dan analisis detail tentang kondisi keuangan Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="h-8 w-auto">
                        <span class="ml-2 text-xl font-bold">MyMoney</span>
                    </div>
                    <p class="text-gray-400">Solusi manajemen keuangan pribadi yang mudah dan efektif.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Daftar</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-envelope mr-2"></i>
                            support@mymoney.com
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-phone mr-2"></i>
                            +62 123 4567 890
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} MyMoney. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 