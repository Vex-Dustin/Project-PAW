@extends('app.master')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-md-flex align-items-start gap-4">
        {{-- Sidebar --}}
        <div class="d-none d-md-block flex-shrink-0" style="width: 260px;">
            @include('app.sidebar')
        </div>

        {{-- Konten Utama --}}
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 fw-bold m-0">Dashboard Admin</h1>
                <span class="badge bg-light text-dark border rounded-pill">{{ date('d M Y') }}</span>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold text-muted small text-uppercase mb-4">Statistik Platform</h6>

                {{-- Baris Statistik Utama --}}
                <div class="row g-3 mb-4">
                    {{-- User Card --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center h-100">
                            <div class="p-2 bg-primary bg-opacity-10 rounded-3 me-3"><i
                                    class="bi bi-people text-primary"></i></div>
                            <div><small class="text-muted d-block" style="font-size: 0.7rem;">TOTAL
                                    USER</small><strong>{{ $total_users }}</strong></div>
                        </div>
                    </div>

                    {{-- Verifikasi Card --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center h-100">
                            <div class="p-2 bg-danger bg-opacity-10 rounded-3 me-3"><i
                                    class="bi bi-patch-exclamation text-danger"></i></div>
                            <div><small class="text-muted d-block"
                                    style="font-size: 0.7rem;">VERIFIKASI</small><strong>{{ $pending_products }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- REVENUE Card --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center h-100">
                            <div class="p-2 bg-success bg-opacity-10 rounded-3 me-3"><i
                                    class="bi bi-currency-dollar text-success"></i></div>
                            <div><small class="text-muted d-block"
                                    style="font-size: 0.7rem;">REVENUE</small><strong>Rp{{ number_format($total_revenue, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- CARD BARU: Laporan Pending --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center h-100 position-relative">
                            <div class="p-2 bg-warning bg-opacity-10 rounded-3 me-3"><i
                                    class="bi bi-exclamation-octagon text-warning"></i></div>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">ADUAN BARU</small>
                                @php $pending_reports = \App\Models\Report::where('status', 'pending')->count(); @endphp
                                <strong>{{ $pending_reports }}</strong>
                            </div>
                            @if ($pending_reports > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                                    style="margin-left: -15px; margin-top: 15px;"></span>
                            @endif
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-muted small text-uppercase mb-3">Navigasi Cepat</h6>
                <div class="list-group list-group-flush border rounded-4 overflow-hidden shadow-sm">
                    <a href="{{ route('admin.verifications.index') }}"
                        class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                        <div><i class="bi bi-patch-check text-danger me-2"></i> Periksa Produk Baru</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                        <div><i class="bi bi-person-gear text-primary me-2"></i> Kelola User & Role</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center text-dark">
                        <div><i class="bi bi-tags text-success me-2"></i> Kelola Kategori Produk</div>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>

                    {{-- MENU BARU: Kelola Laporan --}}
                    <a href="{{ route('reports.index') }}"
                        class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center bg-warning bg-opacity-10">
                        <div class="fw-bold"><i class="bi bi-chat-square-dots text-warning me-2"></i> Kelola Aduan & Laporan
                        </div>
                        <div class="d-flex align-items-center">
                            @if ($pending_reports > 0)
                                <span class="badge bg-danger rounded-pill me-2">{{ $pending_reports }} Baru</span>
                            @endif
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
