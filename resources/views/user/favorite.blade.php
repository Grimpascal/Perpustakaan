@extends('Layouts.dbUser')

@section('title', $title)

@section('content')
<div class="max-w-6xl mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6">📌 Buku Favorit Anda</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        @forelse($favorites as $fav)
            <div class="bg-white shadow rounded-lg overflow-hidden border">

                <img
                    src="{{ $fav->book->cover ? asset('storage/' . $fav->book->cover) : 'https://via.placeholder.com/300x400' }}"
                    class="w-full h-60 object-cover"
                >

                <div class="p-4">
                    <h3 class="font-bold text-lg">{{ $fav->book->judul }}</h3>
                    <p class="text-gray-600 text-sm">Penulis: {{ $fav->book->penulis }}</p>

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('book.detail', $fav->book->id) }}"
                           class="px-3 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 text-sm">
                            Detail
                        </a>

                        <form method="POST" action="{{ route('user.favorite.remove', $fav->book->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                               class="px-3 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
                <p class="text-gray-500 col-span-3 text-center">Belum ada buku favorit.</p>
        @endforelse

    </div>

</div>
@endsection
