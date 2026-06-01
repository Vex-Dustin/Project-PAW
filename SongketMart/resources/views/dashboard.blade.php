@extends('app.master')

@section('title', 'Dashboard Pembeli')

@section('content')
    <div class="d-md-flex align-items-start gap-4">
        {{-- Sidebar --}}
        <div class="d-none d-md-block flex-shrink-0" style="width: 260px;">
            @include('app.sidebar')
        </div>

        {{-- Konten Utama --}}
        <div class="flex-grow-1">
            <div class="mb-4">
                <h2 class="fw-bold text-dark m-0">Dashboard Saya</h2>
                <p class="text-muted">Halo {{ Auth::user()->name }}, selamat datang kembali!</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Akun</h5>
                <p class="mb-0 text-muted">Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.</p>
            </div>

            <div class="row g-3">
                {{-- Menu Pesanan --}}
                <div class="col-6 col-lg-3">
                    <a href="{{ route('order.index') }}"
                        class="card border-0 shadow-sm rounded-4 p-4 text-center text-decoration-none h-100 bg-white shadow-hover">
                        <i class="bi bi-bag-check text-success fs-1 mb-2"></i>
                        <div class="fw-bold text-dark small">Pesanan Saya</div>
                    </a>
                </div>

                {{-- Menu Keranjang --}}
                <div class="col-6 col-lg-3">
                    <a href="{{ route('cart.index') }}"
                        class="card border-0 shadow-sm rounded-4 p-4 text-center text-decoration-none h-100 bg-white shadow-hover">
                        <i class="bi bi-cart3 text-danger fs-1 mb-2"></i>
                        <div class="fw-bold text-dark small">Keranjang</div>
                    </a>
                </div>

                {{-- MENU BARU: Pusat Bantuan (Laporan) --}}
                <div class="col-6 col-lg-3">
                    <a href="{{ route('reports.index') }}"
                        class="card border-0 shadow-sm rounded-4 p-4 text-center text-decoration-none h-100 bg-white shadow-hover">
                        <i class="bi bi-headset text-warning fs-1 mb-2"></i>
                        <div class="fw-bold text-dark small">Pusat Bantuan</div>
                    </a>
                </div>

                {{-- Menu Pengaturan --}}
                <div class="col-6 col-lg-3">
                    <a href="{{ route('profile.index') }}"
                        class="card border-0 shadow-sm rounded-4 p-4 text-center text-decoration-none h-100 bg-white shadow-hover">
                        <i class="bi bi-person-gear text-primary fs-1 mb-2"></i>
                        <div class="fw-bold text-dark small">Pengaturan</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
