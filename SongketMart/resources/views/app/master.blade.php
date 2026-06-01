<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SongketMart - @yield('title')</title>

    {{-- CSS External --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- CSS Internal (Dahulu panjang, sekarang rapi) --}}
    @include('app.partials.styles')
</head>

<body>

    {{-- Navbar Desktop --}}
    <nav class="navbar navbar-expand-md navbar-desktop d-none d-md-block">
        @include('app.navbar')
    </nav>

    <main class="container py-4">
        {{-- Area Notifikasi Global --}}
        @include('app.partials.alerts')

        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Navbar Mobile --}}
    @include('app.bottom_nav')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- Tempat untuk script tambahan khusus di halaman tertentu --}}
</body>

</html>
