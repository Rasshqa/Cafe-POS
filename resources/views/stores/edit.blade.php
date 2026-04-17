@extends('layouts.master', ['title' => 'Edit Cabang'])

@section('content')
<div class="mb-4">
    <a href="{{ route('stores.index') }}" class="btn btn-ghost btn-sm mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    <h5 class="fw-bold mb-0">Edit Cabang: {{ $store->name }}</h5>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="{{ route('stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama Cabang / Toko <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $store->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Telepon Cabang</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $store->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pajak Default (%)</label>
                    <div class="input-group">
                        <input type="number" name="default_tax" class="form-control" value="{{ old('default_tax', $store->default_tax) }}" min="0" max="100" step="0.1">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $store->address) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Logo Cabang Khusus (Opsional)</label>
                @if($store->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$store->logo) }}" alt="Logo" class="rounded border" style="max-height:80px">
                    </div>
                @endif
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                <div class="form-text">Biarkan kosong jika tidak ingin mengubah logo saat ini. Max 1MB (JPG/PNG).</div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-2"></i> Perbarui Cabang</button>
        </form>
    </div>
</div>
@endsection
