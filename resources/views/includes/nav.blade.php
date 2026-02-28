<nav class="navbar-main">
    <div class="nav-container">
        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="brand">dosinyam</a>

        <div class="nav-links">
            @guest
                @if(Request::is('register'))
                    <a href="{{ route('login') }}" class="btn-nav-auth">Masuk!</a>
                @elseif(Request::is('login'))
                    <a href="{{ route('register') }}" class="btn-nav-auth">Daftar!</a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-auth">Masuk!</a>
                @endif
            @endguest

            @auth
                {{-- Home - Unified --}}
                <a href="{{ auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="{{ Request::is('/') || Request::is('admin/dashboard') ? 'active' : '' }}">Beranda</a>

                {{-- Role-Based Links --}}
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.products.index') }}" class="{{ Request::is('admin/products*') ? 'active' : '' }}">Koleksi</a>
                    <a href="{{ route('admin.menus.index') }}" class="{{ Request::is('admin/menus*') ? 'active' : '' }}">Menu</a>
                    <a href="{{ route('admin.orders.index') }}" class="{{ Request::is('admin/orders*') ? 'active' : '' }}">Pesanan</a>
                    <a href="{{ route('admin.reviews.index') }}" class="{{ Request::is('admin/reviews*') ? 'active' : '' }}">Ulasan</a>
                    <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">Request-an</a>
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">notifikasi!</a>
                @else
                    <a href="{{ route('menus.index') }}" class="{{ Request::is('menus*') ? 'active' : '' }}">Menu</a>
                    <a href="{{ route('cart.index') }}" class="{{ Request::is('cart*') ? 'active' : '' }}">Keranjang</a>
                    <a href="{{ route('orders.index') }}" class="{{ Request::is('orders*') ? 'active' : '' }}">Pesananku</a>
                    <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">Request-an</a>
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">Notifikasi!</a>
                @endif

                <a href="{{ route('profile.index') }}" class="{{ Request::is('profile*') ? 'active' : '' }}">Profile</a>
            @endauth
        </div>
    </div>
</nav>
