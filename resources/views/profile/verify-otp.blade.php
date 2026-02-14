@extends('layouts.app')

@section('title', 'Verify OTP')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="signup-page-container register-page">
    <div class="signup-content-wrapper">
        <div class="signup-main-grid">
            <div class="form-section">
                <h1 class="signup-title">Verify New Number</h1>
                
                <p class="footer-text mb-4">Please enter the 4-digit code sent to your new WhatsApp number <strong>{{ session('profile_new_kontak') }}</strong></p>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.otp.process') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">OTP Code</label>
                        <input type="text" name="otp" placeholder="0 0 0 0" maxlength="4"
                               class="form-control text-center" 
                               style="letter-spacing: 15px; font-size: 32px; font-weight: 800;" required>
                    </div>

                    <button type="submit" class="btn-signup w-100 mb-3">Verify & Update <i class="bi bi-shield-check ms-2"></i></button>
                    
                    <div class="text-center">
                        <a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted">Cancel Update</a>
                    </div>
                </form>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
