@extends('Layouts.dbAdmin')

@section('title', $title)

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h2 class="text-3xl font-bold text-gray-800 mb-6">Profil Saya</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-xl p-6 border">
        <div class="flex flex-col items-center mb-6">
            <div class="relative">
                @if($user->photo)
                    <img src="{{ asset('uploads/profile/' . $user->photo) }}"
                         class="w-32 h-32 object-cover rounded-full shadow-md border">
                @else
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                         class="w-32 h-32 rounded-full shadow-md border">
                @endif

                <label class="absolute bottom-1 right-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-full cursor-pointer hover:bg-blue-600 transition">
                    Ubah
                    <input type="file" name="photo" class="hidden" form="profileForm">
                </label>
            </div>

            <h3 class="text-2xl font-semibold mt-3 text-gray-800">{{ $user->nama_lengkap }}</h3>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
        </div>

        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-4 mt-4">
                <div>
                    <label class="font-medium text-gray-600">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}"
                        class="w-full mt-1 px-4 py-2 rounded-lg border focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="font-medium text-gray-600">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full mt-1 px-4 py-2 rounded-lg border focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Tambahan Info Akun --}}
    <div class="bg-white mt-6 p-5 shadow-md rounded-xl border">
        <h3 class="text-xl font-semibold text-gray-700 mb-3">Informasi Akun</h3>

        <p class="text-gray-600 text-sm"><strong>Dibuat pada:</strong> {{ $user->created_at->format('d M Y') }}</p>
    </div>

</div>
@endsection
