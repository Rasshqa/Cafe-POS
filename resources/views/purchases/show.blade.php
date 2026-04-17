@extends('layouts.master', ['title' => 'Detail Pembelian #' . str_pad($purchase->id, 4, '0', STR_PAD_LEFT)])

@section('content')
<div class="mb-4 animate-in">
    <a href="{{ route('purchases.index') }}" class="btn btn-ghost btn-sm mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card animate-in">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Info Pembelian</h6>
                <table class="table table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="120">No.</td><td class="fw-semibold">#{{ str_pad($purchase->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                    <tr><td class="text-muted">Supplier</td><td class="fw-semibold">{{ $purchase->supplier->name }}</td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $purchase->purchase_date->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Total</td><td class="fw-bold" style="color:#6366f1;">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td></tr>
                    @if($purchase->notes)
                    <tr><td class="text-muted">Catatan</td><td>{{ $purchase->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card animate-in" style="animation-delay:.1s;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Item Pembelian</h6>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Produk</th><th>Qty</th><th>Harga Beli</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->details as $d)
                            <tr>
                                <td class="fw-semibold">{{ $d->product->name ?? 'Dihapus' }}</td>
                                <td>{{ $d->qty }}</td>
                                <td>Rp {{ number_format($d->buy_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold" style="color:#6366f1;">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
