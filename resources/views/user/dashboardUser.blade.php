@extends('layouts.dbUser')

@section('title', $title)

@section('content')
    <div class="max-w-6xl mx-auto mt-10">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Welcome, {{ Auth::user()->name }} 👋</h1>
                <p class="text-gray-600">Explore and borrow your favorite books.</p>
            </div>

            {{-- LOGOUT BUTTON --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        {{-- SEARCH BAR --}}
        <div class="mb-6">
            <form action="{{ route('dashboard') }}" method="GET">
                <input 
                    type="text"
                    name="search"
                    placeholder="Search books..."
                    class="w-full px-4 py-2 border rounded-lg shadow-sm"
                    value="{{ request('search') }}"
                >
            </form>
        </div>

        {{-- BOOK LIST --}}
        <h2 class="text-2xl font-semibold mb-4">Available Books</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        @forelse($books as $book)
            <div class="bg-white shadow rounded-xl overflow-hidden border">

                {{-- BOOK IMAGE --}}
                <img 
                    src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/300x400' }}" 
                    class="w-full h-60 object-cover"
                >

                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">{{ $book->judul }}</h3>

                    <p class="text-sm text-gray-600">Author: {{ $book->penulis }}</p>
                    <p class="text-sm text-gray-600">Year: {{ $book->tahun }}</p>

                    {{-- STATUS --}}
                    <p class="mt-2">
                        @if($book->status == 'available')
                            <span class="px-3 py-1 text-sm bg-green-200 text-green-800 rounded-full">
                                Available
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">
                                Borrowed
                            </span>
                        @endif
                    </p>

                    {{-- ACTION BUTTONS --}}
                    <div class="mt-4 flex justify-between">

                        {{-- BORROW --}}
                        @if($book->status == 'available')
                            <form method="POST" action="{{ route('user.pinjam', $book->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                                    Borrow
                                </button>
                            </form>
                        @else
                            <button class="px-3 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed text-sm">
                                Unavailable
                            </button>
                        @endif

                        {{-- FAVORITE --}}
                        <form method="POST" action="{{ route('user.favorite.add', $book->id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm">
                                ★ Favorite
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <p class="text-gray-600 text-center col-span-3">No books found.</p>
        @endforelse

    </div>

</div>
@endsection