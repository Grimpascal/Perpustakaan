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
        <button type="button" 
                id="openModalBtn"
                class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i>
            Tambah Buku
        </button>
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
                        <tr class="hover:bg-gray-50 transition-colors duration-150" 
                            data-book-id="{{ $book->id }}" 
                            data-book-title="{{ $book->judul }}"
                            data-book-data="{{ json_encode([
                                'judul' => $book->judul,
                                'penulis' => $book->penulis,
                                'penerbit_id' => $book->penerbit_id,
                                'kategori_id' => $book->kategori_id,
                                'tahun' => $book->tahun,
                                'isbn' => $book->isbn,
                                'stok' => $book->stok,
                                'bahasa' => $book->bahasa,
                                'deskripsi' => $book->deskripsi
                            ]) }}">
                            <td class="px-3 py-3">
                                <div class="flex items-center min-w-0">
                                    <div class="flex-shrink-0 h-8 w-6 mr-2">
                                        <div class="h-8 w-6 bg-gray-200 rounded shadow-sm flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400 text-xs"></i>
                                        </div>
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
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 truncate max-w-20" title="{{ $book->kategori->nama_kategori ?? 'Tidak ada kategori' }}">
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
                                    <button type="button" 
                                            onclick="showEditModal('{{ $book->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150 p-1 rounded hover:bg-indigo-50 edit-btn"
                                            title="Edit Buku">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            onclick="showDeleteModal('{{ $book->id }}', '{{ addslashes($book->judul) }}')"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-150 p-1 rounded hover:bg-red-50 delete-btn"
                                            title="Hapus Buku">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

<!-- Add Book Modal -->
<div id="addBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 mr-3">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Tambah Buku Baru</h3>
            </div>
            <button type="button" 
                    id="closeModalBtn"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-4">
            <form id="addBookForm" action="{{ route('buku.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Judul -->
                    <div class="col-span-2">
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku *</label>
                        <input type="text" 
                               name="judul" 
                               id="judul"
                               required
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700 mb-1">Penulis *</label>
                        <input type="text" 
                               name="penulis" 
                               id="penulis"
                               required
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Penerbit Dropdown -->
                    <div>
                        <label for="penerbit_id" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                        <select name="penerbit_id" 
                                id="penerbit_id"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Pilih Penerbit</option>
                            @foreach($penerbits as $penerbit)
                                <option value="{{ $penerbit->id }}">{{ $penerbit->nama_penerbit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit *</label>
                        <input type="number" 
                               name="tahun" 
                               id="tahun"
                               required
                               min="1900"
                               max="{{ date('Y') }}"
                               value="{{ date('Y') }}"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Kategori Dropdown -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" 
                                id="kategori_id"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <input type="text" 
                               name="isbn" 
                               id="isbn"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                        <input type="number" 
                               name="stok" 
                               id="stok"
                               required
                               min="1"
                               value="1"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    </div>

                    <!-- Bahasa -->
                    <div>
                        <label for="bahasa" class="block text-sm font-medium text-gray-700 mb-1">Bahasa</label>
                        <select name="bahasa" 
                                id="bahasa"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="Indonesia" selected>Indonesia</option>
                            <option value="Inggris">Inggris</option>
                            <option value="Jawa">Jawa</option>
                            <option value="Sunda">Sunda</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" 
                                  id="deskripsi"
                                  rows="3"
                                  class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                  placeholder="Deskripsi singkat tentang buku..."></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                    <button type="button" 
                            id="cancelBtn"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 hover:shadow-md">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div id="editBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white transform transition-all duration-300 scale-95">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 mr-3">
                    <i class="fas fa-edit text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Edit Buku</h3>
            </div>
            <button type="button" 
                    id="closeEditModalBtn"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-4">
            <form id="editBookForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Hanya isi field yang ingin diubah. Biarkan kosong jika tidak ada perubahan.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Judul -->
                    <div class="col-span-2">
                        <label for="edit_judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                        <input type="text" 
                               name="judul" 
                               id="edit_judul"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Biarkan kosong jika tidak ada perubahan">
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="edit_penulis" class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <input type="text" 
                               name="penulis" 
                               id="edit_penulis"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Biarkan kosong jika tidak ada perubahan">
                    </div>

                    <!-- Penerbit Dropdown -->
                    <div>
                        <label for="edit_penerbit_id" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                        <select name="penerbit_id" 
                                id="edit_penerbit_id"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">-- Pilih Penerbit --</option>
                            @foreach($penerbits as $penerbit)
                                <option value="{{ $penerbit->id }}">{{ $penerbit->nama_penerbit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="edit_tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                        <input type="number" 
                               name="tahun" 
                               id="edit_tahun"
                               min="1900"
                               max="{{ date('Y') }}"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Biarkan kosong jika tidak ada perubahan">
                    </div>

                    <!-- Kategori Dropdown -->
                    <div>
                        <label for="edit_kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" 
                                id="edit_kategori_id"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="edit_isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <input type="text" 
                               name="isbn" 
                               id="edit_isbn"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Biarkan kosong jika tidak ada perubahan">
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="edit_stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" 
                               name="stok" 
                               id="edit_stok"
                               min="0"
                               class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Biarkan kosong jika tidak ada perubahan">
                    </div>

                    <!-- Bahasa -->
                    <div>
                        <label for="edit_bahasa" class="block text-sm font-medium text-gray-700 mb-1">Bahasa</label>
                        <select name="bahasa" 
                                id="edit_bahasa"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">-- Pilih Bahasa --</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Inggris">Inggris</option>
                            <option value="Jawa">Jawa</option>
                            <option value="Sunda">Sunda</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-span-2">
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" 
                                  id="edit_deskripsi"
                                  rows="3"
                                  class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                  placeholder="Biarkan kosong jika tidak ada perubahan"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                    <button type="button" 
                            id="cancelEditBtn"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 hover:shadow-md">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-lg shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 hover:shadow-md">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Update Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white transform transition-all duration-300">
        <!-- Modal Content -->
        <div class="text-center">
            <!-- Warning Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Buku</h3>
            
            <!-- Message -->
            <div class="mb-6">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus buku 
                    <span id="bookTitleToDelete" class="font-semibold text-gray-700"></span>?
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Tindakan ini tidak dapat dibatalkan dan semua data buku akan dihapus permanen.
                </p>
            </div>
            
            <!-- Book Info -->
            <div id="deleteBookInfo" class="bg-gray-50 p-3 rounded-lg mb-6 hidden">
                <div class="text-left">
                    <p class="text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Informasi Buku:</p>
                    <p class="text-xs text-gray-700 mt-1" id="bookDetails"></p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-center space-x-3">
                <button type="button" 
                        id="cancelDeleteBtn"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                    Batal
                </button>
                <form id="deleteBookForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('addBookModal');
    const editModal = document.getElementById('editBookModal');
    const deleteModal = document.getElementById('deleteBookModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const closeEditBtn = document.getElementById('closeEditModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const addForm = document.getElementById('addBookForm');
    const editForm = document.getElementById('editBookForm');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteForm = document.getElementById('deleteBookForm');
    const bookTitleToDelete = document.getElementById('bookTitleToDelete');
    
    let currentBookId = null;
    
    // Animasi modal
    function animateModalOpen(modal) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = modal.querySelector('div[class*="shadow-lg"]');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }
    
    function animateModalClose(modal) {
        const modalContent = modal.querySelector('div[class*="shadow-lg"]');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Open add book modal
    openBtn.addEventListener('click', function() {
        animateModalOpen(addModal);
        document.body.style.overflow = 'hidden';
    });

    // Close modals
    function closeAddModal() {
        animateModalClose(addModal);
        document.body.style.overflow = 'auto';
        addForm.reset();
    }

    function closeEditModal() {
        animateModalClose(editModal);
        document.body.style.overflow = 'auto';
        editForm.reset();
        currentBookId = null;
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    closeBtn.addEventListener('click', closeAddModal);
    cancelBtn.addEventListener('click', closeAddModal);
    
    closeEditBtn.addEventListener('click', closeEditModal);
    cancelEditBtn.addEventListener('click', closeEditModal);
    
    cancelDeleteBtn.addEventListener('click', closeDeleteModal);

    // Close modal when clicking outside
    [addModal, editModal, deleteModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                if (modal === addModal) closeAddModal();
                else if (modal === editModal) closeEditModal();
                else closeDeleteModal();
            }
        });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!addModal.classList.contains('hidden')) closeAddModal();
            if (!editModal.classList.contains('hidden')) closeEditModal();
            if (!deleteModal.classList.contains('hidden')) closeDeleteModal();
        }
    });

    // Form validation for add book
    addForm.addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value;
        const penulis = document.getElementById('penulis').value;
        const tahun = document.getElementById('tahun').value;
        const stok = document.getElementById('stok').value;

        if (!judul.trim() || !penulis.trim() || !tahun || !stok) {
            e.preventDefault();
            showAlert('Harap lengkapi semua field yang wajib diisi!', 'error');
            return false;
        }
    });

    // Edit modal function
    window.showEditModal = function(bookId) {
        currentBookId = bookId;
        
        // Ambil data dari atribut data
        const row = document.querySelector(`tr[data-book-id="${bookId}"]`);
        if (!row) return;
        
        const bookData = JSON.parse(row.getAttribute('data-book-data'));
        
        // Set form action
        editForm.action = `/buku/${bookId}`;
        
        // Kosongkan semua field (karena user hanya perlu mengisi yang ingin diubah)
        document.getElementById('edit_judul').value = '';
        document.getElementById('edit_penulis').value = '';
        document.getElementById('edit_penerbit_id').value = '';
        document.getElementById('edit_kategori_id').value = '';
        document.getElementById('edit_tahun').value = '';
        document.getElementById('edit_isbn').value = '';
        document.getElementById('edit_stok').value = '';
        document.getElementById('edit_bahasa').value = '';
        document.getElementById('edit_deskripsi').value = '';
        
        // Set placeholder dengan nilai saat ini
        document.getElementById('edit_judul').placeholder = `Saat ini: ${bookData.judul}`;
        document.getElementById('edit_penulis').placeholder = `Saat ini: ${bookData.penulis}`;
        
        if (bookData.tahun) {
            document.getElementById('edit_tahun').placeholder = `Saat ini: ${bookData.tahun}`;
        }
        
        if (bookData.isbn) {
            document.getElementById('edit_isbn').placeholder = `Saat ini: ${bookData.isbn}`;
        }
        
        if (bookData.stok) {
            document.getElementById('edit_stok').placeholder = `Saat ini: ${bookData.stok}`;
        }
        
        if (bookData.deskripsi) {
            document.getElementById('edit_deskripsi').placeholder = `Saat ini: ${bookData.deskripsi.substring(0, 50)}...`;
        }
        
        animateModalOpen(editModal);
        document.body.style.overflow = 'hidden';
    };

    // Delete modal function
    window.showDeleteModal = function(bookId, bookTitle) {
        bookTitleToDelete.textContent = bookTitle;
        deleteForm.action = `/buku/${bookId}`;
        
        // Dapatkan informasi tambahan buku dari baris tabel
        const row = document.querySelector(`tr[data-book-id="${bookId}"]`);
        if (row) {
            const kategori = row.querySelector('span[class*="bg-blue-100"]')?.textContent || '-';
            const penulis = row.cells[1]?.textContent?.trim() || '-';
            const tahun = row.cells[0]?.querySelector('.text-gray-500')?.textContent?.split('•')[0]?.trim() || '-';
            
            document.getElementById('bookDetails').textContent = 
                `Penulis: ${penulis} | Tahun: ${tahun} | Kategori: ${kategori}`;
            document.getElementById('deleteBookInfo').classList.remove('hidden');
        }
        
        deleteModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    // Custom validation untuk form edit - hanya validasi jika ada isi
    editForm.addEventListener('submit', function(e) {
        const stok = document.getElementById('edit_stok').value;
        const tahun = document.getElementById('edit_tahun').value;
        
        // Validasi tahun jika diisi
        if (tahun && (tahun < 1900 || tahun > new Date().getFullYear())) {
            e.preventDefault();
            showAlert('Tahun terbit harus antara 1900 dan ' + new Date().getFullYear(), 'error');
            return false;
        }
        
        // Validasi stok jika diisi
        if (stok && stok < 0) {
            e.preventDefault();
            showAlert('Stok tidak boleh negatif', 'error');
            return false;
        }
        
        // Kirim hanya field yang diisi
        const formData = new FormData(editForm);
        const dataToSend = {};
        
        for (let [key, value] of formData.entries()) {
            if (value !== '') {
                dataToSend[key] = value;
            }
        }
        
        // Jika tidak ada yang diubah, tampilkan pesan
        if (Object.keys(dataToSend).length === 2) { // Hanya csrf dan method
            e.preventDefault();
            showAlert('Tidak ada perubahan yang dilakukan', 'info');
            return false;
        }
        
        // Jika ingin mengirim hanya data yang diisi, bisa gunakan AJAX
        // Atau biarkan form submit seperti biasa, lalu di controller filter null/empty values
    });

    // Alert function
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 
            type === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 
            'bg-blue-50 border border-blue-200 text-blue-700'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                               type === 'error' ? 'fa-exclamation-circle' : 
                               'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.classList.remove('translate-x-full');
        }, 10);
        
        setTimeout(() => {
            alertDiv.classList.add('translate-x-full');
            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        }, 3000);
    }

    // Hover effects for action buttons
    document.querySelectorAll('.delete-btn, .edit-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<style>
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
    }
    
    .duration-300 {
        transition-duration: 300ms;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
    
    /* Scale animations */
    .scale-95 {
        transform: scale(0.95);
    }
    
    .scale-100 {
        transform: scale(1);
    }
    
    /* Modal backdrop blur effect */
    #addBookModal, #editBookModal, #deleteBookModal {
        backdrop-filter: blur(4px);
    }
    
    /* Placeholder styling */
    input::placeholder, textarea::placeholder, select option:first-child {
        color: #9CA3AF;
        font-style: italic;
    }
    
    /* Current value display */
    .current-value {
        font-size: 0.875rem;
        color: #6B7280;
        margin-top: 0.25rem;
        font-style: italic;
    }
</style>
@endsection