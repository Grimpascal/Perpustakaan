@extends('layouts.dbUser')

@section('title', $title ?? 'Detail Buku')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Cover --}}
        <div class="md:w-1/3">
            <img src="{{ $book->cover ? asset('storage/'.$book->cover) : 'https://via.placeholder.com/400x600?text=No+Cover' }}"
                 alt="{{ $book->judul }}"
                 class="w-full h-auto rounded">
        </div>

        {{-- Info --}}
        <div class="md:w-2/3">
            <h1 class="text-2xl font-bold mb-2">{{ $book->judul }}</h1>
            <p class="text-gray-600 mb-1"><strong>Penulis:</strong> {{ $book->penulis }}</p>
            <p class="text-gray-600 mb-1"><strong>Penerbit:</strong> {{ $book->penerbit->nama_penerbit ?? '-' }}</p>
            <p class="text-gray-600 mb-1"><strong>Tahun:</strong> {{ $book->tahun }}</p>
            <p class="text-gray-600 mb-3"><strong>Kategori:</strong> {{ $book->kategori->nama_kategori ?? '-' }}</p>

            <p class="mb-4">
                @if($book->stok > 0)
                    <span class="px-3 py-1 bg-green-200 text-green-800 rounded">In Stock ({{ $book->stok }})</span>
                @else
                    <span class="px-3 py-1 bg-red-200 text-red-800 rounded">Out of Stock</span>
                @endif
            </p>

            <div class="flex gap-3">
                {{-- Pinjam (form POST) --}}
                @if($book->stok > 0)
                <form action="{{ route('user.pinjam', $book->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Pinjam</button>
                </form>
                @else
                <button class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed">Habis</button>
                @endif

                <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded">Kembali ke Daftar</a>
            </div>
        </div>
    </div>

    {{-- Deskripsi (jika ada) --}}
    @if(!empty($book->deskripsi))
    <div class="mt-6">
        <h3 class="font-semibold mb-2">Deskripsi</h3>
        <p class="text-gray-700">{{ $book->deskripsi }}</p>
    </div>
    @endif

</div>
@endsection
