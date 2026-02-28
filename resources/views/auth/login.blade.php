@extends('layouts.app')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="signup-page-container login-page">
    <div class="signup-content-wrapper">
        <div class="signup-main-grid">
            <div class="form-section">
                <h1 class="login-title">Sign In</h1>
                
                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukin username lo" value="{{ old('username') }}" required>

                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Ssttt... masukin password lo" required>

                    <div class="forgot-password-link">
                        <a href="{{ route('password.forgot') }}">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-signup w-100">Sign in! <i class="bi bi-box-arrow-in-right ms-2"></i></button>
                </form>

                <p class="footer-text">Belum punya akun? <a href="{{ route('register') }}"><strong>Yuk bikin dulu akunnya!</strong></a></p>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
