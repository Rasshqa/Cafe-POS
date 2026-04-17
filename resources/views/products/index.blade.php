@extends('layouts.master', ['title' => 'Data Produk'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h5 class="fw-bold mb-1">Data Produk</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola menu dan stok produk cafe</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Produk
    </button>
</div>

<div class="card animate-in" style="animation-delay:.1s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60" class="text-center">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="90" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-center">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="rounded-2" style="width:40px;height:40px;object-fit:cover;" alt="">
                            @else
                                <div class="d-inline-flex align-items-center justify-content-center rounded-2" style="width:40px;height:40px;background:#f1f5f9;">
                                    <i class="fa-solid fa-image text-muted" style="font-size:.75rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td>
                            <span class="badge" style="background:rgba(99,102,241,.1);color:#6366f1;">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning bg-opacity-10 text-warning">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Habis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-ghost text-danger px-2"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fa-2x mb-2 opacity-25"></i>
                            <div>Belum ada produk terdaftar</div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Produk Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Cth: Cappuccino" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" min="0" placeholder="25000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" min="0" placeholder="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text" style="font-size:.75rem;">Format: JPG, PNG. Max 2MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
