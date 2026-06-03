@extends('app.master')

@section('title', 'Detail Laporan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3">
                @include('app.sidebar')
            </div>

            <div class="col-md-9">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('reports.index') }}" class="btn btn-light shadow-sm me-3 rounded-circle">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0">Detail Laporan #{{ $report->id }}</h4>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $report->type }}</span>
                            <span class="text-muted small">{{ $report->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <h5 class="fw-bold mb-3">{{ $report->subject }}</h5>

                        <div class="p-3 bg-light rounded-3 mb-4">
                            <p class="mb-0" style="white-space: pre-line;">{{ $report->message }}</p>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label class="d-block text-muted small">Pelapor:</label>
                                <span class="fw-bold">{{ $report->user->name }}</span>
                                <span
                                    class="badge bg-secondary-subtle text-secondary small">{{ $report->user->role }}</span>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <label class="d-block text-muted small">Status Saat Ini:</label>
                                @if ($report->status == 'pending')
                                    <span class="badge bg-warning text-dark px-3 rounded-pill">Menunggu</span>
                                @elseif($report->status == 'process')
                                    <span class="badge bg-info text-white px-3 rounded-pill">Diproses Admin</span>
                                @else
                                    <span class="badge bg-success text-white px-3 rounded-pill">Selesai</span>
                                @endif
                            </div>
                        </div>

                        {{-- Menggunakan Policy updateStatus --}}
                        @can('updateStatus', $report)
                            @if ($report->status != 'resolved')
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('reports.updateStatus', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="process">
                                        <button type="submit" class="btn btn-outline-info rounded-pill px-4"
                                            {{ $report->status == 'process' ? 'disabled' : '' }}>
                                            Tandai Sedang Diproses
                                        </button>
                                    </form>

                                    <form action="{{ route('reports.updateStatus', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                            Selesaikan Masalah
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
