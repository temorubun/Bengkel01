<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMoney - Daftar</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="flex w-full max-w-6xl bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Left side - Image and Text -->
            <div class="w-1/2 bg-gradient-to-br from-blue-600 to-cyan-400 p-12 text-white flex flex-col items-center justify-center">
                <div class="w-96 h-96 mb-8">
                <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/illustration_yb0vsj.png" alt="Hero Image" class="w-full max-w-md mx-auto rounded-[10%]">
                </div>
                <h2 class="text-3xl font-bold text-center mb-4">Kelola Keuangan Dengan Mudah</h2>
                <p class="text-center text-lg">Platform pencatatan keuangan pribadi yang aman dan intuitif</p>
            </div>

            <!-- Right side - Registration Form -->
            <div class="w-1/2 p-12">
                <div class="max-w-md mx-auto">
                    <!-- Logo -->
                    <div class="text-center mb-8">
                        <a href="/" class="inline-block">
                            <div class="inline-block mb-2">
                                <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="h-16 w-auto">
                            </div>
                            <h1 class="text-2xl font-bold">MyMoney</h1>
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-center mb-2">Daftar Akun Baru</h2>
                    <p class="text-gray-600 text-center mb-8">Mulai kelola keuanganmu dengan MyMoney</p>

                    <!-- Registration Form -->
                    <form action="{{ route('register') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" required 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" required 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="Masukkan email"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" required 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="Konfirmasi password">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Daftar
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-8">
                        <span class="text-gray-600">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 