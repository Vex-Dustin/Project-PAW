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
                        @foreach($order->items as $item)
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center border-start-0 border-end-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded me-3 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; flex-shrink: 0;">
                                    @if($item->product->image && file_exists(public_path('storage/' . $item->product->image)))
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                    <i class="bi bi-image text-muted fs-4"></i>
                                    @endif
                                </div>

                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
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
                        <span class="badge {{ $order->status == 'Belum Dibayar' ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-2 rounded-pill">
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
                        <span class="fw-bold text-danger fs-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($order->status == 'Belum Dibayar')
                    <form action="{{ route('order.pay', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm"
                            style="background-color: var(--primary-maroon); border-radius: 10px;"
                            onclick="return confirm('Konfirmasi simulasi pembayaran?')">
                            <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                        </button>
                    </form>
                    @else
                    <div class="alert alert-success text-center border-0 rounded-3 mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>Terima kasih! Pesanan Lunas.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection