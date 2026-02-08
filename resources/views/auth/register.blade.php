@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus display-1 text-primary"></i>
                        <h3 class="mt-3">Daftar Akun</h3>
                        <p class="text-muted">Buat akun baru untuk mulai memesan</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                id="nama" name="nama" value="{{ old('nama') }}" 
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                id="username" name="username" value="{{ old('username') }}" 
                                placeholder="Pilih username" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kontak" class="form-label">Nomor Telepon <span class="text-muted">(opsional)</span></label>
                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" 
                                id="kontak" name="kontak" value="{{ old('kontak') }}" 
                                placeholder="Contoh: 081234567890">
                            @error('kontak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat_default" class="form-label">Alamat <span class="text-muted">(opsional)</span></label>
                            <textarea class="form-control @error('alamat_default') is-invalid @enderror" 
                                id="alamat_default" name="alamat_default" rows="2" 
                                placeholder="Masukkan alamat lengkap">{{ old('alamat_default') }}</textarea>
                            @error('alamat_default')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" 
                                id="password_confirmation" name="password_confirmation" 
                                placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Daftar
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
