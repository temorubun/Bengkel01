<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceTracker - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="flex w-full max-w-6xl bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Left side - Image and Text -->
            <div class="w-1/2 bg-gradient-to-br from-blue-600 to-cyan-400 p-12 text-white flex flex-col items-center justify-center">
                <div class="w-64 h-64 mb-8">
                    <img src="{{ asset('images/finance-illustration.png') }}" alt="Finance Illustration" class="w-full h-full object-contain">
                </div>
                <h2 class="text-3xl font-bold text-center mb-4">Kelola Keuangan Dengan Mudah</h2>
                <p class="text-center text-lg">Platform pencatatan keuangan pribadi yang aman dan intuitif</p>
            </div>

            <!-- Right side - Login Form -->
            <div class="w-1/2 p-12">
                <div class="max-w-md mx-auto">
                    <!-- Logo -->
                    <div class="text-center mb-8">
                        <div class="inline-block p-2 bg-black rounded-lg mb-2">
                            <span class="text-white text-2xl font-bold">FT</span>
                        </div>
                        <h1 class="text-2xl font-bold">FinanceTracker</h1>
                    </div>

                    <h2 class="text-2xl font-bold text-center mb-2">Selamat Datang Kembali!</h2>
                    <p class="text-gray-600 text-center mb-8">Kelola keuanganmu dengan mudah</p>

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

                        <div class="text-right">
                            <a href="#" class="text-blue-600 hover:underline">Lupa Password?</a>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Masuk
                        </button>
                    </form>

                    <!-- Social Login -->
                    <div class="mt-8">
                        <div class="text-center text-gray-600 mb-4">Atau masuk dengan</div>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex items-center justify-center py-3 px-4 border border-gray-300 rounded-lg hover:bg-gray-50">
                                <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5 mr-2">
                                Google
                            </button>
                            <button class="flex items-center justify-center py-3 px-4 border border-gray-300 rounded-lg hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.5 12.5C17.5 15.2614 15.2614 17.5 12.5 17.5C9.73858 17.5 7.5 15.2614 7.5 12.5C7.5 9.73858 9.73858 7.5 12.5 7.5C15.2614 7.5 17.5 9.73858 17.5 12.5Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                                Apple
                            </button>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center mt-8">
                        <span class="text-gray-600">Belum punya akun? </span>
                        <a href="#" class="text-blue-600 hover:underline">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 