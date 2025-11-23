@extends('layouts.auth')

@section('title', $title)

@section('content')
    <div>
        <form action="{{ route('regisUser') }}" method="POST">
            @csrf
            <div>
                <label>Nama Lengkap</label><br>
                <input type="text" name="nama_lengkap" placeholder="Nama"><br>
            </div>
            <div>
                <label>Username</label><br>
                <input type="text" name="username" placeholder="Masukan Username">
            </div>
            <div>
                <label>Email</label><br>
                <input type="email" name="email" placeholder="Masukan Email">
            </div>
            <div>
                <label>Password</label><br>
                <input type="text" name="password" placeholder="Masukan Password">
            </div>
            <div>
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
@endsection