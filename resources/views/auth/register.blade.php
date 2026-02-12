@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <main class="container signup-container">
        <div class="form-section">
            <h1>Sign Up</h1>
            @if ($errors->any())
                <div style="color: red; background: #ffe6e6; padding: 10px; margin-bottom: 10px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('register.process') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="nama" placeholder="Full Name" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Create your username" required>
                    </div>
                    <div class="input-group">
                        <label>Contact</label>
                        <input type="text" name="kontak" placeholder="Phone number" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <textarea name="alamat_default" placeholder="Address" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                </div>

                <button type="submit" class="btn-signin">Sign Up & Send OTP code</button>
            </form>

            <p class="footer-text">Already have account? <a href="{{ route('login') }}">Sign In</a></p>
        </div>

        <div class="illustration-section">
            <img src="img/bento-landscape.png" alt="Bento Illustration">
        </div>
    </main>
@endsection
