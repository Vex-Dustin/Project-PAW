<div class="d-none d-md-block">
    <div class="list-group list-group-flush shadow-sm rounded-3 overflow-hidden bg-white">

        <a href="{{ route('dashboard') }}"
            class="list-group-item list-group-item-action {{ Request::is('dashboard*') || Request::is('admin/dashboard*') || Request::is('seller/dashboard*') ? 'active-maroon' : '' }}">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>

        @if (Auth::user()->role === 'admin')
            <div class="mt-3 px-3 small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Administrator</div>
            <a href="{{ route('admin.verifications.index') }}"
                class="list-group-item list-group-item-action {{ Request::is('admin/verifications*') ? 'active-maroon' : '' }}">
                <i class="bi bi-patch-check me-2"></i> Verifikasi Produk
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="list-group-item list-group-item-action {{ Request::is('admin/users*') ? 'active-maroon' : '' }}">
                <i class="bi bi-people me-2"></i> Kelola User
            </a>
        @endif

        @if (Auth::user()->role === 'penjual')
            <div class="mt-3 px-3 small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Toko Saya</div>
            <a href="{{ route('seller.products.index') }}"
                class="list-group-item list-group-item-action {{ Request::is('seller/products*') ? 'active-maroon' : '' }}">
                <i class="bi bi-box me-2"></i> Produk Saya
            </a>
            <a href="{{ route('seller.orders.index') }}"
                class="list-group-item list-group-item-action {{ Request::is('seller/orders*') ? 'active-maroon' : '' }}">
                <i class="bi bi-cart-check me-2"></i> Pesanan Masuk
            </a>
        @endif

        <div class="mt-3 px-3 small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Personal</div>

        {{-- UPDATE: Menggunakan profile.index --}}
        <a href="{{ route('profile.index') }}"
            class="list-group-item list-group-item-action {{ Request::is('profile*') ? 'active-maroon' : '' }}">
            <i class="bi bi-person-gear me-2"></i> Pengaturan Profil
        </a>

        <a href="{{ route('reports.index') }}"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ Request::is('reports*') ? 'active-maroon' : '' }}">
            <div>
                <i class="bi bi-exclamation-octagon me-2"></i>
                {{ Auth::user()->role === 'admin' ? 'Kelola Laporan' : 'Pusat Bantuan' }}
            </div>
            @if (Auth::user()->role === 'admin')
                @php $pendingReports = \App\Models\Report::where('status', 'pending')->count(); @endphp
                @if ($pendingReports > 0)
                    <span class="badge rounded-pill bg-danger" style="font-size: 0.65rem;">{{ $pendingReports }}</span>
                @endif
            @endif
        </a>

        <a href="#" class="list-group-item list-group-item-action text-danger"
            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="bi bi-box-arrow-right me-2"></i> Keluar
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</div>
