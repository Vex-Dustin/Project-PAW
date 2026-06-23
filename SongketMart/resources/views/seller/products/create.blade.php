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

        {{-- Alert Global jika ada error validasi dari server (termasuk foto kebesaran) --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="row g-4">
                {{-- Kolom Kiri: Informasi Dasar --}}
                <div class="col-md-8">
                    <div class="p-4 bg-light rounded-4 border-0">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Contoh: Songket Lepus Merah Emas" required>
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
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="5"
                                placeholder="Ceritakan motif, bahan, dan keunggulan songket ini..." required>{{ old('description') }}</textarea>
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
                            <input type="number" name="price"
                                class="form-control form-control-lg rounded-3 @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" placeholder="Contoh: 1500000" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Stok (Pcs) <span class="text-danger">*</span></label>
                            <input type="number" name="stock"
                                class="form-control form-control-lg rounded-3 @error('stock') is-invalid @enderror"
                                value="{{ old('stock') }}" placeholder="Jumlah tersedia" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-white border rounded-4 shadow-sm">
                        <label class="form-label fw-bold">Foto Produk</label>
                        <small class="d-block text-muted mb-2">Format: JPG/PNG. Maksimal: 2MB.</small>

                        {{-- Input Foto --}}
                        <input type="file" name="image" id="image_input"
                            class="form-control mb-2 rounded-3 @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Elemen Alert Khusus JS (Disembunyikan secara default) --}}
                        <div id="image_size_warning" class="text-danger small mt-1 d-none">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Ukuran foto melebihi 2MB! Silakan pilih
                            foto lain yang lebih kecil.
                        </div>

                        <div class="form-check mt-3">
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
                    <a href="{{ route('seller.products.index') }}"
                        class="btn btn-outline-secondary px-4 py-2 rounded-pill me-2 fw-bold">Batal</a>
                    <button type="submit" id="submit_btn" class="btn px-4 py-2 rounded-pill fw-bold text-white shadow-sm"
                        style="background-color: var(--primary-maroon);">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Simpan & Unggah Produk
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Script Validasi Ukuran File (Frontend) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image_input');
            const warningText = document.getElementById('image_size_warning');
            const submitBtn = document.getElementById('submit_btn');
            const noImageCheckbox = document.getElementById('no_image');

            // Atur batas maksimal ukuran file dalam bytes (Contoh: 2MB = 2 * 1024 * 1024 bytes)
            const maxSizeInBytes = 2 * 1024 * 1024;

            imageInput.addEventListener('change', function() {
                // Cek apakah ada file yang dipilih
                if (this.files && this.files.length > 0) {
                    const fileSize = this.files[0].size;

                    if (fileSize > maxSizeInBytes) {
                        // Jika lebih dari 2MB: Tampilkan peringatan, beri border merah, dan matikan tombol submit
                        warningText.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        submitBtn.disabled = true;
                    } else {
                        // Jika aman: Sembunyikan peringatan, hapus border merah, dan aktifkan tombol submit
                        warningText.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        submitBtn.disabled = false;
                    }
                }
            });

            // Logika untuk checkbox "Lanjutkan tanpa foto sementara"
            noImageCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    imageInput.value = ''; // Hapus file yang sudah dipilih
                    imageInput.disabled = true; // Nonaktifkan input
                    warningText.classList.add('d-none'); // Sembunyikan error jika ada
                    imageInput.classList.remove('is-invalid');
                    submitBtn.disabled = false; // Pastikan tombol bisa ditekan
                } else {
                    imageInput.disabled = false; // Aktifkan kembali
                }
            });
        });
    </script>
@endsection
