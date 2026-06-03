@extends('app.master')

@section('title', 'Kelola Kategori Produk')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            {{-- KOLOM KIRI: SIDEBAR --}}
            <div class="col-md-3">
                @include('app.sidebar') {{-- PASTIKAN INI SESUAI DENGAN NAMA FILE SIDEBAR ANDA --}}
            </div>

            {{-- KOLOM KANAN: KONTEN UTAMA KATEGORI --}}
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Manajemen Kategori</h4>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-maroon rounded-pill px-3 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        <th>Nama Kategori</th>
                                        <th>Slug</th>
                                        <th>Deskripsi</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $key => $category)
                                        <tr>
                                            <td class="ps-4">{{ $key + 1 }}</td>
                                            <td class="fw-bold">{{ $category->name }}</td>
                                            <td><code>{{ $category->slug }}</code></td>
                                            <td class="text-muted small">{{ Str::limit($category->description, 50) }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Yakin ingin menghapus kategori ini? Produk yang menggunakan kategori ini mungkin akan terpengaruh.')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data kategori.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
