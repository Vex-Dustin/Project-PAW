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

<body class="d-flex flex-column min-vh-100">

    {{-- Navbar Desktop --}}
    <nav class="navbar navbar-expand-md navbar-desktop d-none d-md-block">
        @include('app.navbar')
    </nav>

    <main class="container py-4 flex-grow-1">
        {{-- Area Notifikasi Global --}}
        @include('app.partials.alerts')

        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer py-3 border-top bg-white mt-auto">
        <div class="container">
            <div class="row align-items-center justify-content-between text-center text-md-start">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <span class="text-muted small">
                        &copy; {{ date('Y') }} <strong style="color: var(--primary-maroon);">SongketMart</strong>. Hak Cipta Dilindungi.
                    </span>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <span class="text-muted small">
                        Pelestarian Warisan Nusantara &middot; Kelompok Project PAW
                    </span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Navbar Mobile --}}
    @include('app.bottom_nav')

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- Tempat untuk script tambahan khusus di halaman tertentu --}}
</body>

</html>
