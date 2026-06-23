@extends('app.master')

@section('title', ($seller->shop_name ?: $seller->name) . ' - SongketMart')

@section('content')
    <div class="container my-5">
        {{-- Profile Header Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
            <div class="card-body p-4 p-md-5 text-white" style="background: linear-gradient(135deg, var(--primary-maroon), #901A1E);">
                <div class="row align-items-center g-4">
                    <div class="col-md-8 d-flex align-items-center">
                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-4 shadow" 
                             style="width: 90px; height: 90px; color: var(--primary-maroon); flex-shrink: 0;">
                            <i class="bi bi-shop fs-1"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill fw-bold text-uppercase">
                                <i class="bi bi-patch-check-fill me-1"></i> Penjual Terverifikasi
                            </span>
                            <h2 class="fw-bold mb-1">{{ $seller->shop_name ?: $seller->name }}</h2>
                            <p class="mb-0 text-white-50 small">
                                <i class="bi bi-person-fill me-1"></i> Pemilik: {{ $seller->name }} | 
                                <i class="bi bi-calendar-event me-1"></i> Gabung: {{ $seller->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Rating Stats Card --}}
                    @php
                        $avgRating = $seller->averageSellerRating();
                        $reviewsCount = $reviews->count();
                    @endphp
                    <div class="col-md-4 text-md-end text-start">
                        <div class="bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 d-inline-block text-start">
                            <span class="text-white-50 small d-block mb-1">Reputasi Toko</span>
                            <div class="d-flex align-items-center gap-2">
                                <h2 class="fw-bold text-warning mb-0">{{ $avgRating }}</h2>
                                <div>
                                    <div class="text-warning mb-0 small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $avgRating ? 'bi-star-fill' : ($i - 0.5 <= $avgRating ? 'bi-star-half' : 'bi-star') }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-white-50 small">Dari {{ $reviewsCount }} Ulasan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav Tabs --}}
        <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white rounded-3 shadow-sm" id="storeTabs" role="tablist" style="max-width: 600px; margin: 0 auto;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-3 text-uppercase rounded-3" id="products-tab" data-bs-toggle="pill" data-bs-target="#products-pane" type="button" role="tab" aria-controls="products-pane" aria-selected="true">
                    <i class="bi bi-grid me-2"></i>Produk Toko ({{ $products->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3 text-uppercase rounded-3" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews-pane" type="button" role="tab" aria-controls="reviews-pane" aria-selected="false">
                    <i class="bi bi-chat-left-heart me-2"></i>Ulasan & Umpan Balik ({{ $reviewsCount }})
                </button>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content" id="storeTabsContent">
            {{-- Tab 1: Products --}}
            <div class="tab-pane fade show active" id="products-pane" role="tabpanel" aria-labelledby="products-tab" tabindex="0">
                @if($products->isEmpty())
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm my-4 text-muted">
                        <i class="bi bi-box-seam fs-1 mb-3 d-block opacity-50" style="color: var(--primary-maroon);"></i>
                        Toko ini belum memiliki produk aktif untuk dijual.
                    </div>
                @else
                    <div class="row g-4 mt-1">
                        @foreach($products as $product)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                    <div class="position-relative" style="height: 180px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        @if ($product->image && file_exists(public_path('storage/' . $product->image)))
                                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                                <p style="font-size: 0.6rem;" class="mb-0 px-2 text-uppercase fw-bold">Foto Belum Tersedia</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-muted small mb-1">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                                        <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                                        <span class="fw-bold text-danger d-block mb-3">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="card-footer bg-white border-0 p-3 pt-0">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-maroon w-100 btn-sm fw-bold shadow-sm"
                                            style="border-radius: 8px; border-color: var(--primary-maroon); color: var(--primary-maroon);">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tab 2: Reviews --}}
            <div class="tab-pane fade" id="reviews-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">Semua Ulasan & Penilaian Toko</h4>
                    
                    @if($reviews->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-left-text fs-1 mb-3 d-block opacity-50"></i>
                            Belum ada pembeli yang memberikan ulasan untuk toko ini.
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($reviews as $rev)
                                <div class="col-12 border-bottom pb-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" 
                                                 style="width: 45px; height: 45px; color: var(--primary-maroon); flex-shrink: 0;">
                                                {{ strtoupper(substr($rev->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $rev->user->name }}</h6>
                                                <small class="text-muted">{{ $rev->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                        
                                        {{-- Rating Bintang --}}
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }} fs-6"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-dark mb-0 mt-3 p-3 bg-light rounded-3 shadow-sm small border-start border-4 border-maroon" style="border-left-color: var(--primary-maroon) !important;">
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

    {{-- Styling custom tambahan --}}
    <style>
        .nav-pills .nav-link {
            color: #6c757d;
            background-color: transparent;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link.active {
            color: #fff !important;
            background-color: var(--primary-maroon) !important;
        }
        .nav-pills .nav-link:hover:not(.active) {
            color: var(--primary-maroon);
            background-color: rgba(144, 26, 30, 0.05);
        }
        .btn-outline-maroon:hover {
            background-color: var(--primary-maroon) !important;
            color: #fff !important;
        }
        .hover-maroon:hover {
            color: var(--primary-maroon) !important;
        }
    </style>
@endsection
