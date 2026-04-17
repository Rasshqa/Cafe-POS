@extends('layouts.master', ['title' => 'Laporan & Analitik'])

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary shadow-sm"><i class="fa-solid fa-filter me-1"></i> Filter Data</button>
                <a href="{{ route('reports.index') }}" class="btn btn-light border shadow-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
            <div class="card-body">
                <p class="text-muted text-uppercase mb-1" style="font-size: 0.8rem fw-bold;">Total Pendapatan (Filter)</p>
                <h3 class="fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
            <div class="card-body">
                <p class="text-muted text-uppercase mb-1" style="font-size: 0.8rem fw-bold;">Total Transaksi (Filter)</p>
                <h3 class="fw-bold text-primary">{{ number_format($totalTransactions) }} <small class="text-muted fs-6">Order</small></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white mt-1 border-bottom-0"><h6 class="fw-bold"><i class="fa-solid fa-chart-area text-primary me-1"></i> Grafik Penjualan Harian</h6></div>
            <div class="card-body pt-0">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white mt-1 border-bottom-0"><h6 class="fw-bold"><i class="fa-solid fa-fire text-danger me-1"></i> Top 5 Produk Terlaris</h6></div>
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush">
                    @forelse($topProducts as $item)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            {{ $item->product->name ?? 'Produk Dihapus' }}
                            <span class="badge bg-primary rounded-pill">{{ $item->total_qty }} terjual</span>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">Belum ada data penjualan</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartValues) !!},
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
