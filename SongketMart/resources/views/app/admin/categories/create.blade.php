@extends('app.master')

@section('title', 'Tambah Kategori Produk')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            {{-- KOLOM KIRI: SIDEBAR --}}
            <div class="col-md-3">
                {{-- Pastikan path include ini sama dengan yang Anda gunakan di halaman index --}}
                @include('app.sidebar') {{-- Sesuaikan jika nama file/folder sidebar Anda berbeda --}}
            </div>

            {{-- KOLOM KANAN: FORM TAMBAH KATEGORI --}}
            <div class="col-md-9">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light shadow-sm me-3 rounded-circle"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0">Tambah Kategori Baru</h4>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        {{-- Menampilkan pesan error validasi jika ada --}}
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nama Kategori <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Contoh: Songket Palembang, Batik Jumputan..." required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nama kategori harus unik dan belum pernah dibuat sebelumnya.</div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">Deskripsi Kategori (Opsional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Tuliskan deskripsi singkat mengenai kain ini...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="btn btn-light px-4 rounded-pill">Batal</a>
                                <button type="submit" class="btn btn-maroon px-4 rounded-pill shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
