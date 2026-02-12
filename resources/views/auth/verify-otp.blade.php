@extends('layouts.app')

@section('title', 'OTP Verification')

@section('content')
    <main class="container signup-container"> <div class="form-section">
            <h1>Verify OTP</h1>
            <p style="text-align: center">Please enter the 4-digit code sent to your WhatsApp at <strong>{{ session('pending_user_kontak') }}</strong></p>

            @if ($errors->any())
                <div style="color: red; background: #ffe6e6; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('otp.verify.process') }}" method="POST">
                @csrf
                <div class="input-group" style="text-align: center;">
                    <label style="display: block; width: 100%;">OTP Code</label>
                    <input type="text" name="otp_input" placeholder="0 0 0 0" maxlength="4"
                           style="text-align: center; letter-spacing: 10px; font-size: 24px;" required>
                </div>

                <button type="submit" class="btn-signin">Verify Account</button>
            </form>

            <p class="footer-text">Wrong number? <a href="{{ route('register') }}">Register again</a></p>
        </div>

        <div class="illustration-section">
            <img src="img/bento-landscape.png" alt="Bento Illustration">
        </div>
    </main>
@endsection
