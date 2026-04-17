@extends('layouts.master', ['title' => 'Riwayat Transaksi'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-1">Riwayat Transaksi</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Semua transaksi yang telah dilakukan</p>
    </div>
</div>

<div class="card animate-in" style="animation-delay:.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. TRX</th>
                        <th>Waktu</th>
                        <th>Total</th>
                        <th>Diskon</th>
                        <th>Pajak</th>
                        <th>Bayar</th>
                        <th>Kembali</th>
                        <th>Metode</th>
                        <th class="text-center" width="80">Struk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="fw-semibold">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-muted">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold" style="color:#6366f1;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="text-muted">{{ $trx->discount > 0 ? 'Rp '.number_format($trx->discount, 0, ',', '.') : '-' }}</td>
                        <td class="text-muted">{{ $trx->tax > 0 ? 'Rp '.number_format($trx->tax, 0, ',', '.') : '-' }}</td>
                        <td>Rp {{ number_format($trx->pay_amount, 0, ',', '.') }}</td>
                        <td class="text-success">Rp {{ number_format($trx->return_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($trx->payment_method == 'Cash')
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-money-bill-wave me-1"></i>Cash</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-qrcode me-1"></i>QRIS</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pos.receipt', $trx->id) }}" target="_blank" class="btn btn-sm btn-ghost px-2" title="Cetak Struk">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fa-2x mb-2 opacity-25"></i>
                            <div>Belum ada transaksi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
