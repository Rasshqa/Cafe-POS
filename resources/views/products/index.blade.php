@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manajemen Produk</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Produk</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Menu/Barang</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="100" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="fw-bold">{{ $product->name }}</td>
                    <td><span class="badge bg-secondary">{{ $product->category->name ?? '-' }}</span></td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td class="text-center">
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada menu produk terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Produk Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Pilih Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Nama Menu</label>
                <input type="text" name="name" class="form-control" placeholder="Cth: Caffe Latte" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" required min="0" placeholder="15000">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stock" class="form-control" required min="0" placeholder="50">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
