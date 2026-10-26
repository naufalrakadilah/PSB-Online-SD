@extends('layouts.admin')

@section('title', 'Data Pendaftar - PSB Online SD')
@section('page-title', 'Kelola Calon Peserta Didik')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    {{-- STATUS TABS & SEARCH HEADER --}}
    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            {{-- Filter Pills --}}
            <div class="btn-group" role="group">
                <a href="{{ route('admin.pendaftar') }}" 
                   class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Semua
                </a>
                <a href="{{ route('admin.pendaftar', ['status' => 'menunggu']) }}" 
                   class="btn btn-sm {{ request('status') === 'menunggu' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                    Menunggu
                </a>
                <a href="{{ route('admin.pendaftar', ['status' => 'diverifikasi']) }}" 
                   class="btn btn-sm {{ request('status') === 'diverifikasi' ? 'btn-info text-white' : 'btn-outline-secondary' }}">
                    Diverifikasi
                </a>
                <a href="{{ route('admin.pendaftar', ['status' => 'diterima']) }}" 
                   class="btn btn-sm {{ request('status') === 'diterima' ? 'btn-success' : 'btn-outline-secondary' }}">
                    Diterima
                </a>
                <a href="{{ route('admin.pendaftar', ['status' => 'ditolak']) }}" 
                   class="btn btn-sm {{ request('status') === 'ditolak' ? 'btn-danger' : 'btn-outline-secondary' }}">
                    Ditolak
                </a>
            </div>

            {{-- Search Input --}}
            <form action="{{ route('admin.pendaftar') }}" method="GET" class="d-flex gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group input-group-sm">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari Nama / No. Pendaftaran..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.pendaftar', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- APPLICANTS TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3 text-center" style="width: 50px;">No</th>
                    <th>No. Pendaftaran</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>L/P</th>
                    <th>No. HP Ortu</th>
                    <th>Waktu Daftar</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftar as $index => $item)
                    <tr>
                        <td class="ps-3 text-center text-muted fw-semibold">
                            {{ $pendaftar->firstItem() + $index }}
                        </td>
                        <td>
                            <span class="badge bg-dark-subtle text-dark border px-2 py-1 fw-bold">
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                            <small class="text-muted">{{ $item->asal_sekolah ?? 'Tanpa TK' }}</small>
                        </td>
                        <td><code>{{ $item->nik }}</code></td>
                        <td>
                            @if($item->jenis_kelamin === 'Laki-laki')
                                <span class="badge bg-primary-subtle text-primary">L</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">P</span>
                            @endif
                        </td>
                        <td>{{ $item->no_hp_ayah ?: ($item->no_hp_ibu ?: '-') }}</td>
                        <td class="small text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @if($item->status === 'menunggu')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                </span>
                            @elseif($item->status === 'diverifikasi')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    <i class="bi bi-patch-check me-1"></i> Diverifikasi
                                </span>
                            @elseif($item->status === 'diterima')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Diterima
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.detail', $item->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Verifikasi & Detail">
                                    <i class="bi bi-pencil-square me-1"></i> Periksa
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $item->id }}" 
                                        title="Hapus Data">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            {{-- MODAL HAPUS --}}
                            <div class="modal fade text-start" id="deleteModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            <p class="mb-0">
                                                Apakah Anda yakin ingin menghapus data calon siswa <strong>{{ $item->nama_lengkap }}</strong> (<code>{{ $item->no_pendaftaran }}</code>)?
                                                Semua berkas dokumen yang terupload juga akan dihapus permanen.
                                            </p>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Tidak ada data pendaftar ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($pendaftar->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted">
                Menampilkan {{ $pendaftar->firstItem() }} - {{ $pendaftar->lastItem() }} dari total {{ $pendaftar->total() }} data
            </small>
            <div>
                {{ $pendaftar->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
