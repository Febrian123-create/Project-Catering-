<nav class="navbar-main">
    <div class="nav-container">
        <a href="/" class="brand">dosinyam</a>

        <div class="nav-links">
            @guest
                @if(Request::is('register'))
                    <a href="{{ route('login') }}">Sign In</a>
                @else
                    <a href="{{ route('register') }}" class="{{ Request::is('register') ? 'active' : '' }}">Sign Up</a>
                @endif
            @endguest

            @auth
                <a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">home</a>
                <a href="{{ route('menus.index') }}" class="{{ Request::is('menus*') ? 'active' : '' }}">menus</a>
                <a href="{{ route('cart.index') }}" class="{{ Request::is('cart*') ? 'active' : '' }}">cart</a>
                <a href="{{ route('orders.index') }}" class="{{ Request::is('orders*') ? 'active' : '' }}">buku pesanan</a>
                <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">request</a>
                <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">notifikasi</a>

                @if(Auth::user()->isSeller())
                    <a href="{{ route('seller.dashboard') }}" class="{{ Request::is('seller*') ? 'fw-bold text-primary' : '' }}">Statistik (Admin)</a>
                @endif

                {{-- <a href="{{ route('profile') }}" class="{{ Request::is('profile*') ? 'active' : '' }}">profile</a> --}}
                <a href="#" class="">profile</a>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="color: var(--primary-orange); font-weight: 800; margin-left: 20px;">
                    logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endauth
        </div>
    </div>
</nav>
