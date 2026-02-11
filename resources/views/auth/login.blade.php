@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <main class="container">
        <div class="illustration-section">
            <picture>
                {{-- Tambahkan asset() agar gambar muncul --}}
                <source srcset="{{ asset('img/bento-landscape.png') }}" media="(max-width: 992px)">
                <img src="{{ asset('img/bento.png') }}" alt="Bento Illustration">
            </picture>
        </div>

        <div class="form-section">
            <h1>Sign In</h1>

            {{-- ACTION diubah ke route login, METHOD tetap POST --}}
            <form action="{{ route('login') }}" method="POST">
                @csrf {{-- TAMBAHKAN INI! Tanpa ini akan error 419 --}}

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-signin">Sign In</button>
            </form>

            {{-- Ganti link ke route register --}}
            <p class="footer-text">Don't have account? <a href="{{ route('register') }}">Sign Up</a></p>
        </div>
    </main>
@endsection
