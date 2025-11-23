@extends('layouts.auth')

@section('title', $title)

@section('content')
    <div>
        <form action="{{ route('regisUser') }}" method="POST">
            @csrf
            <div>
                <label>Nama Lengkap</label><br>
                <input type="text" name="nama_lengkap" placeholder="Nama"><br>
                @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label>Username</label><br>
                <input type="text" name="username" placeholder="Masukan Username">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label>Email</label><br>
                <input type="email" name="email" placeholder="Masukan Email">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label>Password</label><br>
                <input type="text" name="password" placeholder="Masukan Password">
                <div>
                    <label>Konfirmasi Password</label><br>
                    <input type="text" name="password_confirmation" placeholder="Masukan Ulang Password"> 
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if(session('errorRegister'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('errorRegister') }}
                </div>
            @endif
            <div>
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
@endsection