@extends('layouts.dbAdmin')

@section('title', $title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Buku</h1>
            <p class="text-gray-600 mt-1">Kelola koleksi buku perpustakaan</p>
        </div>
        <a href="{{ route('buku.create') }}" 
           class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i>
            Tambah Buku
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('buku.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Buku</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Judul, penulis, ISBN..."
                               class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Kategori Filter -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" 
                            id="kategori"
                            class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategories as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-filter mr-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('buku.index') }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-refresh mr-1"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Compact -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Buku
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                            Penulis
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                            Kategori
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                            Stok
                        </th>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                            Status
                        </th>
                        <th scope="col" class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-3 py-3">
                                <div class="flex items-center min-w-0">
                                    <div class="flex-shrink-0 h-8 w-6 mr-2">
                                        @if($book->cover)
                                            <img class="h-8 w-6 object-cover rounded shadow-sm" 
                                                 src="{{ asset('storage/' . $book->cover) }}" 
                                                 alt="{{ $book->judul }}">
                                        @else
                                            <div class="h-8 w-6 bg-gray-200 rounded shadow-sm flex items-center justify-center">
                                                <i class="fas fa-book text-gray-400 text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-medium text-gray-900 truncate" title="{{ $book->judul }}">
                                            {{ $book->judul }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate">
                                            {{ $book->tahun }} • {{ $book->isbn ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 py-3">
                                <div class="text-xs text-gray-900 truncate" title="{{ $book->penulis }}">
                                    {{ $book->penulis }}
                                </div>
                            </td>
                            <td class="px-2 py-3">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 truncate max-w-20" title="{{ $book->kategori->nama ?? 'Tidak ada kategori' }}">
                                    {{ $book->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap text-xs text-gray-900 text-center">
                                {{ $book->stok }}
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap">
                                @if($book->is_available && $book->stok > 0)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1 text-xs"></i>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1 text-xs"></i>
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex justify-end space-x-1">
                                    <a href="{{ route('buku.edit', $book->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150 p-1 rounded"
                                       title="Edit Buku">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $book->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition-colors duration-150 p-1 rounded"
                                                title="Hapus Buku">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-book-open text-3xl mb-2 text-gray-300"></i>
                                    <p class="text-base font-medium mb-1">Tidak ada buku ditemukan</p>
                                    <p class="text-xs">Coba ubah filter pencarian atau tambahkan buku baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-xs text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $books->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $books->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $books->total() }}</span>
                        hasil
                    </div>
                    <div class="flex space-x-2">
                        @if(!$books->onFirstPage())
                            <a href="{{ $books->previousPageUrl() }}" class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300">
                                Previous
                            </a>
                        @endif
                        @if($books->hasMorePages())
                            <a href="{{ $books->nextPageUrl() }}" class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300">
                                Next
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection