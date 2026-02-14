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
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" value="{{ old('username') }}" required>

                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>

                    <button type="submit" class="btn-signup w-100">Sign In <i class="bi bi-box-arrow-in-right ms-2"></i></button>
                </form>

                <p class="footer-text">Don't have an account? <a href="{{ route('register') }}"><strong>Sign Up</strong></a></p>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
