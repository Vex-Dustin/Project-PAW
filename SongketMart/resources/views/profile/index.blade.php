@extends('app.master')

@section('title', 'Pengaturan Profil')

@section('content')
    <div class="d-md-flex align-items-start gap-4">
        {{-- Sidebar --}}
        <div class="d-none d-md-block flex-shrink-0" style="width: 260px;">
            @include('app.sidebar')
        </div>

        {{-- Konten Utama --}}
        <div class="flex-grow-1">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">
                    <i class="bi bi-person-gear me-2"></i>Pengaturan Profil
                </h4>

                {{-- Alert Sukses/Error sudah dihapus dari sini karena sudah ada di master.blade.php --}}

                {{-- FORM 1: UPDATE DATA DIRI --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold small text-muted text-uppercase mb-3">Informasi Akun</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email"
                                class="form-control rounded-3 @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($user->role === 'penjual')
                            <div class="col-md-12 mt-3">
                                <div class="p-3 rounded-3 shop-box-highlight">
                                    <label class="form-label small fw-bold" style="color: var(--primary-maroon);">
                                        <i class="bi bi-shop me-1"></i> Nama Toko (Identitas Penjual)
                                    </label>
                                    <input type="text" name="shop_name"
                                        class="form-control rounded-3 @error('shop_name') is-invalid @enderror"
                                        value="{{ old('shop_name', $user->shop_name) }}"
                                        placeholder="Contoh: Zainal Songket Palembang">
                                    @error('shop_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small text-muted italic">Nama ini akan muncul pada detail produk
                                        dan pesanan.</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 mb-5">
                        <button type="submit" class="btn btn-maroon px-4 py-2 shadow-sm rounded-pill">
                            <i class="bi bi-save me-2"></i>Simpan Profil
                        </button>
                    </div>
                </form>

                {{-- FORM 2: UPDATE PASSWORD --}}
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold small text-muted text-uppercase mb-3 mt-4 border-top pt-4">Ubah Password Keamanan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                class="form-control rounded-3 @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password Baru</label>
                            <input type="password" name="new_password"
                                class="form-control rounded-3 @error('new_password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control rounded-3"
                                placeholder="Ulangi password baru" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-outline-dark px-4 py-2 shadow-sm rounded-pill">
                            <i class="bi bi-shield-lock me-2"></i>Ubah Password
                        </button>
                    </div>
                </form>

                {{-- FORM 3: LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST" class="mt-5 border-top pt-4 text-center">
                    @csrf
                    <p class="text-muted small">Ingin keluar dari sesi saat ini?</p>
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout dari Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
