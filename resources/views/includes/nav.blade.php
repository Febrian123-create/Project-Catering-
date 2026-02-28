<nav class="navbar navbar-expand-md navbar-main px-4 px-md-5">
    <div class="container-fluid p-0">
        {{-- Brand --}}
        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="brand navbar-brand me-auto">dosinyam</a>

        {{-- Bootstrap Toggler (mobile) --}}
        <button class="navbar-toggler border-2 border-dark rounded-3 p-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavLinks"
                aria-controls="mainNavLinks" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav Links --}}
        <div class="collapse navbar-collapse" id="mainNavLinks">
            <div class="navbar-nav ms-auto d-flex flex-column flex-md-row align-items-start align-items-md-center gap-1 gap-md-4 py-3 py-md-0">
                @guest
                    @if(Request::is('register'))
                        <a href="{{ route('login') }}" class="btn-nav-auth nav-link">Masuk!</a>
                    @elseif(Request::is('login'))
                        <a href="{{ route('register') }}" class="btn-nav-auth nav-link">Daftar!</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-auth nav-link">Masuk!</a>
                    @endif
                @endguest

                @auth
                    <a href="{{ auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}"
                       class="nav-link fw-bold {{ Request::is('/') || Request::is('admin/dashboard') ? 'active' : '' }}">Beranda</a>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.products.index') }}" class="nav-link fw-bold {{ Request::is('admin/products*') ? 'active' : '' }}">Koleksi</a>
                        <a href="{{ route('admin.menus.index') }}" class="nav-link fw-bold {{ Request::is('admin/menus*') ? 'active' : '' }}">Menu</a>
                        <a href="{{ route('admin.orders.index') }}" class="nav-link fw-bold {{ Request::is('admin/orders*') ? 'active' : '' }}">Pesanan</a>
                        <a href="{{ route('admin.reviews.index') }}" class="nav-link fw-bold {{ Request::is('admin/reviews*') ? 'active' : '' }}">Ulasan</a>
                        <a href="{{ route('requests.index') }}" class="nav-link fw-bold {{ Request::is('requests*') ? 'active' : '' }}">Request-an</a>
                        <a href="{{ route('notifications.index') }}" class="nav-link fw-bold {{ Request::is('notifications*') ? 'active' : '' }}">Notifikasi!</a>
                    @else
                        <a href="{{ route('menus.index') }}" class="nav-link fw-bold {{ Request::is('menus*') ? 'active' : '' }}">Menu</a>
                        <a href="{{ route('cart.index') }}" class="nav-link fw-bold {{ Request::is('cart*') ? 'active' : '' }}">Keranjang</a>
                        <a href="{{ route('orders.index') }}" class="nav-link fw-bold {{ Request::is('orders*') ? 'active' : '' }}">Pesananku</a>
                        <a href="{{ route('requests.index') }}" class="nav-link fw-bold {{ Request::is('requests*') ? 'active' : '' }}">Request-an</a>
                        <a href="{{ route('notifications.index') }}" class="nav-link fw-bold {{ Request::is('notifications*') ? 'active' : '' }}">Notifikasi!</a>
                    @endif

                    <a href="{{ route('profile.index') }}" class="nav-link fw-bold {{ Request::is('profile*') ? 'active' : '' }}">Profile</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
