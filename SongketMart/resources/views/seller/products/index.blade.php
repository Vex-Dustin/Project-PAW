@extends('app.seller_layout')

@section('title', 'Daftar Produk Saya')

@section('seller_content')
    <div class="card border-0 shadow-sm rounded-4 p-4">
        {{-- Header Halaman --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-0" style="color: var(--primary-maroon);">
                    <i class="bi bi-box-seam me-2"></i>Produk Saya
                </h2>
                <p class="text-muted small mb-0">Kelola koleksi kain songket yang Anda jual di platform ini.</p>
            </div>
            <a href="{{ route('seller.products.create') }}" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Produk
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Tabel Produk --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">Foto</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3">Stok</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="65" height="65"
                                        class="rounded shadow-sm border" style="object-fit: cover;">
                                @else
                                    <div class="bg-light text-center rounded border d-flex align-items-center justify-content-center"
                                        style="width: 65px; height: 65px;">
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <span class="badge bg-secondary-subtle text-secondary small" style="font-weight: 500;">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $product->stock }} Pcs</span>
                            </td>
                            <td>
                                @if ($product->status == 'Certified Authentic')
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-patch-check-fill me-1"></i> Asli
                                    </span>
                                @elseif($product->status == 'Pending')
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                        {{ $product->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Cek izin update sebelum menampilkan tombol Edit --}}
                                    @can('update', $product)
                                        <a href="{{ route('seller.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </a>
                                    @endcan

                                    {{-- Cek izin delete sebelum menampilkan form Hapus --}}
                                    @can('delete', $product)
                                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-box2 fs-1 opacity-25"></i>
                                </div>
                                <p class="mb-0">Belum ada produk yang Anda unggah.</p>
                                <small>Klik tombol "Tambah Produk" untuk mulai berjualan.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small d-none d-md-block">
                    Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari
                    {{ $products->total() }} produk
                </div>
                <div class="pagination-sm">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <div class="pb-5 mb-5 d-block d-md-none"></div> {{-- Spacer untuk Mobile agar tidak tertutup Footer --}}
@endsection
