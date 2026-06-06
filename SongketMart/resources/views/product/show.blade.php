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

                    <div class="d-flex align-items-center mb-3">
                        <span
                            class="badge bg-success-subtle text-success border border-success px-2 py-1 me-2 rounded-pill">
                            <i class="bi bi-patch-check-fill me-1"></i> {{ $product->status ?? 'Tersedia' }}
                        </span>
                        <small class="text-muted">
                            <i class="bi bi-shop me-1"></i>
                            {{ $product->user->shop_name ?? $product->user->name }}
                        </small>
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
    </div>
@endsection
