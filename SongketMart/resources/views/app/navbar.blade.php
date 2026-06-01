<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"
            style="color: var(--primary-maroon); font-size: 1.5rem;">
            <i class="bi bi-shop me-2"></i>SongketMart
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'fw-bold text-dark' : '' }}"
                        href="{{ route('home') }}">Produk</a>
                </li>

                {{-- Keranjang: Muncul untuk SEMUA kecuali Admin --}}
                @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin'))
                    <li class="nav-item me-lg-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="d-md-none ms-2">Keranjang</span>
                            @auth
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                                @endphp
                                @if ($cartCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.65rem;">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                    </li>
                @endif

                @guest
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-maroon px-4 rounded-pill">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('register') }}" class="btn btn-sm btn-maroon px-4 rounded-pill text-white"
                            style="background-color: var(--primary-maroon);">Daftar</a>
                    </li>
                @else
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px;">
                                <i class="bi bi-person-fill text-secondary"></i>
                            </div>
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </a>

                        {{-- 🔥 PERBAIKAN ADA DI BARIS BAWAH INI (Penambahan style="z-index: 1050;") 🔥 --}}
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2 py-2"
                            style="z-index: 1050;">
                            <li>
                                <div class="dropdown-header border-bottom mb-2 pb-2">
                                    <small class="text-muted d-block mb-1">Masuk sebagai:</small>
                                    <span class="badge rounded-pill text-uppercase px-3 py-2"
                                        style="background-color: #fff0f0; color: var(--primary-maroon); font-size: 0.7rem; font-weight: 700; border: 1px solid rgba(128,0,0,0.1);">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>
                            </li>

                            {{-- Menu Berdasarkan Role --}}
                            @if (Auth::user()->role === 'admin')
                                <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i
                                            class="bi bi-grid-1x2 me-2 text-muted"></i>Dashboard Admin</a></li>
                            @elseif(Auth::user()->role === 'penjual')
                                <li><a class="dropdown-item py-2" href="{{ route('seller.dashboard') }}"><i
                                            class="bi bi-grid-1x2 me-2 text-muted"></i>Dashboard Toko</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('order.index') }}"><i
                                            class="bi bi-bag-check me-2 text-muted"></i>Pesanan Saya</a></li>
                            @else
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i
                                            class="bi bi-grid-1x2 me-2 text-muted"></i>Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('order.index') }}"><i
                                            class="bi bi-bag-check me-2 text-muted"></i>Pesanan Saya</a></li>
                            @endif

                            <li>
                                <hr class="dropdown-divider opacity-50">
                            </li>

                            <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}"><i
                                        class="bi bi-person-gear me-2 text-muted"></i>Pengaturan Akun</a></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="px-2 mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                        <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
