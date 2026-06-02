@extends('app.master')

@section('title', 'Kelola User - SongketMart')

@section('content')
    <div class="container-fluid pt-3 mb-5">
        <div class="row g-4">
            {{-- Sidebar --}}
            <div class="col-md-3 d-none d-md-block">
                @include('app.sidebar')
            </div>

            {{-- Konten Utama --}}
            <div class="col-md-9">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0 h5" style="color: var(--primary-maroon);">
                                <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
                            </h2>
                            <p class="text-muted small mb-0">Total: {{ $users->total() }} Pengguna Terdaftar</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success border-0 rounded-4 mb-4 small">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small fw-bold ps-3">NAMA / EMAIL</th>
                                    <th class="border-0 small fw-bold">ROLE SAAT INI</th>
                                    <th class="border-0 small fw-bold text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $u)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold mb-0">{{ $u->name }}</div>
                                            <small class="text-muted">{{ $u->email }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $u->role == 'penjual' ? 'bg-info' : 'bg-secondary' }} bg-opacity-10 {{ $u->role == 'penjual' ? 'text-info' : 'text-secondary' }} rounded-pill px-3 py-2 small">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- Form Ganti Role --}}
                                                <form action="{{ route('admin.users.update-role', $u->id) }}" method="POST"
                                                    class="d-flex gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" class="form-select form-select-sm rounded-3"
                                                        style="width: 110px;">
                                                        <option value="pembeli"
                                                            {{ $u->role == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                                        <option value="penjual"
                                                            {{ $u->role == 'penjual' ? 'selected' : '' }}>Penjual</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-dark rounded-3"
                                                        title="Simpan Role">
                                                        <i class="bi bi-check2"></i>
                                                    </button>
                                                </form>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
