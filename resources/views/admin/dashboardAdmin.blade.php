@extends('layouts.dbAdmin')

@section('title', $title)

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                <h2 class="text-4xl font-bold text-indigo-600 mt-1">{{ $total_pengguna }}</h2>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-4 rounded-xl shadow-inner">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Buku</p>
                <h2 class="text-4xl font-bold text-indigo-600 mt-1">{{ $total_buku }}</h2>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-4 rounded-xl shadow-inner">
                <i class="fa-solid fa-book text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Peminjam</p>
                <h2 class="text-4xl font-bold text-indigo-600 mt-1">{{ $total_peminjam }}</h2>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-4 rounded-xl shadow-inner">
                <i class="fa-solid fa-book text-2xl"></i>
            </div>
        </div>
    </div>
</div>

@endsection
