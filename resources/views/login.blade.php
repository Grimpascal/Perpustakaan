@extends('layouts.auth')

@section('title', $title)

@section('content')
<div class="min-h-screen flex items-center justify-center from-purple-100  py-12">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row w-full max-w-4xl">
        
        <div class="md:w-1/2 bg-linear-to-br from-purple-600 to-indigo-600 flex items-center justify-center p-8">
            <img src="{{ asset('images/undraw_book-writer_ri5u.svg') }}" 
                 alt="Perpustakaan" 
                 class="w-3/4 drop-shadow-lg ">
        </div>

        <div class="md:w-1/2 p-8 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">
                Selamat Datang 👋
            </h2>
            <p class="text-center text-gray-500 mb-6">
                Silakan masuk untuk mengakses Dashboard Perpustakaan
            </p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('verifLogin') }}"  class="space-y-4" method="POST">
                @csrf
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Username</label>
                    <input type="text" name="username" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Masukkan username Anda">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Password</label>
                    <input type="password" name="password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Masukkan password">
                </div>

                <button type="submit" 
                        class="w-full bg-linear-to-r from-purple-600 to-indigo-600 text-white font-semibold py-2 rounded-lg shadow-md hover:opacity-90 transition-all duration-200">
                    Login
                </button>
            </form>

        </div>
    </div>
</div>
@endsection