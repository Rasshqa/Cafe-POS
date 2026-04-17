@extends('layouts.master', ['title' => 'Riwayat Pembelian'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-1">Riwayat Pembelian Stok</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Catatan pembelian barang dari supplier</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Buat Pembelian
    </a>
</div>

<div class="card animate-in" style="animation-delay:.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Catatan</th>
                        <th class="text-center" width="80">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $i => $p)
                    <tr>
                        <td class="fw-semibold">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-muted">{{ $p->purchase_date->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $p->supplier->name ?? '-' }}</td>
                        <td class="fw-bold" style="color:#6366f1;">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                        <td class="text-muted">{{ Str::limit($p->notes, 30) ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('purchases.show', $p->id) }}" class="btn btn-sm btn-ghost px-2"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-file-invoice fa-2x mb-2 opacity-25"></i><div>Belum ada pembelian</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
