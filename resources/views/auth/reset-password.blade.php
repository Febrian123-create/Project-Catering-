@extends('layouts.app')

@section('title', 'Reset Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="signup-page-container login-page">
    <div class="signup-content-wrapper">
        <div class="signup-main-grid">
            <div class="form-section">
                <h1 class="signup-title">Reset Password</h1>
                <p class="footer-text mb-4">Buat password baru untuk akun kamu.</p>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.reset.update') }}" method="POST">
                    @csrf

                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required>

                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>

                    <button type="submit" class="btn-signup w-100">Simpan Password <i class="bi bi-check-circle ms-2"></i></button>
                </form>

                <p class="footer-text">Ingat password lama? <a href="{{ route('login') }}"><strong>Sign In</strong></a></p>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
