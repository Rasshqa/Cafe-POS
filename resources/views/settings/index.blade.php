@extends('layouts.master', ['title' => 'Pengaturan Toko'])

@section('content')
<div class="mb-4 animate-in">
    <h5 class="fw-bold mb-1">Pengaturan Toko</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Konfigurasi informasi bisnis Anda</p>
</div>

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card animate-in" style="animation-delay:.1s;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-store me-2" style="color:#6366f1;"></i>Informasi Toko</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Toko / Brand <span class="text-danger">*</span></label>
                            <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="store_phone" class="form-control" value="{{ $settings['store_phone'] }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pajak Default (%)</label>
                            <div class="input-group">
                                <input type="number" name="default_tax" class="form-control" value="{{ $settings['default_tax'] }}" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text" style="font-size:.72rem;">Nilai pajak otomatis pada halaman kasir</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Toko</label>
                            <textarea name="store_address" class="form-control" rows="3" placeholder="Jl. Contoh No. 1, Kota">{{ $settings['store_address'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card animate-in" style="animation-delay:.2s;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-image me-2" style="color:#f59e0b;"></i>Logo Toko</h6>
                    @if($settings['store_logo'])
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/'.$settings['store_logo']) }}" class="rounded-3 border" style="max-height:120px;max-width:100%;object-fit:contain;">
                        </div>
                    @else
                        <div class="text-center mb-3 py-4 rounded-3" style="background:#f8fafc;">
                            <i class="fa-solid fa-image fa-3x" style="color:#e2e8f0;"></i>
                            <div class="text-muted mt-2" style="font-size:.82rem;">Belum ada logo</div>
                        </div>
                    @endif
                    <input type="file" name="store_logo" class="form-control" accept="image/*">
                    <div class="form-text" style="font-size:.72rem;">JPG, PNG, WebP. Max 1MB. Tampil di struk.</div>
                </div>
            </div>

            <div class="card animate-in mt-4" style="animation-delay:.3s;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-shield-check me-2 text-success"></i>Info Sistem</h6>
                    <table class="table table-borderless mb-0" style="font-size:.82rem;">
                        <tr><td class="text-muted" width="110">Versi</td><td class="fw-semibold">2.0.0</td></tr>
                        <tr><td class="text-muted">Laravel</td><td class="fw-semibold">{{ app()->version() }}</td></tr>
                        <tr><td class="text-muted">PHP</td><td class="fw-semibold">{{ PHP_VERSION }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary px-5 py-2 mt-4 animate-in" style="animation-delay:.35s;">
        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Pengaturan
    </button>
</form>
@endsection
