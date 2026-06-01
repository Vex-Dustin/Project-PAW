<div
    class="bottom-nav d-md-none fixed-bottom bg-white border-top d-flex justify-content-around align-items-center py-2 shadow-lg">

    {{-- 1. BERANDA (Semua Role) --}}
    <a href="{{ route('home') }}" class="nav-item-mobile {{ Request::is('/') ? 'active' : '' }}">
        <i class="bi {{ Request::is('/') ? 'bi-house-door-fill' : 'bi-house-door' }}"></i>
        <span>Beranda</span>
    </a>

    @auth
        @if (Auth::user()->role === 'admin')
            {{-- NAVIGASI ADMIN --}}
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item-mobile {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Panel</span>
            </a>
            <a href="{{ route('admin.verifications.index') }}"
                class="nav-item-mobile {{ Request::is('admin/verifications*') ? 'active' : '' }}">
                <i class="bi bi-patch-check"></i>
                <span>Verifikasi</span>
            </a>
        @else
            {{-- NAVIGASI PENJUAL / PEMBELI --}}
            @if (Auth::user()->role === 'penjual')
                <a href="{{ route('seller.dashboard') }}"
                    class="nav-item-mobile {{ Request::is('seller/dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Toko</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-item-mobile {{ Request::is('dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-columns-gap"></i>
                    <span>Dashboard</span>
                </a>
            @endif

            {{-- Akses Keranjang --}}
            <a href="{{ route('cart.index') }}"
                class="nav-item-mobile {{ Request::is('cart*') ? 'active' : '' }} position-relative">
                <i class="bi bi-cart3"></i>
                <span>Keranjang</span>
                @php
                    $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                @endphp
                @if ($cartCount > 0)
                    <span class="position-absolute top-0 start-50 translate-middle-x badge rounded-pill bg-danger"
                        style="font-size: 0.6rem; margin-left: 10px; margin-top: 5px;">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- Pesanan Saya --}}
            <a href="{{ route('order.index') }}" class="nav-item-mobile {{ Request::is('orders*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i>
                <span>Pesanan</span>
            </a>
        @endif
    @else
        {{-- GUEST --}}
        <a href="{{ route('login') }}" class="nav-item-mobile">
            <i class="bi bi-cart3"></i><span>Keranjang</span>
        </a>
    @endauth

    {{-- AKUN (Semua Role) --}}
    <a href="{{ route('profile.index') }}" class="nav-item-mobile {{ Request::is('profile*') ? 'active' : '' }}">
        <i class="bi {{ Request::is('profile*') ? 'bi-person-fill' : 'bi-person' }}"></i>
        <span>Akun</span>
    </a>
</div>
