@extends('layouts.master', ['title' => 'Dashboard'])

@section('content')
<!-- Stats Row -->
<div class="row g-4 mb-4 animate-in">
    <div class="col-xl-4 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:rgba(34,197,94,.1);color:#16a34a;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                    <div class="stat-value text-success">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value" style="color:#6366f1;">{{ $todayTransactions->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <a href="{{ route('reports.index') }}" class="text-decoration-none">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="stat-label">Laporan Lengkap</div>
                        <div class="stat-value text-dark" style="font-size:1rem;">Lihat Analitik <i class="fa-solid fa-arrow-right ms-1" style="font-size:.8rem;"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 animate-in" style="animation-delay:.15s;">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i>Aksi Cepat</h6>
                <div class="row g-3">
                    <div class="col-sm-4">
                        <a href="{{ route('pos.index') }}" class="btn btn-primary w-100 py-3">
                            <i class="fa-solid fa-cash-register d-block mb-1" style="font-size:1.4rem;"></i>
                            Buka Kasir
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ route('products.index') }}" class="btn btn-ghost w-100 py-3">
                            <i class="fa-solid fa-box-open d-block mb-1" style="font-size:1.4rem;"></i>
                            Tambah Produk
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ route('pos.history') }}" class="btn btn-ghost w-100 py-3">
                            <i class="fa-solid fa-clock-rotate-left d-block mb-1" style="font-size:1.4rem;"></i>
                            Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="fa-solid fa-rocket" style="font-size:2rem;opacity:.8;"></i>
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#fff;">Kasir Pro v2.0</h6>
                        <small style="opacity:.8;">Antarmuka Premium Edition</small>
                    </div>
                </div>
                <p class="mb-0 mt-2" style="font-size:.82rem;opacity:.85;">Kelola transaksi cafe Anda dengan cepat, akurat, dan profesional.</p>
            </div>
        </div>
    </div>
</div>
@endsection
