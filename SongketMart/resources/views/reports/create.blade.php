@extends('app.master')

@section('title', 'Buat Laporan Baru')

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
                <h4 class="fw-bold mb-0">Buat Laporan Baru</h4>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('reports.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipe Laporan</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="Pesanan">Masalah Pesanan</option>
                                    <option value="Produk">Masalah Produk</option>
                                    <option value="Akun">Masalah Akun/Login</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Subjek Singkat</label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                    placeholder="Contoh: Barang belum sampai" required>
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Detail Laporan</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                                rows="6" placeholder="Jelaskan kendala Anda secara rinci..." required></textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('reports.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-maroon px-4 rounded-pill shadow-sm">
                                <i class="bi bi-send me-2"></i> Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection