@extends('app.master')

@section('content')
    {{-- Wrapper Utama --}}
    <div class="container-fluid pt-3 seller-main-wrapper">
        <div class="row g-4">

            <div class="col-md-3">
                <div class="sidebar-container mb-3">
                    @include('app.sidebar')
                </div>
            </div>

            <div class="col-md-9">
                <div class="main-content-area">
                    @yield('seller_content')
                </div>

                {{-- BLOK PENGGANJAL (Spacer) --}}
                {{-- Elemen ini tidak terlihat, tapi memaksa halaman bertambah panjang ke bawah --}}
                <div class="mobile-spacer d-block d-md-none" style="height: 150px; width: 100%;"></div>
            </div>
        </div>
    </div>
@endsection
