@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <main class="container signup-container">
        <div class="form-section">
            <h1>Sign Up</h1>

            <form action="sign-up-logic.php" method="POST">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Create your username" required>
                    </div>
                    <div class="input-group">
                        <label>Contact</label>
                        <input type="text" name="contact" placeholder="Phone number" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <textarea name="address" placeholder="Address" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm password" required>
                </div>

                <button type="submit" class="btn-signin">Sign Up</button>
            </form>

            <p class="footer-text">Already have account? <a href="{{ route('login') }}">Sign In</a></p>
        </div>

        <div class="illustration-section">
            <img src="img/bento-landscape.png" alt="Bento Illustration">
        </div>
    </main>
@endsection
