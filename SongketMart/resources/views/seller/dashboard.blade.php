@extends('app.seller_layout')

@section('title', 'Dashboard Penjual - SongketMart')

@section('seller_content')
<div class="card border-0 shadow-sm rounded-4 p-3 p-md-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">Dashboard {{ ucfirst(Auth::user()->role) }}</h1>
        <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-md-none">
            <i class="bi bi-person-circle me-1"></i> Ke Profil
        </a>
    </div>

    {{-- 1. Bagian Header Dashboard --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 h4" style="color: var(--primary-maroon);">
            <i class="bi bi-grid-1x2-fill me-2"></i>Ringkasan Toko
        </h2>
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
            <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
        </span>
    </div>

    {{-- ======================================================== --}}
    {{-- 2. MENU PINTASAN MOBILE (Hanya Tampil di Layar HP)       --}}
    {{-- ======================================================== --}}
    <div class="d-block d-md-none mb-4">
        <p class="text-muted small fw-bold mb-2 text-uppercase">Menu Kelola Toko</p>
        <div class="row g-2">
            {{-- Tombol Produk Saya --}}
            <div class="col-4">
                <a href="{{ route('seller.products.index') }}" class="btn btn-white border shadow-sm w-100 py-3 rounded-4 text-dark text-decoration-none h-100">
                    <i class="bi bi-box-seam fs-3 d-block mb-1" style="color: var(--primary-maroon);"></i>
                    <span style="font-size: 0.7rem;" class="fw-bold d-block">Produk</span>
                </a>
            </div>
            {{-- Tombol Pesanan Masuk --}}
            <div class="col-4">
                <a href="{{ route('seller.orders.index') }}" class="btn btn-white border shadow-sm w-100 py-3 rounded-4 text-dark text-decoration-none position-relative h-100">
                    <i class="bi bi-cart-check fs-3 d-block mb-1" style="color: var(--primary-maroon);"></i>
                    <span style="font-size: 0.7rem;" class="fw-bold d-block">Pesanan</span>
                    @if(isset($pending_action) && $pending_action > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="margin-left: -10px; margin-top: 5px;">
                        {{ $pending_action }}
                    </span>
                    @endif
                </a>
            </div>
            {{-- TOMBOL BARU: Lapor/Bantuan --}}
            <div class="col-4">
                <a href="{{ route('reports.index') }}" class="btn btn-white border shadow-sm w-100 py-3 rounded-4 text-dark text-decoration-none h-100">
                    <i class="bi bi-headset fs-3 d-block mb-1 text-primary"></i>
                    <span style="font-size: 0.7rem;" class="fw-bold d-block">Bantuan</span>
                </a>
            </div>
        </div>
    </div>
    {{-- ======================================================== --}}

    {{-- 3. Bagian Statistik Toko --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="p-4 rounded-4 text-white shadow-sm"
                style="background: linear-gradient(135deg, var(--primary-maroon), #b30000);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-1">Total Pendapatan (Selesai)</h6>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($total_revenue ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-wallet2 fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="p-3 bg-white border rounded-4 shadow-sm h-100">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10 me-3">
                        <i class="bi bi-clock-history text-warning fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Perlu Diproses</small>
                        <h4 class="fw-bold mb-0">{{ $pending_action ?? 0 }} <span class="fs-6 fw-normal text-muted">Pesanan</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="p-3 bg-white border rounded-4 shadow-sm h-100">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 bg-dark bg-opacity-10 me-3">
                        <i class="bi bi-box-seam text-dark fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Koleksi Produk</small>
                        <h4 class="fw-bold mb-0">{{ $product_count ?? 0 }} <span class="fs-6 fw-normal text-muted">Item</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Notifikasi Info & Bantuan --}}
    <div class="row mt-4 g-3">
        @if(isset($pending_action) && $pending_action > 0)
        <div class="col-12">
            <div class="p-3 rounded-4 border-0 d-flex align-items-center shadow-sm" style="background-color: #fff4f4; color: #a94442;">
                <i class="bi bi-info-circle-fill me-3 fs-5"></i>
                <div class="small">
                    <strong>Info Pesanan:</strong> Ada {{ $pending_action }} pesanan menanti untuk diproses di menu
                    <a href="{{ route('seller.orders.index') }}" class="text-decoration-none fw-bold text-danger">Pesanan Masuk</a>.
                </div>
            </div>
        </div>
        @endif

        {{-- CARD BARU: Pusat Bantuan untuk Desktop --}}
        <div class="col-12">
            <div class="p-3 rounded-4 border-0 d-flex align-items-center bg-light shadow-sm">
                <i class="bi bi-headset me-3 fs-5 text-primary"></i>
                <div class="small flex-grow-1">
                    <strong>Butuh Bantuan?</strong> Alami kendala dalam transaksi atau penggunaan sistem?
                </div>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                    Lapor Admin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
