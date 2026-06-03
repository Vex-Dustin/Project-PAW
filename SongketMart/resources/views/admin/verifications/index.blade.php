@extends('app.master')

@section('title', 'Verifikasi Produk')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row g-4">
            {{-- 1. Sidebar di sisi kiri (3 Kolom) --}}
            <div class="col-md-3">
                @include('app.sidebar')
            </div>

            {{-- 2. Konten Utama di sisi kanan (9 Kolom) --}}
            <div class="col-md-9">
                <div class="mb-4">
                    <h2 class="fw-bold">Verifikasi Produk Songket</h2>
                    <p class="text-muted">Setujui atau tolak produk yang diunggah oleh penjual untuk menjamin keaslian.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive bg-white p-3 rounded-4 shadow-sm">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Penjual</th>
                                <th>Foto</th>
                                <th>Detail Produk</th>
                                <th>Harga & Stok</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingProducts as $product)
                                <tr>
                                    <td><strong>{{ $product->user->name }}</strong></td>
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" width="60"
                                                height="60" class="rounded border shadow-sm" style="object-fit: cover;">
                                        @else
                                            <span class="text-muted small">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $product->name }}</strong><br>
                                        <span class="badge bg-secondary opacity-75">{{ $product->category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="d-block fw-bold text-dark">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <small class="text-muted">Stok: {{ $product->stock }}</small>
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ $product->status }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.verifications.verify', $product->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Certified Authentic">
                                                <button type="submit" class="btn btn-sm btn-success px-3 rounded-pill"
                                                    onclick="return confirm('Sertifikasi produk ini sebagai barang asli?')">
                                                    <i class="bi bi-check-lg me-1"></i> Setujui
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.verifications.verify', $product->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger px-3 rounded-pill"
                                                    onclick="return confirm('Tolak produk ini?')">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-patch-check fs-1 d-block mb-2 opacity-25"></i>
                                        Hore! Tidak ada produk yang menunggu verifikasi saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
