<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMoney - Lupa Password</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="inline-block mb-2">
                        <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="h-16 w-auto">
                    </div>
                    <h1 class="text-2xl font-bold">MyMoney</h1>
                </div>

                <h2 class="text-2xl font-bold text-center mb-2">Lupa Password?</h2>
                <p class="text-gray-600 text-center mb-8">Masukkan email Anda untuk mereset password</p>

                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">Kami telah mengirimkan link reset password ke email Anda.</span>
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf
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

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Kirim Link Reset Password
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="text-center mt-8">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 