<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMoney - Login</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

            <!-- Right side - Login Form -->
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

                    <h2 class="text-2xl font-bold text-center mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-600 text-center mb-8">Kelola keuanganmu dengan mudah</p>

                    @if (session('warning'))
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" placeholder="Masukkan email" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" placeholder="Masukkan password" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa Password?</a>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-8">
                        <span class="text-gray-600">Belum punya akun? </span>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                            <i class="fas fa-user-plus mr-1"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 