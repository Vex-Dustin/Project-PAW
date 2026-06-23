@extends('app.master')

@section('title', 'Katalog Songket & Batik')

{{-- 1. BAGIAN STYLE (Diletakkan di dalam section agar terbaca) --}}
@section('styles')
    <style>
        /* Mengatur scroll kategori agar rapi di mobile */
        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
            display: flex;
            gap: 8px;
        }

        /* Tombol kategori yang langsing dan cantik */
        .btn-category {
            white-space: nowrap;
            flex-shrink: 0;
            padding: 6px 20px;
            /* Membuat tombol langsing (tidak gemuk) */
            font-size: 14px;
            border-radius: 50px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-maroon {
            background-color: var(--primary-maroon);
            color: white;
        }

        .btn-white {
            background-color: white;
        }
    </style>
@endsection

@section('content')
    {{-- 2. BAGIAN HEADER & SEARCH --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: var(--primary-maroon);">Eksplorasi Warisan Budaya</h4>
        <p class="text-muted small">Temukan kain pilihan terbaik dari UMKM Dekranasda</p>

        <form action="{{ route('home') }}" method="GET" class="mt-3">
            <div class="input-group shadow-sm border rounded-pill overflow-hidden bg-white" style="max-width: 600px;">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 py-2 ps-1"
                    placeholder="Cari kain songket atau batik..." value="{{ request('search') }}" style="box-shadow: none;">
                @if (request('search'))
                    <a href="{{ route('home') }}" class="input-group-text bg-white border-0 pe-3 text-decoration-none">
                        <i class="bi bi-x-circle-fill text-muted"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 3. FILTER KATEGORI HYBRID (Mobile: Scroll, Desktop: Wrap/Turun ke bawah) --}}
    <div class="category-scroll flex-nowrap flex-md-wrap overflow-auto pb-3 mb-4 mt-2">

        {{-- Tombol Semua --}}
        <a href="{{ route('home') }}"
            class="btn btn-category shadow-sm {{ !request('search') ? 'btn-maroon text-white' : 'btn-white border text-dark' }}">
            Semua
        </a>

        {{-- Daftar Kategori dari Database --}}
        @foreach ($categories as $cat)
            <a href="{{ route('home', ['search' => $cat->name]) }}"
                class="btn btn-category shadow-sm {{ request('search') == $cat->name ? 'btn-maroon text-white' : 'btn-white border text-dark' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    {{-- 4. LOGIKA TAMPILAN PENCARIAN --}}
    @if (request('search'))
        <div class="mb-4">
            <p class="text-muted">Menampilkan hasil pencarian untuk: <span
                    class="fw-bold text-dark">"{{ request('search') }}"</span></p>
            <a href="{{ route('home') }}" class="btn btn-sm btn-light border rounded-pill">
                <i class="bi bi-x-circle me-1"></i> Hapus Pencarian
            </a>
        </div>
    @endif

    {{-- 5. GRID PRODUK --}}
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="position-relative"
                        style="height: 180px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        @if ($product->image && file_exists(public_path('storage/' . $product->image)))
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                <p style="font-size: 0.6rem;" class="mb-0 px-2 text-uppercase fw-bold">Foto Belum Tersedia
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        <p class="text-muted small mb-1">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                        <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                        
                        {{-- Info Rating Toko Ringkas --}}
                        @php
                            $seller = $product->user;
                            $avgRating = $seller ? $seller->averageSellerRating() : 0;
                            $reviewsCount = $seller ? $seller->sellerReviews()->count() : 0;
                        @endphp
                        <div class="d-flex align-items-center justify-content-between mb-2" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">
                                    <i class="bi bi-star-fill"></i>
                                </span>
                                <span class="fw-bold text-dark me-1">{{ $avgRating }}</span>
                                <span class="text-muted">({{ $reviewsCount }})</span>
                            </div>
                             @if($seller)
                                 <a href="{{ route('store.profile', $seller->id) }}" class="text-decoration-none text-muted text-truncate fw-bold ms-2 hover-maroon" style="max-width: 120px;" title="Kunjungi Toko {{ $seller->shop_name ?: $seller->name }}">
                                     <i class="bi bi-shop me-1" style="color: var(--primary-maroon);"></i>{{ $seller->shop_name ?: $seller->name }}
                                 </a>
                             @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-danger">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3 pt-0">
                        <a href="/product/{{ $product->id }}" class="btn btn-outline-maroon w-100 btn-sm fw-bold"
                            style="border-radius: 8px; border-color: var(--primary-maroon); color: var(--primary-maroon);">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search text-muted opacity-25" style="font-size: 4rem;"></i>
                <h5 class="mt-3 fw-bold text-muted">Produk Tidak Ditemukan</h5>
                <p class="text-muted small">Maaf, kami tidak menemukan produk dengan kata kunci "{{ request('search') }}"
                </p>
                <a href="{{ route('home') }}" class="btn btn-maroon rounded-pill px-4 shadow-sm mt-2">Lihat Semua
                    Produk</a>
            </div>
        @endforelse
    </div>

    {{-- 6. PAGINATION --}}
    @if ($products->hasPages())
        <div class="d-flex justify-content-center mt-5 mb-5">
            {{ $products->links() }}
        </div>
    @endif

@endsection
