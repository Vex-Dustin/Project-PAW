@extends('app.seller_layout')

@section('title', 'Edit Produk - ' . $product->name)

@section('seller_content')
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('seller.products.index') }}" class="btn btn-light rounded-circle p-2 me-3 shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-bold mb-0" style="color: var(--primary-maroon);">Edit Produk</h3>
                <p class="text-muted small mb-0">Ubah informasi produk <strong>{{ $product->name }}</strong>.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Kolom Kiri: Informasi Dasar --}}
                <div class="col-md-8">
                    <div class="p-4 bg-light rounded-4 border-0">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id"
                                class="form-select form-select-lg rounded-3 @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Jenis Songket --</option>
                                @foreach ($categories ?? [] as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Harga, Stok, & Foto --}}
                <div class="col-md-4">
                    <div class="p-4 bg-white border rounded-4 shadow-sm mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-lg rounded-3"
                                value="{{ old('price', $product->price) }}" required>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Stok (Pcs) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control form-control-lg rounded-3"
                                value="{{ old('stock', $product->stock) }}" required>
                        </div>
                    </div>

                    <div class="p-4 bg-white border rounded-4 shadow-sm text-center">
                        <label class="form-label fw-bold d-block text-start">Foto Produk Saat Ini</label>

                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-3 shadow-sm mb-3"
                                style="max-height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 py-4 mb-3 text-muted border border-dashed">
                                <i class="bi bi-image fs-1 opacity-50"></i><br>
                                <small>Tidak ada foto</small>
                            </div>
                        @endif

                        <div class="text-start">
                            <input type="file" name="image" class="form-control rounded-3 form-control-sm"
                                accept="image/*">
                            <small class="text-muted" style="font-size: 0.7rem;">Abaikan jika tidak ingin mengganti
                                foto.</small>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="col-12 mt-4 text-end">
                    <hr class="opacity-25 mb-4">
                    <a href="{{ route('seller.products.index') }}"
                        class="btn btn-outline-secondary px-4 py-2 rounded-pill me-2 fw-bold">Batal</a>
                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm"
                        style="background-color: var(--primary-maroon);">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
