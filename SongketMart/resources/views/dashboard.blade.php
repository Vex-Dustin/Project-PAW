@extends('app.master')
@section('title', 'Dashboard')
@section('sidebar')
    @parent
@endsection
@section('content')
<div class="mb-4">
    <h1 class="fw-black text-dark tracking-tight">Dashboard</h1>
</div>

<div class="alert bg-white border-start border-dark border-4 shadow-sm p-4 rounded-3 d-flex align-items-center justify-content-between" role="alert">
    <div>
        <h5 class="fw-bold text-dark mb-1">Halo, {{ Auth::user()->name }}!</h5>
        <p class="text-muted small m-0">Anda masuk menggunakan email: {{ Auth::user()->email }}</p>
    </div>
    <span class="badge bg-dark px-3 py-2 d-none d-sm-block">Online</span>
</div>
@endsection