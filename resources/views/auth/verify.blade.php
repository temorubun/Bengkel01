@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo -->
        <div class="flex justify-center">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-md">
                <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" class="w-12 h-12">
            </div>
        </div>
        
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Verifikasi Email
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
            @if (session('resent'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Link verifikasi baru telah dikirim ke email Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-sm text-gray-700">
                <p class="mb-4">
                    Jika Anda tidak menerima email verifikasi, klik tombol di bawah ini untuk mengirim ulang.
                </p>
            </div>

            <form class="space-y-6" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Kirim Ulang Email Verifikasi
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            atau
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <form method="POST" action="{{ route('login') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-500">
        <p>© {{ date('Y') }} MyMoney. Semua hak dilindungi.</p>
    </div>
</div>
@endsection 