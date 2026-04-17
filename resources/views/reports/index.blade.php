@extends('layouts.master', ['title' => 'Laporan & Analitik'])

@section('content')
<!-- Filter -->
<div class="card mb-4 animate-in">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                <a href="{{ route('reports.index') }}" class="btn btn-ghost">Reset</a>
                <a href="{{ route('reports.export', ['start_date' => request('start_date', $startDate->format('Y-m-d')), 'end_date' => request('end_date', $endDate->format('Y-m-d'))]) }}" class="btn btn-success text-white">
                    <i class="fa-solid fa-file-excel me-1"></i> Export (CSV)
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-4 animate-in" style="animation-delay:.1s;">
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:rgba(34,197,94,.1);color:#16a34a;">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:rgba(99,102,241,.1);color:#6366f1;">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value" style="color:#6366f1;">{{ number_format($totalTransactions) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 animate-in" style="animation-delay:.2s;">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-area me-2" style="color:#6366f1;"></i>Grafik Penjualan</h6>
                <canvas id="salesChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-fire me-2 text-danger"></i>Top 5 Terlaris</h6>
                @forelse($topProducts as $i => $item)
                <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:#f1f5f9 !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-circle d-flex align-items-center justify-content-center" 
                              style="width:24px;height:24px;font-size:.7rem;{{ $i===0 ? 'background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;' : 'background:#f1f5f9;color:#64748b;' }}">
                            {{ $i + 1 }}
                        </span>
                        <span style="font-size:.85rem;font-weight:500;">{{ $item->product->name ?? 'Dihapus' }}</span>
                    </div>
                    <span class="badge" style="background:rgba(99,102,241,.1);color:#6366f1;">{{ $item->total_qty }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.85rem;">
                    <i class="fa-solid fa-chart-simple fa-2x mb-2 opacity-25"></i><br>
                    Belum ada data
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($chartValues) !!},
            backgroundColor: 'rgba(99,102,241,0.08)',
            borderColor: '#6366f1',
            borderWidth: 2.5,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#6366f1',
            pointBorderWidth: 2,
            pointRadius: 4,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
