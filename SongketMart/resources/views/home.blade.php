<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - SongketMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="bi bi-shop me-2"></i>SongketMart</a>
            <div>
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
                @else
                <a href="{{ url('/login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-light btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <i class="bi bi-shop display-1 text-primary"></i>
                <h1 class="mt-3 fw-bold">SongketMart</h1>
                <p class="lead text-muted mt-2">Jual dan Beli Batik dengan Mudah</p>
                <div class="mt-4 d-flex justify-content-center gap-3">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2 me-2"></i>Masuk ke Dashboard
                    </a>
                    @else
                    <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ url('/register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Daftar Akun
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center text-muted mt-5 py-4 border-top">
        &copy; {{ date('Y') }} SongketMart. All rights reserved.
    </footer>
</body>

</html>