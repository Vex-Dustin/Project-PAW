@extends('app.master')

@section('title', 'Pesanan Masuk - Seller')

@section('content')
    <div class="container-fluid my-4 pb-5">
        <div class="row">
            <div class="col-md-3">
                @include('app.sidebar')
            </div>

            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">
                        <i class="bi bi-cart-fill me-2"></i>Pesanan Masuk
                    </h4>

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pembeli</th>
                                    <th>Total Belanja Anda</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>No. Resi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $sellerTotal = $order->items
                                            ->where('product.user_id', auth()->id())
                                            ->sum(function ($item) {
                                                return $item->price * $item->quantity;
                                            });

                                        $isLocked =
                                            $order->payment_method === 'transfer' && $order->status === 'Belum Dibayar';
                                    @endphp

                                    @if ($isLocked)
                                        <tr class="table-light text-muted" style="cursor: not-allowed;"
                                            title="Detail dikunci sebelum pembeli membayar">
                                        @else
                                        <tr onclick="window.location='{{ route('seller.orders.show', $order->id) }}'"
                                            style="cursor: pointer;" title="Klik untuk melihat detail">
                                    @endif
                                    <td>
                                        <div class="fw-bold">{{ $order->user->name }}</div>
                                        <div class="small text-muted">{{ $order->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($sellerTotal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($order->payment_method === 'transfer')
                                            <span class="badge bg-info text-dark px-2 rounded-pill"><i
                                                    class="bi bi-bank"></i> Transfer</span>
                                        @elseif($order->payment_method === 'cod')
                                            <span class="badge bg-warning text-dark px-2 rounded-pill"><i
                                                    class="bi bi-cash"></i> COD</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $order->status == 'Dibatalkan' ? 'bg-danger' : 'bg-primary' }} px-3 py-2 rounded-pill">{{ $order->status }}</span>
                                    </td>
                                    <td>
                                        @if ($order->resi_number)
                                            <span class="badge bg-light text-dark border">{{ $order->resi_number }}</span>
                                        @else
                                            <small class="text-muted italic">Belum ada</small>
                                        @endif
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation();">
                                        @if ($isLocked)
                                            <button class="btn btn-sm btn-light text-muted rounded-3 px-3 shadow-sm"
                                                disabled data-bs-toggle="tooltip" title="Terkunci (Belum Dibayar)">
                                                <i class="bi bi-lock-fill me-1"></i> Terkunci
                                            </button>
                                        @else
                                            <a href="{{ route('seller.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">Belum ada pesanan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
