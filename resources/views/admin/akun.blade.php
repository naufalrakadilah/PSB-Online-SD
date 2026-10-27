@extends('layouts.admin')

@section('title', 'Akun Admin - PSB Online SD')
@section('page-title', 'Kelola Akun Admin')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-hourglass-split text-warning me-2"></i>Menunggu Verifikasi
        </h6>
        <span class="badge bg-warning-subtle text-warning border">{{ $akunMenunggu->count() }} akun</span>
    </div>

    @if($akunMenunggu->isEmpty())
        <div class="p-4 text-center text-muted small">Tidak ada akun yang menunggu verifikasi.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akunMenunggu as $akun)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $akun->name }}</td>
                            <td>{{ $akun->email }}</td>
                            <td class="small text-muted">{{ $akun->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('admin.akun.verify', $akun) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg me-1"></i> Verifikasi
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.akun.destroy', $akun) }}" method="POST"
                                          data-confirm="Tolak pendaftaran akun {{ $akun->name }}?" onsubmit="return confirm(this.dataset.confirm)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg me-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-shield-check text-success me-2"></i>Admin Aktif
        </h6>
        <span class="badge bg-success-subtle text-success border">{{ $akunAktif->count() }} akun</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase text-muted">
                <tr>
                    <th class="ps-3">Nama</th>
                    <th>Email</th>
                    <th>Diverifikasi</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($akunAktif as $akun)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            {{ $akun->name }}
                            @if($akun->is(auth()->user()))
                                <span class="badge bg-primary-subtle text-primary border ms-1">Anda</span>
                            @endif
                        </td>
                        <td>{{ $akun->email }}</td>
                        <td class="small text-muted">
                            {{ $akun->verified_at->format('d/m/Y H:i') }}
                            @if($akun->verifier)
                                oleh {{ $akun->verifier->name }}
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            @if($akunAktif->count() > 1)
                                <form action="{{ route('admin.akun.destroy', $akun) }}" method="POST"
                                      data-confirm="{{ $akun->is(auth()->user()) ? 'Hapus akun Anda sendiri? Anda akan langsung keluar.' : 'Hapus akun admin '.$akun->name.'?' }}"
                                      onsubmit="return confirm(this.dataset.confirm)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="small text-muted" title="Minimal harus ada 1 admin aktif">Admin terakhir</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
