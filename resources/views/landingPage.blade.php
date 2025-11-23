@extends('Layouts.app')

@section('content')
<x-navbar></x-navbar>
<div class="relative h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: url('{{ asset('images/hero-section.jpg') }}');">

    <div class="absolute inset-0 bg-opacity-50"></div>

    <div class="relative z-10 text-center px-6">
        <h1 class="text-5xl font-bold text-white mb-4 drop-shadow-lg">
            Selamat Datang di Perpustakaan Digital
        </h1>
        <p class="text-xl text-gray-200 max-w-2xl mx-auto mb-6">
            Akses ribuan buku, kelola peminjaman, dan eksplorasi koleksi terbaik kami.  
            Semua dalam satu platform modern.
        </p>

        <a href="/login"
            class="bg-blue-600 text-white px-7 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
            Mulai Sekarang
        </a>
    </div>
    </div>

    <!-- Features Section -->
    <section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold mb-10">Kenapa Memilih Kami?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <div class="bg-gray-50 p-8 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-5xl mb-3">📘</div>
                <h3 class="text-xl font-semibold mb-3">Buku Lengkap</h3>
                <p class="text-gray-600">
                    Koleksi buku yang luas dari berbagai kategori dan penulis.
                </p>
            </div>

            <div class="bg-gray-50 p-8 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-5xl mb-3">⚡</div>
                <h3 class="text-xl font-semibold mb-3">Peminjaman Cepat</h3>
                <p class="text-gray-600">
                    Proses peminjaman mudah & langsung terintegrasi dengan dashboard.
                </p>
            </div>

            <div class="bg-gray-50 p-8 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-5xl mb-3">🔍</div>
                <h3 class="text-xl font-semibold mb-3">Pencarian Akurat</h3>
                <p class="text-gray-600">
                    Fitur pencarian buku berdasarkan judul, penulis, dan kategori.
                </p>
            </div>

        </div>

    </div>
</section>
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-16">Layanan Perpustakaan</h2>

        <!-- Service 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-20">
            <img src="{{ asset('images/service-1.jpg') }}" 
                 class="w-full rounded-xl shadow-lg" alt="Service 1">

            <div>
                <h3 class="text-2xl font-bold mb-4">Peminjaman Buku</h3>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Layanan peminjaman buku yang mudah dan cepat, tanpa antrian.
                    Pengguna cukup login, memilih buku, dan langsung meminjam secara digital.
                </p>
            </div>
        </div>

        <!-- Service 2 (dibalik: teks kiri, gambar kanan) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-20">
            <div>
                <h3 class="text-2xl font-bold mb-4">Pengembalian Terjadwal</h3>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Sistem mencatat dan mengingatkan tenggat pengembalian buku.
                    Notifikasi otomatis membuat pengguna tidak terlambat mengembalikan buku.
                </p>
            </div>

            <img src="{{ asset('images/service-2.jpg') }}" 
                 class="h-100px rounded-xl shadow-lg " alt="Service 2">
        </div>

        <!-- Service 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <img src="{{ asset('images/service-3.jpg') }}" 
                 class="w-full rounded-xl shadow-lg" alt="Service 3">

            <div>
                <h3 class="text-2xl font-bold mb-4">Rekomendasi Buku</h3>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Dapatkan rekomendasi buku berdasarkan genre favorit, buku populer,
                    serta riwayat peminjaman pengguna untuk pengalaman membaca terbaik.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- Reviews Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold mb-12">Apa Kata Pengguna?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-gray-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <p class="italic text-gray-700 mb-4">
                    "Sistem perpustakaan ini sangat membantu! Peminjaman jadi lebih cepat dan praktis."
                </p>
                <h3 class="font-semibold text-gray-800"> Budi Santoso</h3>
            </div>

            <div class="bg-gray-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <p class="italic text-gray-700 mb-4">
                    "Desainnya modern dan mudah dipahami. Cocok untuk mahasiswa."
                </p>
                <h3 class="font-semibold text-gray-800"> Siti Aisyah</h3>
            </div>

            <div class="bg-gray-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <p class="italic text-gray-700 mb-4">
                    "Koleksi bukunya lengkap banget, pencarian juga cepat!"
                </p>
                <h3 class="font-semibold text-gray-800">Andi Pratama</h3>
            </div>

        </div>
    </div>
</section>

<section class="py-20 bg-blue-600 text-white text-center">
    <div class="max-w-3xl mx-auto px-6">

        <h2 class="text-4xl font-bold mb-4">Mulai Jelajahi Koleksi Buku Kami</h2>

        <p class="text-lg text-blue-100 mb-8">
            Daftar sekarang dan nikmati kemudahan meminjam buku secara digital,
            tanpa batasan waktu dan tempat.
        </p>

        <a href="/login"
           class="bg-white text-blue-700 font-semibold px-8 py-3 rounded-lg text-lg shadow 
                  hover:bg-gray-100 transition">
            Mulai Sekarang
        </a>

    </div>
</section>

<x-footer></x-footer>
@endsection