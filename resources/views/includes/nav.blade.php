<nav class="navbar-main">
    <div class="nav-container">
        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="brand">dosinyam</a>

        <div class="nav-links">
            @guest
                @if(Request::is('register'))
                    <a href="{{ route('login') }}" class="btn-nav-auth">Sign In</a>
                @elseif(Request::is('login'))
                    <a href="{{ route('register') }}" class="btn-nav-auth">Sign Up</a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-auth">Sign In</a>
                @endif
            @endguest

            @auth
                {{-- Home - Unified --}}
                <a href="{{ auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="{{ Request::is('/') || Request::is('admin/dashboard') ? 'active' : '' }}">home</a>

                {{-- Role-Based Links --}}
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.products.index') }}" class="{{ Request::is('admin/products*') ? 'active' : '' }}">produk</a>
                    <a href="{{ route('admin.menus.index') }}" class="{{ Request::is('admin/menus*') ? 'active' : '' }}">menu</a>
                    <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">request</a>
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">notifikasi</a>
                @else
                    <a href="{{ route('menus.index') }}" class="{{ Request::is('menus*') ? 'active' : '' }}">menus</a>
                    <a href="{{ route('cart.index') }}" class="{{ Request::is('cart*') ? 'active' : '' }}">cart</a>
                    <a href="{{ route('orders.index') }}" class="{{ Request::is('orders*') ? 'active' : '' }}">pesanan</a>
                    <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">request</a>
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">notifikasi</a>
                @endif

                <a href="{{ route('profile.index') }}" class="{{ Request::is('profile*') ? 'active' : '' }}">profile</a>
            @endauth
        </div>
    </div>
</nav>
