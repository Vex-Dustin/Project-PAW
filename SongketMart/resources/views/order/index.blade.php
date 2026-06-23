@extends('app.master')

@section('title', 'Riwayat Pesanan Saya - SongketMart')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3">
                @include('app.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h3 class="fw-bold mb-4" style="color: var(--primary-maroon);">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan Saya
                    </h3>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm"
                            role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Metode</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="fw-bold">#ORD-{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border shadow-sm">
                                                {{ strtoupper($order->payment_method) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $badgeColor = match ($order->status) {
                                                    'Menunggu Verifikasi' => 'bg-info text-dark',
                                                    'Sudah Dibayar', 'Diproses' => 'bg-primary',
                                                    'Dikirim' => 'bg-warning text-dark',
                                                    'Selesai' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeColor }} px-3 py-2 rounded-pill">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                @if ($order->status == 'Belum Dibayar' && $order->payment_method == 'transfer')
                                                    <button type="button" class="btn btn-maroon btn-sm shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadModal{{ $order->id }}">
                                                        <i class="bi bi-upload"></i> Bayar
                                                    </button>
                                                @elseif($order->status == 'Dikirim')
                                                    <form action="{{ route('order.complete', $order->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm shadow-sm"
                                                            onclick="return confirm('Yakin pesanan sudah diterima?')">
                                                            <i class="bi bi-box-seam"></i> Selesai
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($order->status != 'Belum Dibayar')
                                                    <a href="{{ route('order.invoice', $order->id) }}"
                                                        class="btn btn-sm btn-outline-dark shadow-sm" title="Cetak Invoice">
                                                        <i class="bi bi-printer"></i>
                                                     </a>
                                                @endif

                                                <a href="{{ route('order.show', $order->id) }}"
                                                    class="btn btn-sm btn-outline-primary shadow-sm" title="Lihat Detail Pesanan">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pesanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD BUKTI TRANSFER --}}
    @foreach ($orders as $order)
        @if ($order->payment_method == 'transfer')
            <div class="modal fade" id="uploadModal{{ $order->id }}" tabindex="-1"
                aria-labelledby="modalLabel{{ $order->id }}" aria-hidden="true" data-bs-backdrop="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form action="{{ route('order.upload-proof', $order->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold" id="modalLabel{{ $order->id }}">Upload Bukti Transfer
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-center">
                                <p class="small text-muted mb-3">Upload struk untuk
                                    <strong>#ORD-{{ $order->id }}</strong>
                                </p>

                                {{-- Info Batasan Ukuran --}}
                                <div class="alert alert-warning py-2 rounded-3 small border-0 mb-3 text-start">
                                    <i class="bi bi-info-circle me-1"></i> Maksimal ukuran file <strong>2 MB</strong> (JPG,
                                    PNG).
                                </div>

                                {{-- Input File dengan fungsi onchange --}}
                                <input type="file" name="payment_proof" id="payment_proof_{{ $order->id }}"
                                    class="form-control rounded-3" accept="image/*" required
                                    onchange="validateFileSize(this)">

                                {{-- Pesan Error Tersembunyi --}}
                                <div id="error_msg_{{ $order->id }}"
                                    class="text-danger small mt-2 d-none fw-bold text-start">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Ukuran file lebih dari 2 MB!
                                    Silakan kompres atau pilih file lain.
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light rounded-pill px-4"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-maroon rounded-pill px-4">Kirim Bukti</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- SCRIPT VALIDASI UKURAN FILE --}}
    <script>
        function validateFileSize(input) {
            const maxBytes = 2 * 1024 * 1024; // Batas maksimal 2 MB
            const file = input.files[0];
            const orderId = input.id.replace('payment_proof_', '');
            const errorDiv = document.getElementById('error_msg_' + orderId);
            const form = input.closest('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            if (file && file.size > maxBytes) {
                // Munculkan pesan error, ubah warna input, matikan tombol
                errorDiv.classList.remove('d-none');
                input.classList.add('is-invalid');
                submitBtn.disabled = true;
                input.value = ''; // Mengosongkan file yang melebihi batas ukuran
            } else {
                // Sembunyikan error, kembalikan tampilan, aktifkan tombol
                errorDiv.classList.add('d-none');
                input.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        }
    </script>
@endsection
