@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Manajemen Kategori</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Kategori</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Nama Kategori</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td class="text-center">
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Belum ada kategori.</td>
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
      <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kategori Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Kopi, Snack, dll" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Kategori</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
