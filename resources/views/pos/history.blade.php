@extends('layouts.master')

@section('content')
<h4 class="mb-4">Riwayat Transaksi</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Waktu Transaksi</th>
                    <th>Total Belanja</th>
                    <th>Bayar (Cash)</th>
                    <th>Kembali</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td>{{ $trx->created_at->format('d M Y - H:i') }}</td>
                    <td class="fw-bold text-primary">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="text-success">Rp {{ number_format($trx->pay_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($trx->return_amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
