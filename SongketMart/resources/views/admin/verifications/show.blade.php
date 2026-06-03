@extends('app.master')

@section('title', 'Detail Verifikasi Produk')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row g-4">
            {{-- 1. Sidebar --}}
            <div class="col-md-3">
                @include('app.sidebar')
            </div>

            {{-- 2. Konten Utama --}}
            <div class="col-md-9">
                <div class="mb-4">
                    <a href="{{ route('admin.verifications.index') }}"
                        class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                    <h2 class="fw-bold">Detail Pengajuan Produk</h2>
                    <p class="text-muted">Periksa informasi kain songket dengan teliti sebelum memberikan sertifikasi
                        keaslian.</p>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <div class="row g-4">
                        {{-- Sisi Kiri: Foto Produk --}}
                        <div class="col-md-5 text-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="img-fluid rounded-4 shadow-sm border"
                                    style="max-height: 400px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light rounded-4 d-flex flex-column align-items-center justify-content-center border"
                                    style="height: 300px;">
                                    <i class="bi bi-image text-muted fs-1 mb-2"></i>
                                    <span class="text-muted">Tidak Ada Foto Produk</span>
                                </div>
                            @endif
                        </div>

                        {{-- Sisi Kanan: Informasi Produk --}}
                        <div class="col-md-7">
                            <span
                                class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill fs-6">{{ $product->status }}</span>
                            <h3 class="fw-bold text-dark mb-1">{{ $product->name }}</h3>
                            <p class="text-muted mb-3">Kategori: <span
                                    class="badge bg-secondary">{{ $product->category->name }}</span></p>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Harga Jual</small>
                                    <strong class="fs-4 text-danger">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Stok Fisik</small>
                                    <strong class="fs-4 text-dark">{{ $product->stock }} Pcs</strong>
                                </div>
                            </div>

                            <div class="mb-4 bg-light p-3 rounded-3">
                                <h6 class="fw-bold text-dark"><i class="bi bi-person-badge me-2"></i>Informasi Pengrajin /
                                    Penjual</h6>
                                <p class="mb-1 alt-text"><strong>Nama Toko:</strong> {{ $product->user->name }}</p>
                                <p class="mb-0 text-muted small"><strong>Email:</strong> {{ $product->user->email }}</p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-dark">Deskripsi & Spesifikasi Produk:</h6>
                                <p class="text-secondary" style="text-align: justify; line-height: 1.6;">
                                    {{ $product->description ?? 'Penjual tidak menyertakan deskripsi untuk produk ini.' }}
                                </p>
                            </div>

                            <hr>

                            {{-- Tombol Aksi Persetujuan --}}
                            <div class="d-flex gap-3 mt-4">
                                <form action="{{ route('admin.verifications.verify', $product->id) }}" method="POST"
                                    class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Certified Authentic">
                                    <button type="submit" class="btn btn-success w-100 py-2 rounded-3"
                                        onclick="return confirm('Sertifikasi produk ini sebagai barang asli?')">
                                        <i class="bi bi-patch-check-fill me-2"></i> Setujui & Sertifikasi Resmi
                                    </button>
                                </form>

                                <form action="{{ route('admin.verifications.verify', $product->id) }}" method="POST"
                                    class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-3"
                                        onclick="return confirm('Tolak produk ini?')">
                                        <i class="bi bi-x-circle me-2"></i> Tolak Produk
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
