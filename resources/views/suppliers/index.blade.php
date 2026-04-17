@extends('layouts.master', ['title' => 'Supplier'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-1">Data Supplier</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola data pemasok barang</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Supplier
    </button>
</div>

<div class="card animate-in" style="animation-delay:.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Pembelian</th>
                        <th width="80" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $i => $s)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $s->name }}</td>
                        <td class="text-muted">{{ $s->contact ?? '-' }}</td>
                        <td class="text-muted" style="max-width:200px;">{{ Str::limit($s->address, 40) ?? '-' }}</td>
                        <td><span class="badge" style="background:rgba(99,102,241,.1);color:#6366f1;">{{ $s->purchases_count }} order</span></td>
                        <td class="text-center">
                            <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-ghost text-danger px-2"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-truck fa-2x mb-2 opacity-25"></i><div>Belum ada supplier</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h6 class="modal-title fw-bold">Tambah Supplier</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="PT. Kopi Nusantara">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak (Telp/WA)</label>
                        <input type="text" name="contact" class="form-control" placeholder="08123456789">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Jl. Raya No. 1"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
