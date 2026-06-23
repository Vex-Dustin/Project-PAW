@extends('app.master')

@section('title', 'Detail Pesanan - SongketMart')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: var(--primary-maroon);">
                <i class="bi bi-receipt me-2"></i>Detail Pesanan #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('order.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold py-3 border-bottom-0">
                        <i class="bi bi-box-seam me-2 text-muted"></i>Item yang Dibeli
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach ($order->items as $item)
                                <li
                                    class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center border-start-0 border-end-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded me-3 overflow-hidden bg-light d-flex align-items-center justify-content-center"
                                            style="width: 70px; height: 70px; flex-shrink: 0;">
                                            @if ($item->product->image && file_exists(public_path('storage/' . $item->product->image)))
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="bi bi-image text-muted fs-4"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                            <p class="text-muted small mb-1">{{ $item->quantity }} x Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</p>
                                            
                                            {{-- FITUR ULASAN: Muncul jika pesanan berstatus Selesai --}}
                                            <div class="mt-2 mb-1">
                                                @if($item->product->user)
                                                    <a href="{{ route('store.profile', $item->product->user_id) }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-2.5 py-0.5 d-inline-flex align-items-center gap-1 small fw-bold hover-shop-badge transition-all" style="color: var(--primary-maroon); font-size: 0.75rem; border-color: rgba(144, 26, 30, 0.15) !important;">
                                                        <i class="bi bi-shop text-muted"></i>
                                                        <span>{{ $item->product->user->shop_name ?: $item->product->user->name }}</span>
                                                        <i class="bi bi-chevron-right text-muted" style="font-size: 0.6rem;"></i>
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-muted border">Toko Tidak Diketahui</span>
                                                @endif
                                            </div>

                                            @if ($order->status == 'Selesai' && $item->product->user)
                                                @php
                                                    $reviewExists = \App\Models\Review::where('order_id', $order->id)
                                                        ->where('seller_id', $item->product->user_id)
                                                        ->first();
                                                @endphp

                                                @if ($reviewExists)
                                                    <div class="small mt-1 text-success">
                                                        <i class="bi bi-star-fill text-warning me-1"></i> Ulasan Toko: {{ $reviewExists->rating }}/5 Bintang
                                                    </div>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 mt-2 small"
                                                        data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $item->product->user_id }}">
                                                        <i class="bi bi-star-fill text-warning me-1"></i> Beri Ulasan Toko
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark">Rp
                                        {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-end">
                        <span class="text-muted me-2">Total Item:</span>
                        <span class="fw-bold text-dark">{{ $order->items->sum('quantity') }} Kain</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: var(--primary-maroon);">Ringkasan Pembayaran</h5>

                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <span class="text-muted small">Status Pesanan</span>
                            <span
                                class="badge {{ $order->status == 'Belum Dibayar' ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-2 rounded-pill">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <span class="text-muted small">Waktu Transaksi</span>
                            <span class="text-dark small fw-bold">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total Tagihan</span>
                            <span class="fw-bold text-danger fs-4">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>

                        @if ($order->status == 'Dibatalkan')
                            <div class="alert alert-danger text-center border-0 rounded-3 mb-0">
                                <i class="bi bi-x-circle-fill me-2"></i>Pesanan Dibatalkan
                            </div>
                        @elseif ($order->payment_method == 'cod')
                            @if ($order->status == 'Selesai')
                                <div class="alert alert-success text-center border-0 rounded-3 mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>Terima kasih! Pesanan Lunas (COD Selesai).
                                </div>
                            @else
                                <div class="alert alert-warning text-center border-0 rounded-3 mb-0">
                                    <i class="bi bi-cash-stack me-2"></i>Pembayaran COD (Belum Lunas).
                                    <p class="small mb-0 mt-1 text-muted">Silakan siapkan uang tunai saat barang diterima.</p>
                                </div>
                            @endif
                        @else {{-- transfer --}}
                            @if ($order->status == 'Belum Dibayar')
                                <div class="border p-3 rounded-3 bg-light">
                                    <p class="small text-muted mb-2 text-center">Silakan unggah bukti transfer pembayaran Anda:</p>
                                    <form action="{{ route('order.upload-proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="file" name="payment_proof" class="form-control form-control-sm" required>
                                            <div class="form-text small" style="font-size: 0.7rem;">Maksimal ukuran file 2 MB (JPG, PNG).</div>
                                        </div>
                                        <button type="submit" class="btn btn-sm w-100 text-white fw-bold shadow-sm" style="background-color: var(--primary-maroon); border-radius: 8px;">
                                            <i class="bi bi-upload me-2"></i>Unggah Bukti Transfer
                                        </button>
                                    </form>
                                </div>
                            @elseif ($order->status == 'Menunggu Verifikasi')
                                <div class="alert alert-warning text-center border-0 rounded-3 mb-0">
                                    <i class="bi bi-clock-history me-2"></i>Menunggu Verifikasi Bukti Transfer
                                </div>
                            @else
                                <div class="alert alert-success text-center border-0 rounded-3 mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>Terima kasih! Pembayaran Lunas.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RENDERING MODAL FORM ULASAN DI LUAR LOOP LIST-GROUP UNTUK MENCEGAH BUG FLICKER --}}
        @if ($order->status == 'Selesai')
            @php
                $sellers = $order->items->map(function($item) {
                    return $item->product->user;
                })->filter()->unique('id');
            @endphp
            @foreach ($sellers as $seller)
                @php
                    $reviewExists = \App\Models\Review::where('order_id', $order->id)
                        ->where('seller_id', $seller->id)
                        ->exists();
                @endphp
                @if (!$reviewExists)
                    <div class="modal fade" id="reviewModal-{{ $seller->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <form action="{{ route('reviews.store', [$order->id, $seller->id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="fw-bold" style="color: var(--primary-maroon);">Tulis Ulasan Toko</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="text-center mb-3">
                                            <h6 class="fw-bold mb-1">{{ $seller->shop_name ?: $seller->name }}</h6>
                                            <p class="text-muted small">Berikan ulasan jujur Anda untuk pelayanan toko ini</p>
                                        </div>
                                        
                                        {{-- Rating --}}
                                        <div class="mb-4 text-center">
                                            <label class="form-label d-block fw-bold small text-muted mb-2">Beri Bintang</label>
                                            <div class="rating-stars d-flex justify-content-center gap-2">
                                                <select name="rating" class="form-select rounded-3 text-center fw-bold" style="max-width: 200px;" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                                    <option value="3">⭐⭐⭐ (3)</option>
                                                    <option value="2">⭐⭐ (2)</option>
                                                    <option value="1">⭐ (1)</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Komentar --}}
                                        <div class="mb-0">
                                            <label class="form-label fw-bold small text-muted">Komentar / Ulasan Toko</label>
                                            <textarea name="comment" class="form-control rounded-3" rows="4" placeholder="Ceritakan pelayanan toko, respon chat, kecepatan kirim, dsb..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn text-white rounded-pill px-4" style="background-color: var(--primary-maroon);">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <style>
        .hover-shop-badge:hover {
            background-color: rgba(144, 26, 30, 0.05) !important;
            border-color: var(--primary-maroon) !important;
            transform: translateY(-1px);
        }
    </style>
@endsection
