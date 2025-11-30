@extends('layouts.dbAdmin')

@section('title', $title)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
            Manajemen Pengguna
        </h1>
        <p class="text-gray-500 mt-1">Kelola data pengguna dan akses sistem</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-medium">
                {{ $users->total() }} Total Pengguna
            </span>
        </div>
        <!-- Tombol Tambah Pengguna -->
        <button onclick="openAddModal()" 
                class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pengguna
        </button>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden flex transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="addModalContent">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Pengguna Baru</h3>
                        <p class="text-sm text-gray-500 mt-1">Isi form untuk menambah pengguna baru</p>
                    </div>
                    <button onclick="closeAddModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Add User Form -->
                <form id="addUserForm" method="POST" action="{{ route('pengguna.tambah') }}">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap *
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan nama lengkap" required>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                Username *
                            </label>
                            <input type="text" id="username" name="username" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan username" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="email@example.com" required>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password *
                            </label>
                            <input type="password" id="password" name="password" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Minimal 8 karakter" required minlength="8">
                            <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Role *
                            </label>
                            <select id="role" name="role" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer transition-all duration-200">
                                <option value="">Pilih Role</option>
                                <option value="admin">Administrator</option>
                                <option value="user" selected>Pengguna</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeAddModal()" 
                                class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
            <div class="lg:col-span-8">
                <form action="{{ route('pengguna') }}" method="GET" class="flex gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari pengguna..." 
                               class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 transition-all duration-200"
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-linear-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Role Filter -->
            <div class="lg:col-span-4">
                <form action="{{ route('pengguna') }}" method="GET" id="roleFilter">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="relative">
                        <select name="role" onchange="document.getElementById('roleFilter').submit()" 
                                class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/50 appearance-none cursor-pointer transition-all duration-200">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(request('search') || request('role'))
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <span class="text-sm text-gray-600 font-medium">Filter aktif:</span>
            
            @if(request('search'))
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-blue-50 text-blue-700 border border-blue-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                "{{ request('search') }}"
                <a href="{{ route('pengguna', ['role' => request('role')]) }}" class="ml-2 hover:text-blue-900">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </span>
            @endif

            @if(request('role'))
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-purple-50 text-purple-700 border border-purple-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Role: {{ ucfirst(request('role')) }}
                <a href="{{ route('pengguna', ['search' => request('search')]) }}" class="ml-2 hover:text-purple-900">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </span>
            @endif

            <a href="{{ route('pengguna') }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200 ml-auto">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset Filter
            </a>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-linear-to-r from-gray-50 to-gray-75 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>User</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Bergabung
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="shrink-0">
                                    <div class="relative">
                                        <img class="h-10 w-10 rounded-xl bg-linear-to-br from-blue-100 to-purple-100" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_lengkap) }}&background=4f46e5&color=fff&bold=true&size=128" 
                                             alt="{{ $user->nama_lengkap }}">
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 group-hover:text-gray-700 transition-colors">
                                        {{ $user->nama_lengkap }}
                                    </div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono text-gray-600 bg-gray-50 px-2 py-1 rounded-lg inline-block">
                                {{ $user->username }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                {{ $user->role == 'admin' 
                                    ? 'bg-purple-100 text-purple-800 border border-purple-200' 
                                    : 'bg-green-100 text-green-800 border border-green-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                    {{ $user->role == 'admin' ? 'bg-purple-500' : 'bg-green-500' }}"></span>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <!-- Ganti button edit yang lama -->
                                <button onclick="openEditModal({{ $user->id }}, {{ json_encode($user->nama_lengkap) }}, {{ json_encode($user->username) }}, {{ json_encode($user->email) }}, {{ json_encode($user->role) }})" 
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 tooltip" 
                                        data-tooltip="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="openDeleteModal({{ $user->id }}, {{ json_encode($user->nama_lengkap) }})" 
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 tooltip" 
                                        data-tooltip="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if ($users->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-2">Tidak ada pengguna ditemukan</p>
                                <p class="text-sm text-gray-400 max-w-sm">
                                    @if(request('search') || request('role'))
                                        Coba ubah kata kunci pencarian atau filter role yang digunakan
                                    @else
                                        Belum ada pengguna terdaftar dalam sistem
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} hasil
                </div>
                <div class="flex items-center space-x-1">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden flex transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="editModalContent">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Pengguna</h3>
                    <p class="text-sm text-gray-500 mt-1">Ubah data pengguna</p>
                </div>
                <button onclick="closeEditModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Edit User Form -->
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="edit_nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" id="edit_nama_lengkap" name="nama_lengkap" 
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username
                        </label>
                        <input type="text" id="edit_username" name="username" 
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="Masukkan username">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" id="edit_email" name="email" 
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="email@example.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru
                        </label>
                        <input type="password" id="edit_password" name="password" 
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">
                            Role
                        </label>
                        <select id="edit_role" name="role" 
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer transition-all duration-200">
                            <option value="">Pilih Role</option>
                            <option value="admin">Administrator</option>
                            <option value="user">Pengguna</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden flex transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="p-6">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Pengguna</h3>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus 
                    <span class="font-semibold text-gray-800" id="userName"></span>?
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Tindakan ini tidak dapat dibatalkan. Semua data pengguna akan dihapus secara permanen.
                </p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-linear-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .tooltip {
        position: relative;
    }
    
    .tooltip::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #374151;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }
    
    .tooltip:hover::before {
        opacity: 1;
        visibility: visible;
        bottom: calc(100% + 5px);
    }
    
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px 16px;
        padding-right: 40px;
    }
</style>

<script>
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        const userNameElement = document.getElementById('userName');
        const deleteForm = document.getElementById('deleteForm');
        
        // Set user data
        userNameElement.textContent = userName;
        deleteForm.action = `/pengguna/${userId}`;
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
        
        // Prevent background scroll
        document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        
        // Hide modal with animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Add Modal functions
function openAddModal() {
    const modal = document.getElementById('addModal');
    const modalContent = document.getElementById('addModalContent');
    
    // Reset form
    document.getElementById('addUserForm').reset();
    
    // Show modal with animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 50);
    
    // Prevent background scroll
    document.body.style.overflow = 'hidden';
}

    function closeAddModal() {
        const modal = document.getElementById('addModal');
        const modalContent = document.getElementById('addModalContent');
        
        // Hide modal with animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('addModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddModal();
        }
    });

    // Close modal with Escape key (update existing)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('addModal').classList.contains('hidden')) {
                closeAddModal();
            } else if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });

    // Edit Modal functions
function openEditModal(userId, namaLengkap, username, email, role) {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    const editForm = document.getElementById('editUserForm');
    
    // Set form action
    editForm.action = `/pengguna/${userId}`;
    
    // Fill form with current data
    document.getElementById('edit_nama_lengkap').value = namaLengkap || '';
    document.getElementById('edit_username').value = username || '';
    document.getElementById('edit_email').value = email || '';
    document.getElementById('edit_role').value = role || '';
    document.getElementById('edit_password').value = '';
    
    // Show modal with animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 50);
    
    // Prevent background scroll
    document.body.style.overflow = 'hidden';
}

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('editModalContent');
        
        // Hide modal with animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Update Escape key handler untuk semua modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('addModal').classList.contains('hidden')) {
                closeAddModal();
            } else if (!document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            } else if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });
</script>
@endsection