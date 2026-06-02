<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #ORD-{{ $order->id }} - SongketMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-maroon: #800000;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
        }

        .invoice-header {
            border-bottom: 3px solid var(--primary-maroon);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand-name {
            color: var(--primary-maroon);
            font-weight: 800;
            font-size: 2rem;
        }

        .table thead {
            background-color: var(--primary-maroon);
            color: white;
        }

        /* Gaya Khusus untuk Cetak (Print) */
        @media print {
            body {
                background-color: white;
            }

            .invoice-card {
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .invoice-header {
                margin-top: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        {{-- Tombol Navigasi (Hilang saat diprint) --}}
        <div class="max-width: 800px; margin: 20px auto;" style="max-width: 800px; margin: 20px auto;">
            <div class="d-flex justify-content-between no-print">
                <a href="{{ route('order.index') }}" class="btn btn-secondary rounded-pill">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button onclick="window.print()" class="btn btn-danger rounded-pill"
                    style="background-color: var(--primary-maroon);">
                    <i class="bi bi-printer"></i> Cetak / Download PDF
                </button>
            </div>
        </div>

        <div class="invoice-card">
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="brand-name">SongketMart</div>
                    <p class="text-muted mb-0">Pusat Kerajinan Songket Palembang Asli</p>
                </div>
                <div class="text-end">
                    <h2 class="fw-bold mb-0">INVOICE</h2>
                    <span class="badge bg-success">PESANAN {{ strtoupper($order->status) }}</span>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-6">
                    <h6 class="text-muted text-uppercase small fw-bold">Ditagihkan Kepada:</h6>
                    <p class="fw-bold mb-1">{{ Auth::user()->name }}</p>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-muted text-uppercase small fw-bold">Detail Pesanan:</h6>
                    <p class="mb-1"><strong>ID:</strong> #ORD-{{ $order->id }}</p>
                    <p class="mb-0"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th class="py-3 ps-3">Deskripsi Produk</th>
                        <th class="py-3 text-center">Jumlah</th>
                        <th class="py-3 text-end pe-3">Harga Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Contoh Data Statis (Silakan sesuaikan dengan tabel order_items Anda jika ada) --}}
                    <tr>
                        <td class="py-4 ps-3">
                            <span class="fw-bold">Kain Songket Tradisional</span><br>
                            <small class="text-muted">Kualitas Premium Hand-made</small>
                        </td>
                        <td class="py-4 text-center">1</td>
                        <td class="py-4 text-end pe-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-top">
                        <td colspan="2" class="text-end py-3 fw-bold fs-5">Total Bayar</td>
                        <td class="text-end py-3 pe-3 fw-bold fs-5 text-maroon" style="color: var(--primary-maroon);">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-5 p-4 rounded-4 bg-light">
                <h6 class="fw-bold small text-uppercase">Catatan:</h6>
                <p class="small text-muted mb-0">Terima kasih telah melestarikan budaya bangsa dengan membeli Songket
                    asli. Invoice ini adalah bukti pembayaran yang sah.</p>
            </div>

            <div class="text-center mt-5">
                <p class="small text-muted">© {{ date('Y') }} SongketMart Palembang. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
