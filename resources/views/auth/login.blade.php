@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle display-1 text-primary"></i>
                        <h3 class="mt-3">Login</h3>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                    id="username" name="username" value="{{ old('username') }}" 
                                    placeholder="Masukkan username" required autofocus>
                            </div>
                            @error('username')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
