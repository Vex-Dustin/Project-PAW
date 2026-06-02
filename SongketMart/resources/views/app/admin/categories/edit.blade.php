@extends('app.master')

@section('title', 'Edit Kategori Produk')

@section('content')
    <div class="d-md-flex align-items-start gap-4">
        {{-- Sidebar --}}
        <div class="d-none d-md-block flex-shrink-0" style="width: 260px;">
            @include('app.sidebar')
        </div>

        {{-- Konten Utama --}}
        <div class="flex-grow-1">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light shadow-sm me-3 rounded-circle">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Edit Kategori</h4>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}" placeholder="Contoh: Songket Lepus" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                placeholder="Opsional: Tuliskan deskripsi mengenai kategori ini...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.index') }}"
                                class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-maroon px-4 rounded-pill shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
