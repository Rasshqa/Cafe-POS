@extends('layouts.master', ['title' => 'Kategori Menu'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-1">Kategori Menu</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola kategori produk cafe Anda</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah
    </button>
</div>

<div class="card animate-in" style="animation-delay:.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Nama Kategori</th>
                        <th width="120">Jumlah Produk</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $i => $category)
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>
                            <span class="badge" style="background:rgba(99,102,241,.1);color:#6366f1;">
                                {{ $category->products_count ?? $category->products()->count() }} item
                            </span>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-ghost text-danger px-2"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-folder-open fa-2x mb-2 opacity-25"></i>
                            <div>Belum ada kategori</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Kategori</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Kopi, Snack" required autofocus>
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
