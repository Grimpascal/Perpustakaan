@extends('layouts.dbAdmin')

@section('title', $title)

@section('content')
<div class="">
    <div class="overflow-x-auto bg-white shadow-xl rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Nama Lengkap
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                        Dibuat Pada
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                mau kutambahin fitur shorter
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                        {{ $user->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $user->nama_lengkap }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $user->username }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                @endforeach

                @if ($users->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Data pengguna belum tersedia.
                        </td>
                    </tr>
                @endif
                </tbody>
        </table>
    </div>
</div>
@endsection