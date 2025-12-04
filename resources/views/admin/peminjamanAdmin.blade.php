@extends('layouts.dbAdmin')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="container mx-auto px-0 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 px-4 sm:px-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Peminjaman</h1>
            <p class="text-gray-600 mt-1">Kelola semua transaksi peminjaman buku perpustakaan</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
            <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100 min-w-[120px]">
                <p class="text-xs text-gray-600 font-medium">Total</p>
                <p class="text-lg font-bold text-blue-600">{{ $totalPeminjaman ?? 0 }}</p>
            </div>
            <div class="bg-yellow-50 px-4 py-3 rounded-lg border border-yellow-100 min-w-[120px]">
                <p class="text-xs text-gray-600 font-medium">Dipinjam</p>
                <p class="text-lg font-bold text-yellow-600">{{ $peminjamanAktif ?? 0 }}</p>
            </div>
            <div class="bg-red-50 px-4 py-3 rounded-lg border border-red-100 min-w-[120px]">
                <p class="text-xs text-gray-600 font-medium">Terlambat</p>
                <p class="text-lg font-bold text-red-600">{{ $peminjamanTerlambat ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mx-4 sm:mx-6 mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 sm:mx-6 mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Filter dan Search -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6 mx-4 sm:mx-6">
        <div class="p-4">
            <form method="GET" action="{{ route('peminjaman.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nama, buku, ID..."
                                   class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" 
                                id="status"
                                class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="md:col-span-3">
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                        <select name="date_range" 
                                id="date_range"
                                class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Waktu</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="overdue" {{ request('date_range') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-2 flex items-end space-x-2">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('peminjaman.index') }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            <i class="fas fa-rotate-left mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Container -->
    <div class="mx-4 sm:mx-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                ID
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Peminjam
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Buku
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Tanggal
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Denda
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjamans as $peminjaman)
                            @php
                                // HITUNG STATUS TERLAMBAT
                                $isOverdue = false;
                                $hariTerlambat = 0;
                                
                                if ($peminjaman->status == 'dipinjam') {
                                    if (Carbon\Carbon::now()->greaterThan($peminjaman->tanggal_harus_kembali)) {
                                        $isOverdue = true;
                                        $hariTerlambat = Carbon\Carbon::now()->diffInDays($peminjaman->tanggal_harus_kembali);
                                    }
                                }
                                
                                // TENTUKAN STATUS YANG DITAMPILKAN
                                $displayStatus = $peminjaman->status;
                                if ($isOverdue) {
                                    $displayStatus = 'terlambat';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $displayStatus == 'terlambat' ? 'bg-red-50' : ($displayStatus == 'dikembalikan' ? 'bg-green-50' : '') }}">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-xs font-mono font-semibold text-gray-900">#{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600 text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $peminjaman->user->nama_lengkap ?? 'User Tidak Ditemukan' }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">
                                                {{ $peminjaman->user->email ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                            <i class="fas fa-book text-gray-500 text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $peminjaman->book->judul ?? 'Buku Tidak Ditemukan' }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">
                                                {{ $peminjaman->book->penulis ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-plus text-blue-500 mr-2 text-xs"></i>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                            <i class="fas fa-calendar-check {{ $isOverdue ? 'text-red-500' : 'text-green-500' }} mr-2 text-xs"></i>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($peminjaman->tanggal_harus_kembali)->format('d/m/Y') }}</span>
                                            @if($isOverdue)
                                                <span class="ml-2 text-xs font-medium text-red-600">
                                                    ({{ $hariTerlambat }} hari)
                                                </span>
                                            @endif
                                        </div>
                                        @if($peminjaman->tanggal_dikembalikan)
                                            <div class="flex items-center text-green-600">
                                                <i class="fas fa-undo mr-2 text-xs"></i>
                                                <span class="text-xs">{{ \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{-- TAMPILKAN STATUS BERDASARKAN $displayStatus --}}
                                    @if($displayStatus == 'terlambat')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                            Terlambat
                                        </span>
                                    @elseif($displayStatus == 'dipinjam')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-book-reader mr-1.5"></i>
                                            Dipinjam
                                        </span>
                                    @elseif($displayStatus == 'dikembalikan')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($peminjaman->denda > 0)
                                        <div class="text-sm font-semibold text-red-600">
                                            Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                        </div>
                                        @if($isOverdue && !$peminjaman->tanggal_dikembalikan)
                                            <div class="text-xs text-gray-500">
                                                Potensi: Rp {{ number_format($hariTerlambat * 10000, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    @else
                                        @if($isOverdue && !$peminjaman->tanggal_dikembalikan)
                                            <div class="text-sm font-semibold text-red-600">
                                                Rp {{ number_format($hariTerlambat * 10000, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ({{ $hariTerlambat }} × Rp 10.000)
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-1">
                                        
                                        <!-- Tombol Kembalikan -->
                                        @if($peminjaman->status == 'dipinjam')
                                            <button onclick="showReturnModal({{ $peminjaman->id }}, '{{ addslashes($peminjaman->book->judul ?? 'Buku') }}', {{ $isOverdue ? 'true' : 'false' }})"
                                                    class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50 transition"
                                                    title="Kembalikan Buku">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Tombol Hapus -->
                                        @if($peminjaman->status == 'dikembalikan')
                                            <button onclick="showDeleteModal({{ $peminjaman->id }})"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition"
                                                    title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-book-open text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium mb-1">Tidak ada data peminjaman</p>
                                        <p class="text-sm">Coba ubah filter pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($peminjamans->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 mb-2 sm:mb-0">
                            Menampilkan
                            <span class="font-medium">{{ $peminjamans->firstItem() ?? 0 }}</span>
                            sampai
                            <span class="font-medium">{{ $peminjamans->lastItem() ?? 0 }}</span>
                            dari
                            <span class="font-medium">{{ $peminjamans->total() }}</span>
                            data
                        </div>
                        <div class="flex space-x-2">
                            @if(!$peminjamans->onFirstPage())
                                <a href="{{ $peminjamans->previousPageUrl() }}" 
                                   class="px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-sm font-medium hover:bg-gray-200 transition">
                                    <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                                </a>
                            @endif
                            @if($peminjamans->hasMorePages())
                                <a href="{{ $peminjamans->nextPageUrl() }}" 
                                   class="px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-sm font-medium hover:bg-gray-200 transition">
                                    Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-6 px-4 sm:px-6">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Total data: <span class="font-medium">{{ $peminjamans->total() }}</span>
            </div>
            <button onclick="exportToExcel()"
                    class="inline-flex items-center px-4 py-2.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                <i class="fas fa-file-export mr-2"></i>
                Export Excel
            </button>
        </div>
    </div>
</div>

<!-- Modals (tetap sama) -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <!-- Modal Content will be loaded by JavaScript -->
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-undo text-green-600 text-xl"></i>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2" id="returnModalTitle">Konfirmasi Pengembalian</h3>
            
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-4" id="returnModalMessage"></p>
                
                <div id="dendaInfo" class="hidden bg-yellow-50 p-4 rounded-lg mb-4">
                    <div class="text-left">
                        <p class="text-sm font-medium text-yellow-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Terlambat Pengembalian
                        </p>
                        <p class="text-sm text-yellow-700" id="dendaDetails"></p>
                    </div>
                </div>
                
                <form id="returnForm" method="POST" action="" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="text-left">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan" 
                                  id="catatan"
                                  rows="3"
                                  class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tambah catatan jika diperlukan..."></textarea>
                    </div>
                    
                    <div class="text-left hidden" id="dendaInput">
                        <label for="denda" class="block text-sm font-medium text-gray-700 mb-1">Denda (Rp)</label>
                        <input type="number" 
                               name="denda" 
                               id="denda"
                               min="0"
                               step="1000"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </form>
            </div>
            
            <div class="flex justify-center space-x-3">
                <button type="button" 
                        onclick="closeReturnModal()"
                        class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="button" 
                        onclick="submitReturnForm()"
                        class="px-5 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Pengembalian
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-trash text-red-600 text-xl"></i>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Data Peminjaman</h3>
            
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-4">
                    Apakah Anda yakin ingin menghapus data peminjaman ini?
                </p>
                <p class="text-xs text-gray-400">
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
            
            <div class="flex justify-center space-x-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
</div>

<script>
let currentPeminjamanId = null;

// Detail Modal Functions
async function showDetailModal(id) {
    currentPeminjamanId = id;
    
    try {
        const response = await fetch(`/peminjaman/${id}/detail`);
        if (response.ok) {
            const data = await response.text();
            document.getElementById('detailModal').innerHTML = data;
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            alert('Gagal memuat detail peminjaman');
        }
    } catch (error) {
        console.error('Error loading detail:', error);
        alert('Gagal memuat detail peminjaman');
    }
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPeminjamanId = null;
}

// Return Modal Functions
function showReturnModal(id, bookTitle, isOverdue) {
    currentPeminjamanId = id;
    
    document.getElementById('returnModalTitle').textContent = `Konfirmasi Pengembalian`;
    document.getElementById('returnModalMessage').textContent = `Apakah Anda yakin ingin mengkonfirmasi pengembalian buku "${bookTitle}"?`;
    
    if (isOverdue) {
        // Hitung denda otomatis
        fetch(`/peminjaman/${id}/calculate-denda`)
            .then(response => response.json())
            .then(data => {
                if (data.denda > 0) {
                    document.getElementById('dendaInfo').classList.remove('hidden');
                    document.getElementById('dendaInput').classList.remove('hidden');
                    document.getElementById('dendaDetails').textContent = 
                        `Telat ${data.hari_telat} hari × Rp 10.000/hari = Rp ${data.denda.toLocaleString('id-ID')}`;
                    document.getElementById('denda').value = data.denda;
                }
            })
            .catch(error => {
                console.error('Error calculating denda:', error);
            });
    } else {
        document.getElementById('dendaInfo').classList.add('hidden');
        document.getElementById('dendaInput').classList.add('hidden');
    }
    
    document.getElementById('returnForm').action = `/peminjaman/${id}/return`;
    document.getElementById('returnModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPeminjamanId = null;
    document.getElementById('returnForm').reset();
    document.getElementById('dendaInfo').classList.add('hidden');
    document.getElementById('dendaInput').classList.add('hidden');
}

function submitReturnForm() {
    document.getElementById('returnForm').submit();
}

// Delete Modal Functions
function showDeleteModal(id) {
    currentPeminjamanId = id;
    document.getElementById('deleteForm').action = `/peminjaman/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPeminjamanId = null;
}

// Export to Excel
function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/peminjaman/export?${params.toString()}`, '_blank');
}

// Close modals when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});

document.getElementById('returnModal').addEventListener('click', function(e) {
    if (e.target === this) closeReturnModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeReturnModal();
        closeDeleteModal();
    }
});

// Auto-calculate denda
document.getElementById('denda')?.addEventListener('input', function(e) {
    const value = parseInt(e.target.value) || 0;
    if (value < 0) {
        e.target.value = 0;
    }
});

// Responsive table on mobile
function setupResponsiveTable() {
    if (window.innerWidth < 768) {
        const table = document.querySelector('table');
        const headers = document.querySelectorAll('thead th');
        const cells = document.querySelectorAll('tbody td');
        
        // Add data-label attribute to each cell
        cells.forEach((cell, index) => {
            const headerIndex = index % headers.length;
            const headerText = headers[headerIndex].textContent.trim();
            cell.setAttribute('data-label', headerText);
        });
    }
}

// Run on load and resize
window.addEventListener('load', setupResponsiveTable);
window.addEventListener('resize', setupResponsiveTable);
</script>

<style>
/* Responsive table for mobile */
@media (max-width: 768px) {
    table {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    thead {
        display: none;
    }
    
    tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background: white;
        padding: 0.5rem;
    }
    
    tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        text-align: right;
    }
    
    tbody td:last-child {
        border-bottom: none;
    }
    
    tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #374151;
        margin-right: 1rem;
        flex-shrink: 0;
        text-align: left;
    }
    
    /* Status badges on mobile */
    .inline-flex {
        margin-left: auto;
    }
    
    /* Action buttons on mobile */
    .flex.justify-end {
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
}

/* Ensure no horizontal scroll on desktop */
@media (min-width: 769px) {
    .container {
        max-width: calc(100vw - 18rem);
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    table {
        width: 100%;
        table-layout: auto;
    }
    
    td, th {
        max-width: 200px;
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

/* General styles for better layout */
html {
    overflow-x: hidden;
}

body {
    max-width: 100vw;
    overflow-x: hidden;
}

#detailModal,
#returnModal,
#deleteModal {
    z-index: 9999;
}

/* Modal backdrop */
.fixed.inset-0 {
    backdrop-filter: blur(2px);
}

/* Transition effects */
.transition {
    transition: all 0.2s ease-in-out;
}

/* Hover effects */
.hover\:bg-blue-50:hover {
    background-color: rgba(59, 130, 246, 0.05);
}

.hover\:bg-green-50:hover {
    background-color: rgba(34, 197, 94, 0.05);
}

.hover\:bg-red-50:hover {
    background-color: rgba(239, 68, 68, 0.05);
}

/* Loading state */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
@endsection