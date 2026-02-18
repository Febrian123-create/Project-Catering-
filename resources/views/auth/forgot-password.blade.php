@extends('layouts.app')

@section('title', 'Forgot Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="signup-page-container login-page">
    <div class="signup-content-wrapper">
        <div class="signup-main-grid">
            <div class="form-section">
                <h1 class="signup-title">Forgot Password</h1>
                <p class="footer-text mb-4">Masukkan username dan nomor WhatsApp yang terdaftar untuk verifikasi akun kamu.</p>

                @if (session('error'))
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        {{ session('error') }}
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

                <form action="{{ route('password.forgot.send') }}" method="POST">
                    @csrf
                    
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" value="{{ old('username') }}" required>

                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="kontak" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('kontak') }}" required>

                    <button type="submit" class="btn-signup w-100">Kirim OTP <i class="bi bi-whatsapp ms-2"></i></button>
                </form>

                <p class="footer-text">Ingat password? <a href="{{ route('login') }}"><strong>Sign In</strong></a></p>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
