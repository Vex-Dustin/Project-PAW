@extends('app.master')

@section('title', 'Keranjang Belanja - SongketMart')

@section('content')
    {{-- Tambahan pb-5 mb-5 agar bagian bawah tidak tertutup oleh Bottom Nav di HP --}}
    <div class="container my-4 my-md-5 pb-5 mb-5">
        <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">
            <i class="bi bi-cart3 me-2"></i>Keranjang Anda
        </h4>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Pesan Error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                <strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Bagian Kiri: Daftar Produk --}}
            <div class="col-12 col-lg-8">
                @forelse($carts as $item)
                    <div class="card shadow-sm border-0 mb-3 rounded-4 overflow-hidden">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-start">

                                {{-- 1. Gambar di Kiri --}}
                                <div class="flex-shrink-0 me-3">
                                    @if ($item->product->image && file_exists(public_path('storage/' . $item->product->image)))
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            class="rounded-3 shadow-sm border" alt="{{ $item->product->name }}"
                                            style="width: 85px; height: 85px; object-fit: cover;">
                                    @else
                                        <div class="bg-light border rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                                            style="width: 85px; height: 85px;">
                                            <i class="bi bi-image text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- 2. Detail Produk di Kanan --}}
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-truncate">{{ $item->product->name }}</h6>
                                            <div class="text-danger fw-bold small">Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>

                                    {{-- 3. Aksi (Hapus & Kuantitas) di Bawah Teks --}}
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm p-1 px-2 rounded-3 shadow-sm"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                        {{-- Input Qty & Update --}}
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden"
                                                style="width: 80px;">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                    min="1" max="{{ $item->product->stock }}"
                                                    class="form-control text-center border-secondary-subtle px-1"
                                                    onchange="this.form.submit()">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                        <i class="bi bi-cart-x text-muted mb-3 d-block" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold text-muted">Keranjang masih kosong</h5>
                        <p class="text-muted small mb-4">Yuk, cari songket favoritmu sekarang!</p>
                        <a href="{{ route('home') }}" class="btn rounded-pill px-4 shadow-sm text-white fw-bold"
                            style="background-color: var(--primary-maroon);">Belanja Sekarang</a>
                    </div>
                @endforelse
            </div>

            {{-- Bagian Kanan: Ringkasan Belanja & Checkout Form --}}
            @if ($carts->count() > 0)
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 sticky-md-top" style="top: 90px; z-index: 10;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>

                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span class="small">Total Barang</span>
                                <span class="fw-bold text-dark">{{ $carts->sum('quantity') }} pcs</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">Total Harga</span>
                                <span class="fw-bold text-danger fs-5">
                                    @php
                                        $total = $carts->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        });
                                    @endphp
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                            <hr class="text-muted opacity-25 mb-4">

                            {{-- FORM INTEGRASI: Alamat & Pembayaran --}}
                            <form action="{{ route('order.checkout') }}" method="POST">
                                @csrf

                                {{-- Input Alamat Pengiriman --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Alamat Pengiriman</label>
                                    <textarea name="shipping_address"
                                        class="form-control rounded-3 shadow-sm @error('shipping_address') is-invalid @enderror" rows="3"
                                        placeholder="Masukkan alamat lengkap pengiriman..." required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- BARU: Input Catatan Pembeli (Opsional) --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Catatan Pembeli (Opsional)</label>
                                    <textarea name="notes" class="form-control rounded-3 shadow-sm @error('notes') is-invalid @enderror" rows="2"
                                        placeholder="Contoh: Warna cadangan maroon, packing kardus, dll...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Pilihan Metode Pembayaran --}}
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Metode Pembayaran</label>
                                    <select name="payment_method"
                                        class="form-select rounded-3 shadow-sm @error('payment_method') is-invalid @enderror"
                                        required>
                                        <option value="" disabled selected>Pilih Pembayaran...</option>
                                        <option value="transfer"
                                            {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank
                                            (Verifikasi Manual)</option>
                                        <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Bayar
                                            di Tempat (COD)</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn w-100 text-white shadow-sm py-3 rounded-pill fw-bold"
                                    style="background-color: var(--primary-maroon);">
                                    <i class="bi bi-bag-check me-2"></i> Lanjutkan ke Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
