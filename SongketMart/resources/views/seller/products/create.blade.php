@extends('app.seller_layout')

@section('title', 'Tambah Produk Baru - SongketMart')

@section('seller_content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('seller.products.index') }}" class="btn btn-light rounded-circle p-2 me-3 shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0" style="color: var(--primary-maroon);">Tambah Produk Baru</h3>
            <p class="text-muted small mb-0">Masukkan detail kain songket yang ingin Anda jual.</p>
        </div>
    </div>

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            {{-- Kolom Kiri: Informasi Dasar --}}
            <div class="col-md-8">
                <div class="p-4 bg-light rounded-4 border-0">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Songket Lepus Merah Emas" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select form-select-lg rounded-3" required>
                            <option value="">-- Pilih Jenis Songket --</option>
                            @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Deskripsi Produk <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control rounded-3" rows="5" placeholder="Ceritakan motif, bahan, dan keunggulan songket ini..." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Harga, Stok, & Foto --}}
            <div class="col-md-4">
                <div class="p-4 bg-white border rounded-4 shadow-sm mb-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control form-control-lg rounded-3" placeholder="Contoh: 1500000" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Stok (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control form-control-lg rounded-3" placeholder="Jumlah tersedia" required>
                    </div>
                </div>

                <div class="p-4 bg-white border rounded-4 shadow-sm">
                    <label class="form-label fw-bold">Foto Produk</label>
                    <input type="file" name="image" id="image_input" class="form-control mb-2 rounded-3" accept="image/*">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="no_image">
                        <label class="form-check-label small text-muted" for="no_image">
                            Lanjutkan tanpa foto sementara
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="col-12 mt-4 text-end">
                <hr class="opacity-25 mb-4">
                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill me-2 fw-bold">Batal</a>
                <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm" style="background-color: var(--primary-maroon);">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Simpan & Unggah Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
