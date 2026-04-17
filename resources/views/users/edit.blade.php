@extends('layouts.master', ['title' => 'Edit Pegawai'])

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    <h5 class="fw-bold mb-0">Edit Akun: {{ $user->name }}</h5>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body p-4">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email (Username Login) <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="4">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="4">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir (Hanya akses POS)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Manajer (Manajemen Produk & Cabang)</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if(auth()->user()->role === 'owner')
            <div class="mb-4">
                <label class="form-label">Penempatan Cabang Toko</label>
                <select name="store_id" class="form-select @error('store_id') is-invalid @enderror">
                    <option value="">Pilih Cabang (Kosongkan jika admin pusat)</option>
                    @foreach($stores as $st)
                        <option value="{{ $st->id }}" {{ old('store_id', $user->store_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
                @error('store_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @endif

            <hr class="my-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-2"></i> Perbarui Data</button>
        </form>
    </div>
</div>
@endsection
