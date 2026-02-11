<nav class="navbar">
    {{-- Ganti link ke route home --}}
    <a class="brand" href="{{ route('home') }}">namanya apaya</a>

    <div class="nav-links">
        {{-- Logika otomatis: jika belum login, tampilkan tombol login/register --}}
        @guest
            @if(Route::currentRouteName() == 'login')
                <a href="{{ route('register') }}" class="btn-nav-auth">Sign Up</a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-auth">Sign In</a>
            @endif
        @else
            {{-- Jika sudah login, tampilkan nama user --}}
            <span class="text-white me-3">Halo, {{ Auth::user()->nama }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-nav-auth border-0">Logout</button>
            </form>
        @endguest
    </div>
</nav>
