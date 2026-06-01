@extends('app.master')

@section('title', 'Masuk')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: var(--primary-maroon);">Selamat Datang</h3>
                        <p class="text-muted">Masuk untuk melanjutkan belanja</p>
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

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control bg-light border-0 p-3"
                                placeholder="nama@email.com" required style="border-radius: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Kata Sandi</label>
                            <input type="password" name="password" class="form-control bg-light border-0 p-3"
                                placeholder="••••••••" required style="border-radius: 12px;">
                        </div>
                        <button type="submit" class="btn btn-maroon w-100 p-3 fw-bold mb-3 shadow-sm">Masuk
                            Sekarang</button>
                    </form>

                    <div class="text-center">
                        <p class="small text-muted">Belum punya akun? <a href="{{ route('register') }}"
                                class="text-decoration-none fw-bold" style="color: var(--primary-maroon);">Daftar Gratis</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
