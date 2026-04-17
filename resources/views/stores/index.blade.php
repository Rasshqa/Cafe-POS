@extends('layouts.master', ['title' => 'Manajemen Cabang Toko'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Daftar Cabang</h5>
    <a href="{{ route('stores.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Cabang</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nama Toko</th>
                        <th>No Telp</th>
                        <th>Alamat</th>
                        <th>Pajak Dflt</th>
                        <th>Tgl Dibuat</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $st)
                    <tr>
                        <td class="ps-4">#{{ $st->id }}</td>
                        <td class="fw-semibold">
                            @if($st->logo)
                                <img src="{{ asset('storage/'.$st->logo) }}" alt="logo" class="rounded me-2" style="width:30px;height:30px;object-fit:cover;">
                            @endif
                            {{ $st->name }}
                        </td>
                        <td>{{ $st->phone ?? '-' }}</td>
                        <td><span class="text-truncate d-inline-block" style="max-width:200px;">{{ $st->address ?? '-' }}</span></td>
                        <td>{{ $st->default_tax }}%</td>
                        <td>{{ $st->created_at->format('d M Y') }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('stores.edit', $st->id) }}" class="btn btn-sm btn-ghost me-1"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('stores.destroy', $st->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus cabang ini beserta seluruh data di dalamnya? PERINGATAN: Berbahaya!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada cabang tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
