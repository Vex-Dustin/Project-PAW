@extends('app.master')

@section('title', $product->name)

@section('content')
    <div class="container mt-4 mb-5 pb-5">
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary mb-4 border-0 shadow-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
        </a>

        <div class="row g-4 bg-white p-4 rounded-4 shadow-sm">
            {{-- Gambar Produk --}}
            <div class="col-md-5">
                <div class="card border-0 overflow-hidden rounded-4">
                    @if ($product->image && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid w-100"
                            style="object-fit: cover; max-height: 500px;" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light d-flex flex-column align-items-center justify-content-center rounded-4"
                            style="height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted">Gambar Produk Belum Tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="col-md-7">
                <div class="ps-md-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="#"
                                    class="text-decoration-none text-muted">{{ $product->category->name ?? 'Uncategorized' }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $product->name }}</li>
                        </ol>
                    </nav>

                    <h2 class="fw-bold mb-2">{{ $product->name }}</h2>

                    {{-- Info Rating Bintang Toko di bawah Nama --}}
                    @php
                        $seller = $product->user;
                        $avgRating = $seller ? $seller->averageSellerRating() : 0;
                        $reviewsCount = $seller ? $seller->sellerReviews()->count() : 0;
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2 small">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $avgRating ? 'bi-star-fill' : ($i - 0.5 <= $avgRating ? 'bi-star-half' : 'bi-star') }}"></i>
                            @endfor
                        </div>
                        <span class="fw-bold text-dark small me-1">{{ $avgRating }}</span>
                        <span class="text-muted small">({{ $reviewsCount }} Ulasan Toko)</span>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <span
                            class="badge bg-success-subtle text-success border border-success px-2 py-1 me-3 rounded-pill">
                            <i class="bi bi-patch-check-fill me-1"></i> {{ $product->status ?? 'Tersedia' }}
                        </span>
                        @if($seller)
                            <a href="{{ route('store.profile', $seller->id) }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 small fw-bold hover-shop-badge transition-all" style="color: var(--primary-maroon); border-color: rgba(144, 26, 30, 0.2) !important;">
                                <i class="bi bi-shop"></i>
                                <span>Kunjungi Toko: <strong>{{ $seller->shop_name ?: $seller->name }}</strong></span>
                                <i class="bi bi-arrow-right-short ms-1 text-muted"></i>
                            </a>
                        @endif
                    </div>

                    <h3 class="text-danger fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>

                    <h6 class="fw-bold">Deskripsi Produk</h6>
                    <p class="text-muted lh-lg" style="white-space: pre-line;">
                        {{ $product->description ?? 'Tidak ada deskripsi tambahan untuk produk ini.' }}
                    </p>

                    <div class="bg-light p-3 rounded-4 mb-4 border">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Stok Tersedia:</span>
                            <span class="fw-bold">{{ $product->stock }} pcs</span>
                        </div>
                    </div>

                    {{-- LOGIKA PROTEKSI ROLE (Poin 1 & 12) --}}
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <div class="alert alert-info rounded-3 border-0 shadow-sm">
                                <i class="bi bi-info-circle-fill me-2"></i> Sebagai <strong>Admin</strong>, Anda hanya dapat
                                memantau produk ini dan tidak dapat melakukan pembelian.
                            </div>
                        @elseif(Auth::user()->id === $product->user_id)
                            <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Ini adalah produk toko Anda sendiri. Anda
                                tidak dapat membelinya.
                            </div>
                        @else
                            {{-- Form Keranjang untuk Pembeli Biasa / Penjual Lain --}}
                            <form action="{{ route('cart.store', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="row g-2">
                                    <div class="col-4 col-md-3">
                                        <input type="number" name="quantity"
                                            class="form-control form-control-lg text-center rounded-3" value="1"
                                            min="1" max="{{ $product->stock }}">
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <button type="submit" class="btn btn-lg w-100 shadow-sm rounded-3 fw-bold text-white"
                                            style="background-color: var(--primary-maroon);">
                                            <i class="bi bi-cart-plus me-2"></i> Masukkan Keranjang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    @else
                        {{-- Tombol Login untuk Guest --}}
                        <a href="{{ route('login') }}" class="btn btn-lg w-100 shadow-sm rounded-3 fw-bold text-white"
                            style="background-color: var(--primary-maroon);">
                            Login untuk Membeli
                        </a>
                    @endauth

                </div>
            </div>
        </div>

        {{-- BAGIAN BARU: ULASAN DAN RATING TOKO --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">Ulasan & Reputasi Toko ({{ $seller->shop_name ?: $seller->name }})</h4>
                    
                    @php
                        $reviews = $seller ? $seller->sellerReviews()->with('user')->latest()->get() : collect();
                    @endphp

                    @if($reviews->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-left-text fs-1 mb-3 d-block opacity-50"></i>
                            Belum ada ulasan untuk toko ini.
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($reviews as $rev)
                                <div class="col-12 border-bottom pb-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 40px; height: 40px; color: var(--primary-maroon);">
                                                {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $rev->user->name }}</h6>
                                                <small class="text-muted">{{ $rev->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                        
                                        {{-- Rating Bintang --}}
                                        <div class="text-warning small">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-dark mb-0 mt-2 bg-light p-3 rounded-3" style="font-size: 0.95rem;">
                                        {{ $rev->comment }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shop-badge:hover {
            background-color: rgba(144, 26, 30, 0.05) !important;
            border-color: var(--primary-maroon) !important;
            transform: translateY(-1px);
        }
    </style>
@endsection
