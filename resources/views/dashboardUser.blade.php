@extends('Layouts.db')

@section('title', $title)

<form method="POST" action="{{ route('logout') }}" class="mt-6">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">
        <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
    </button>
</form>