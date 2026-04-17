@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mt-3">
        <div class="card text-bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title">Pendapatan Hari Ini</h5>
                <h1 class="display-6 fw-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</h1>
                <p class="card-text">Total <b>{{ $todayTransactions->count() }}</b> transaksi hari ini.</p>
            </div>
        </div>
    </div>
</div>
@endsection
