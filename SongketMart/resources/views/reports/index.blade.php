@extends('app.master')

@section('title', 'Pusat Laporan & Bantuan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            {{-- KOLOM KIRI: SIDEBAR --}}
            <div class="col-md-3">
                @include('app.sidebar') {{-- Sesuaikan path sidebar Anda --}}
            </div>

            {{-- KOLOM KANAN: KONTEN UTAMA --}}
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Pusat Laporan</h4>
                        <p class="text-muted small">
                            {{ auth()->user()->role == 'admin' ? 'Kelola aduan dari pengguna' : 'Riwayat aduan Anda kepada Admin' }}
                        </p>
                    </div>
                    {{-- Tombol hanya muncul untuk Penjual/Pembeli --}}
                    @if (auth()->user()->role != 'admin')
                        <a href="{{ route('reports.create') }}" class="btn btn-maroon rounded-pill px-4 shadow-sm">
                            <i class="bi bi-chat-dots me-2"></i> Buat Laporan
                        </a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        @if (auth()->user()->role == 'admin')
                                            <th>Pelapor</th>
                                        @endif
                                        <th>Subjek</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $key => $report)
                                        <tr>
                                            <td class="ps-4">{{ $reports->firstItem() + $key }}</td>
                                            @if (auth()->user()->role == 'admin')
                                                <td>
                                                    <span class="fw-bold">{{ $report->user->name }}</span><br>
                                                    <small class="text-muted">{{ ucfirst($report->user->role) }}</small>
                                                </td>
                                            @endif
                                            <td>
                                                <span class="d-block fw-bold text-truncate"
                                                    style="max-width: 200px;">{{ $report->subject }}</span>
                                                <small class="text-muted">{{ $report->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $report->type }}</span>
                                            </td>
                                            <td>
                                                @if ($report->status == 'pending')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning px-3 rounded-pill">Menunggu</span>
                                                @elseif($report->status == 'process')
                                                    <span
                                                        class="badge bg-info-subtle text-info px-3 rounded-pill">Diproses</span>
                                                @else
                                                    <span
                                                        class="badge bg-success-subtle text-success px-3 rounded-pill">Selesai</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                {{-- Jika Admin, bisa update status --}}
                                                @if (auth()->user()->role == 'admin' && $report->status != 'resolved')
                                                    <form action="{{ route('reports.updateStatus', $report->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="resolved">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success rounded-pill px-3">
                                                            Selesaikan
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('reports.show', $report->id) }}"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Belum ada laporan masuk.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
