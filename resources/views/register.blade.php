@extends('Layouts.auth')

@section('title', $title)

@section('content')
<div class="min-h-screen flex items-center justify-center bg-linear-to-br from-purple-100 via-white to-blue-100 py-12">

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row w-full max-w-5xl">

        <!-- Left Image Section -->
        <div class="md:w-1/2 bg-blue-300 flex items-center justify-center p-10">
            <img src="{{ asset('images/undraw_book-writer_ri5u.svg') }}"
                 alt="Register Illustration"
                 class="w-3/4 drop-shadow-lg">
        </div>

        <!-- Right Form Section -->
        <div class="md:w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-5">
                Daftar Akun Baru
            </h2>

            <p class="text-center text-gray-500 mb-6">
                Buat akun untuk mulai meminjam dan mengakses ribuan koleksi buku.
            </p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('regisUser') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Username</label>
                    <input type="text" name="username"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan username Anda">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Email</label>
                    <input type="email" name="email"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan email Anda">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan password">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg 
                        shadow-md hover:opacity-90 transition-all duration-200">
                    Daftar Sekarang
                </button>

                <p class="text-center text-gray-600 mt-4">
                    Sudah punya akun?
                    <a href="/login" class="text-blue-600 font-semibold hover:underline">
                        Login di sini
                    </a>
                </p>

            </form>

        </div>
    </div>

</div>
@endsection
