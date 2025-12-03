@extends('Layouts.dbUser')

@section('title', $title)

@section('content')

<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-4">Riwayat Peminjaman</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 border">Judul Buku</th>
                <th class="py-2 px-4 border">Tanggal Pinjam</th>
                <th class="py-2 px-4 border">Pengembalian</th>
                <th class="py-2 px-4 border">Status</th>
                <th class="py-2 px-4 border">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($peminjamans as $p)
            <tr>
                <td class="py-2 px-4 border">{{ $p->book->judul }}</td>
                <td class="py-2 px-4 border">{{ $p->tanggal_pinjam }}</td>
                <td class="py-2 px-4 border">{{ $p->tanggal_kembali }}</td>
                <td class="py-2 px-4 border">
                    @if($p->status == 'dipinjam')
                        <span class="text-yellow-600 font-bold">Dipinjam</span>
                    @else
                        <span class="text-green-600 font-bold">Dikembalikan</span>
                    @endif
                </td>
                <td class="py-2 px-4 border text-center">
                    @if($p->status == 'dipinjam')
                    <form method="POST" action="{{ route('user.kembalikan', $p->id) }}">
                        @csrf
                        <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Kembalikan
                        </button>
                    </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-3 text-gray-500">
                        Belum ada peminjaman
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
