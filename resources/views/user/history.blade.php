@extends('Layouts.dbUser')

@section('title', $title)

@section('content')
<div class="max-w-5xl mx-auto mt-10">

    <h2 class="text-2xl font-semibold mb-6">Riwayat Peminjaman</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 bg-white rounded-lg shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Judul Buku</th>
                    <th class="p-3 text-left">Tanggal Pinjam</th>
                    <th class="p-3 text-left">Tanggal Jatuh Tempo</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Denda</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($peminjaman as $item)
                @php
                    $jatuhTempo = \Carbon\Carbon::parse($item->tanggal_pinjam)->addDays(7);
                @endphp

                <tr class="border-t">
                    <td class="p-3">{{ $item->book->judul }}</td>
                    <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="p-3">{{ $jatuhTempo->format('d M Y') }}</td>

                    <td class="p-3 text-center">
                        @if($item->status == 'dipinjam')
                            <span class="px-3 py-1 rounded bg-blue-200 text-blue-800 text-sm">Dipinjam</span>
                        @elseif($item->status == 'telat')
                            <span class="px-3 py-1 rounded bg-red-200 text-red-800 text-sm">Telat</span>
                        @else
                            <span class="px-3 py-1 rounded bg-green-200 text-green-800 text-sm">Dikembalikan</span>
                        @endif
                    </td>

                    <td class="p-3 text-center">
                        Rp {{ number_format($item->denda, 0, ',', '.') }}
                    </td>

                    <td class="p-3 text-center">
                        @if($item->status == 'dipinjam' || $item->status == 'telat')
                            <form action="{{ route('user.kembalikan', $item->id) }}" method="POST">
                                @csrf
                                <button
                                    class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded"
                                    onclick="return confirm('Kembalikan buku ini?')">
                                    Kembalikan
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm">Selesai</span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-gray-500">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection
