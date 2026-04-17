@extends('layouts.master', ['title' => 'Manajemen Produk'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold text-secondary mb-0">Daftar Menu Tersedia</h5>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus me-1"></i> Tambah Produk
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="80" class="text-center">Gambar</th>
                        <th>Menu / Barang</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-center py-2">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="Gambar" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light text-secondary d-flex justify-content-center align-items-center rounded border" style="width: 50px; height: 50px; margin: 0 auto;">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td><span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary fw-normal">{{ $product->category->name ?? '-' }}</span></td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="text-success fw-bold">{{ $product->stock }}</span>
                            @else
                                <span class="text-danger fw-bold"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini secara permanen?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa-solid fa-box-open fa-3x mb-3 text-light"></i><br>
                            Belum ada menu produk terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <!-- MENGGUNAKAN multipart/form-data -->
      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-light">
          <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle text-primary me-2"></i> Tambah Produk Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Pilih Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Cth: Nasi Goreng Spesial" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" required min="0" placeholder="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control" required min="0" placeholder="0">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Produk (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
            </div>
        </div>
        <div class="modal-footer bg-light px-4">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
