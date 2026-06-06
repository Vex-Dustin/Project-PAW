@extends('app.seller_layout')

@section('title', 'Rincian Pesanan #ORD-' . $order->id)

@section('seller_content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--primary-maroon);">
                    <i class="bi bi-receipt-cutoff me-2"></i>Rincian Pesanan
                </h3>
                <p class="text-muted small mb-0">ID Pesanan: <span class="fw-bold">#ORD-{{ $order->id }}</span> | Tanggal:
                    {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            {{-- Sisi Kiri: Daftar Produk & Ringkasan Pembayaran + BUKTI PEMBAYARAN --}}
            <div class="col-lg-8">
                {{-- Card Daftar Produk --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Daftar Produk</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 250px;">Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end" style="min-width: 120px;">Harga Satuan</th>
                                        <th class="text-end" style="min-width: 140px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sellerTotal = 0; @endphp
                                    @foreach ($order->items as $item)
                                        @if ($item->product->user_id == auth()->id())
                                            @php $sellerTotal += ($item->price * $item->quantity); @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                            class="rounded-3 me-3 border"
                                                            style="width: 60px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <span class="fw-bold d-block text-truncate"
                                                                style="max-width: 200px;">{{ $item->product->name }}</span>
                                                            <small class="text-muted">Kategori:
                                                                {{ $item->product->category->name ?? 'Songket' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end text-nowrap">Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold text-maroon text-nowrap">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Harga Produk Anda:</span>
                                <span class="fw-bold">Rp {{ number_format($sellerTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span class="fw-bold">Total Pendapatan Anda:</span>
                                <h4 class="fw-bold mb-0 text-maroon text-nowrap">Rp
                                    {{ number_format($sellerTotal, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAMBAHAN BARU: Card Bukti Pembayaran dari Pembeli --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-image me-2 text-secondary"></i>Bukti Pembayaran</h5>

                        {{-- Catatan: Sesuaikan '$order->payment_proof' dengan nama kolom asli di database Anda --}}
                        @if (!empty($order->payment_proof))
                            <div class="p-3 bg-light rounded-4 border text-center">
                                <div
                                    class="position-relative d-inline-block shadow-sm rounded-3 overflow-hidden bg-white p-2 border">
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                                        class="img-fluid rounded"
                                        style="max-height: 300px; object-fit: contain; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#paymentProofModal">
                                </div>
                                <p class="text-muted small mb-0 mt-3">
                                    <i class="bi bi-zoom-in me-1"></i> Klik gambar di atas untuk memperbesar rincian
                                    transfer.
                                </p>
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded-4 border border-dashed">
                                <i class="bi bi-file-earmark-x text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted small mb-0">Belum ada bukti pembayaran yang diunggah oleh pembeli atau
                                    menggunakan metode COD.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Info Pembeli & Form Status --}}
            <div class="col-lg-4">
                {{-- Status & Update Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div
                        class="p-3 text-center @if ($order->status == 'Sudah Dibayar') bg-warning @elseif($order->status == 'Diproses') bg-primary @elseif($order->status == 'Dikirim') bg-info @elseif($order->status == 'Selesai') bg-success @else bg-secondary @endif">
                        <span class="fw-bold text-white text-uppercase">Status Saat Ini: {{ $order->status }}</span>
                    </div>

                    <div class="card-body p-4">
                        {{-- LOGIKA BARU: Jika Selesai, form dihilangkan --}}
                        @if ($order->status == 'Selesai')
                            <div class="alert alert-success border-0 shadow-sm rounded-3 text-center mb-0">
                                <i class="bi bi-check-circle-fill d-block fs-3 mb-2"></i>
                                <span class="fw-bold d-block">Pesanan Selesai</span>
                                <small>Status pesanan tidak dapat diubah lagi.</small>
                            </div>
                        @else
                            <form action="{{ route('seller.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Ubah Status Menjadi:</label>
                                    <select name="status" id="statusSelect" class="form-select rounded-3 shadow-sm"
                                        required>
                                        <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>
                                            Diproses
                                            (Sedang disiapkan)</option>
                                        <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim
                                            (Diserahkan ke kurir)</option>
                                    </select>
                                </div>

                                {{-- Input Resi yang muncul dinamis jika status 'Dikirim' --}}
                                <div id="resiInputGroup" class="{{ $order->status == 'Dikirim' ? '' : 'd-none' }} mb-3">
                                    <label class="form-label small fw-bold">Nomor Resi Kurir:</label>
                                    <input type="text" name="resi_number" id="resi_input"
                                        class="form-control rounded-3 shadow-sm" placeholder="Masukkan nomor resi..."
                                        value="{{ $order->resi_number }}">
                                    <small class="text-muted d-block mt-1">Gunakan nomor resi pengiriman yang valid agar
                                        pembeli
                                        dapat melacak paket.</small>
                                </div>

                                <button type="submit" class="btn w-100 rounded-pill py-2 fw-bold text-white shadow-sm mt-2"
                                    style="background-color: var(--primary-maroon);">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Customer Card --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Pelanggan</h6>
                        <div class="mb-3">
                            <label class="small text-muted d-block">Nama Lengkap:</label>
                            <span class="fw-bold">{{ $order->user->name }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted d-block">Kontak Email:</label>
                            <span class="text-dark">{{ $order->user->email }}</span>
                        </div>
                        <hr>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Alamat Pengiriman</h6>
                        <p class="text-muted text-dark fw-semibold small mb-0">
                            {{ $order->shipping_address ?? 'Alamat tidak tercatat' }}
                        </p>
                        <hr>
                        <div class="bg-light p-3 rounded-3">
                            <label class="small text-muted d-block mb-1 italic"><i class="bi bi-chat-left-text me-1"></i>
                                Catatan Pembeli:</label>
                            <span class="small">{{ $order->notes ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAMBAHAN BARU: Bootstrap Modal untuk Zoom Gambar Bukti Pembayaran --}}
    @if (!empty($order->payment_proof))
        <div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 bg-light rounded-top-4 py-3">
                        <h5 class="modal-title fw-bold text-dark" id="paymentProofModalLabel">Detail Bukti Pembayaran</h5>
                    </div>
                    <div class="modal-body p-3 text-center bg-dark rounded-bottom-4">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded-2"
                            style="max-height: 80vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- JavaScript Dinamis untuk Resi Pengiriman --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');
            const resiGroup = document.getElementById('resiInputGroup');
            const resiInput = document.getElementById('resi_input');

            // Tambahkan pengecekan if (statusSelect) agar tidak error saat form disembunyikan
            if (statusSelect) {
                function toggleResiInput() {
                    if (statusSelect.value === 'Dikirim') {
                        resiGroup.classList.remove('d-none');
                        resiInput.setAttribute('required', 'required');
                    } else {
                        resiGroup.classList.add('d-none');
                        resiInput.removeAttribute('required');
                    }
                }

                toggleResiInput();
                statusSelect.addEventListener('change', toggleResiInput);
            }
        });
    </script>
@endsection
