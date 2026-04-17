@extends('layouts.master', ['title' => 'Daftar Pegawai'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Manajemen Akses & Pegawai</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Akun</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Cabang Penempatan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'owner')
                                <span class="badge bg-danger">Owner</span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-primary">Manager</span>
                            @else
                                <span class="badge bg-secondary">Kasir</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'owner')
                                <span class="text-muted fst-italic">Global Access</span>
                            @else
                                {{ $user->store->name ?? 'Belum Ada Cabang' }}
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-ghost me-1"><i class="fa-solid fa-pen"></i></a>
                            @if($user->role !== 'owner')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
