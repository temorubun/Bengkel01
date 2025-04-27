<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - MyMoney</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6">
                <!-- Logo -->
                <div class="flex flex-col items-center mb-8">
                    <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney" class="w-24 h-24 object-contain mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">MyMoney</h1>
                </div>

                <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Verifikasi Email</h2>

                <div class="text-center text-gray-600 mb-8">
                    Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi. Jika Anda tidak menerima email tersebut,
                </div>

                @if (session('resent'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Link verifikasi baru telah dikirim ke email Anda.</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.resend') }}" class="mb-6">
                    @csrf
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200">
                    Login
                </a>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} MyMoney. Semua hak dilindungi.
        </div>
    </div>
</body>
</html> 