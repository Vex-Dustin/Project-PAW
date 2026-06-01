@extends('app.master')

@section('title', 'Daftar Akun')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: var(--primary-maroon);">Daftar Akun</h3>
                        <p class="text-muted">Bergabung dengan ekosistem SongketMart</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control bg-light border-0 p-3"
                                    placeholder="Nama Anda" value="{{ old('name') }}" required
                                    style="border-radius: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Pilih Peran</label>
                                <select name="role" id="roleSelect" class="form-select bg-light border-0 p-3" required
                                    style="border-radius: 12px;" onchange="toggleShopInput()">
                                    <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Saya Ingin
                                        Belanja</option>
                                    <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Saya Penjual
                                        (UMKM)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Form Input Nama Toko (Awalnya Disembunyikan oleh JS) --}}
                        <div class="mb-3" id="shopInputGroup"
                            style="display: {{ old('role') == 'penjual' ? 'block' : 'none' }};">
                            <label class="form-label small fw-bold">Nama Toko</label>
                            <input type="text" name="shop_name" id="shop_name"
                                class="form-control bg-light border-0 p-3 @error('shop_name') is-invalid @enderror"
                                placeholder="Contoh: Zainal Songket Palembang" value="{{ old('shop_name') }}"
                                style="border-radius: 12px;">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Nama ini yang akan dilihat
                                oleh pembeli Anda di SongketMart.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email"
                                class="form-control bg-light border-0 p-3 @error('email') is-invalid @enderror"
                                placeholder="nama@email.com" value="{{ old('email') }}" required
                                pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                                title="Gunakan format email lengkap, contoh: nama@email.com" style="border-radius: 12px;">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Kata Sandi</label>
                                <input type="password" name="password" class="form-control bg-light border-0 p-3"
                                    placeholder="Minimal 8 karakter" required style="border-radius: 12px;" minlength="8">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control bg-light border-0 p-3" placeholder="Ulangi kata sandi" required
                                    style="border-radius: 12px;" minlength="8">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-maroon w-100 p-3 fw-bold mb-3 shadow-sm">Buat Akun
                            Sekarang</button>
                    </form>

                    <div class="text-center">
                        <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}"
                                class="text-decoration-none fw-bold" style="color: var(--primary-maroon);">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleShopInput() {
            const role = document.getElementById('roleSelect').value;
            const shopGroup = document.getElementById('shopInputGroup');
            const shopInput = document.getElementById('shop_name');

            if (role === 'penjual') {
                shopGroup.style.display = 'block';
                shopInput.setAttribute('required', 'required');
            } else {
                shopGroup.style.display = 'none';
                shopInput.removeAttribute('required');
                shopInput.value = ''; // Kosongkan form jika berubah pikiran jadi pembeli
            }
        }

        // Panggil fungsi saat halaman pertama kali dimuat 
        // agar mempertahankan tampilan jika terjadi error validasi
        document.addEventListener("DOMContentLoaded", toggleShopInput);
    </script>
@endsection
