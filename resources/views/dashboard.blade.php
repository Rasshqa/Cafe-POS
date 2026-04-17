@extends('layouts.master', ['title' => 'Dashboard Utama'])

@section('content')
<div class="row align-items-stretch">
    <!-- Card Pendapatan -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #28a745 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.8rem;">Pendapatan Hari Ini</p>
                    <h3 class="mb-0 fw-bold text-dark">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success ms-2">
                    <i class="fa-solid fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Transaksi -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #3498db !important;">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.8rem;">Transaksi Hari Ini</p>
                    <h3 class="mb-0 fw-bold text-dark">{{ $todayTransactions->count() }} <small class="text-muted fs-6">Order</small></h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary ms-2">
                    <i class="fa-solid fa-file-invoice-dollar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card Placeholder Laporan -->
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="{{ route('reports.index') }}" class="text-decoration-none">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #f39c12 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.8rem;">Laporan & Analitik</p>
                    <h3 class="mb-0 fw-bold text-dark text-muted fs-5">Lihat Detail <i class="fa-solid fa-arrow-right fs-6"></i></h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning ms-2">
                    <i class="fa-solid fa-chart-pie fa-2x"></i>
                </div>
            </div>
        </div>
        </a>
    </div>
</div>

<!-- Welcome Message -->
<div class="row mt-2 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-light rounded px-4 py-4 d-flex align-items-center">
                <div class="me-4 text-primary d-none d-md-block">
                    <i class="fa-solid fa-rocket fa-4x"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Selamat Datang di Sistem Kasir Pro!</h5>
                    <p class="text-muted mb-0">Antarmuka baru ini menggunakan konsep <i>Clean Sidebar Layout</i> yang jauh lebih profesional. Semua fitur bisa diakses dengan mudah lewat panel di sebelah kiri. Kita akan segera mengembangkan fitur tambahan pada tahapan berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
