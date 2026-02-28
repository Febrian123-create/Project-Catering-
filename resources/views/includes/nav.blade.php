<nav class="navbar-main">
    <div class="nav-container">
        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? url('/admin/dashboard') : url('/') }}" class="brand">dosinyam</a>

        {{-- Hamburger button (mobile only) --}}
        <button class="nav-hamburger" id="navHamburger" aria-label="Toggle menu" onclick="toggleMobileNav()">
            <span></span><span></span><span></span>
        </button>

        <div class="nav-links" id="navLinks">
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
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">Notifikasi!</a>
                @else
                    <a href="{{ route('menus.index') }}" class="{{ Request::is('menus*') ? 'active' : '' }}">Menu</a>
                    <a href="{{ route('cart.index') }}" class="{{ Request::is('cart*') ? 'active' : '' }}">Keranjang</a>
                    <a href="{{ route('orders.index') }}" class="{{ Request::is('orders*') ? 'active' : '' }}">Pesananku</a>
                    <a href="{{ route('requests.index') }}" class="{{ Request::is('requests*') ? 'active' : '' }}">Request-an</a>
                    <a href="{{ route('notifications.index') }}" class="{{ Request::is('notifications*') ? 'active' : '' }}">Notifikasi!</a>
                @endif

                <a href="{{ route('profile.index') }}" class="{{ Request::is('profile*') ? 'active' : '' }}">Profile</a>

                {{-- Mobile-only logout --}}
                <form action="{{ route('logout') }}" method="POST" class="d-none d-nav-mobile">
                    @csrf
                    <button type="submit" class="btn-nav-auth" style="border:none; background:none; cursor:pointer; width:100%; text-align:left; padding: 0.5rem 1rem;">Keluar</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<script>
function toggleMobileNav() {
    var links = document.getElementById('navLinks');
    var btn   = document.getElementById('navHamburger');
    var open  = links.classList.toggle('nav-open');
    btn.classList.toggle('active', open);
}
// Close on outside click
document.addEventListener('click', function(e) {
    var nav  = document.getElementById('navLinks');
    var btn  = document.getElementById('navHamburger');
    if (!nav.contains(e.target) && !btn.contains(e.target)) {
        nav.classList.remove('nav-open');
        btn.classList.remove('active');
    }
});
</script>
