@extends('layouts.app')

@section('title', 'Pengumuman Hasil Seleksi - PSB Online SD')

@section('styles')
<style>
    .pengumuman-hero {
        background: var(--gradient-hero);
        padding: 4.5rem 0 3.5rem;
        color: white;
        text-align: center;
    }

    .stat-card-clean {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s ease;
    }

    .stat-card-clean:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .table-custom-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .table-psb th {
        background: #F8FAFC;
        font-weight: 700;
        font-size: 0.85rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--gray-200);
        padding: 1rem 1.25rem;
    }

    .table-psb td {
        padding: 1.1rem 1.25rem;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .badge-status {
        padding: 0.45rem 0.9rem;
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 50rem;
    }
</style>
@endsection

@section('content')
{{-- HERO SECTION --}}
<section class="pengumuman-hero">
    <div class="container">
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 fw-bold text-uppercase">
            <i class="bi bi-megaphone me-1"></i> Tahun Ajaran 2026/2027
        </span>
        <h1 class="fw-bold mb-3 display-5">Pengumuman Hasil Seleksi</h1>
        <p class="text-white-50 lead mx-auto" style="max-width: 650px;">
            Daftar resmi calon peserta didik baru SD Negeri Nusantara yang telah melalui proses seleksi berkas dan kriteria usia.
        </p>
    </div>
</section>

<div class="container py-5">
    {{-- STATS ROW --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-clean">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <span class="text-muted d-block small fw-semibold text-uppercase">Total Diterima</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $pengumumanStats->diterima ?? 0 }}</h3>
                    <small class="text-muted">Siswa Diterima</small>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-clean">
                <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="bi bi-person-x-fill"></i>
                </div>
                <div>
                    <span class="text-muted d-block small fw-semibold text-uppercase">Total Ditolak</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $pengumumanStats->ditolak ?? 0 }}</h3>
                    <small class="text-muted">Tidak Memenuhi Syarat</small>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-clean">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <span class="text-muted d-block small fw-semibold text-uppercase">Total Telah Diseleksi</span>
                    <h3 class="fw-bold mb-0 text-primary">
                        {{ ($pengumumanStats->diterima ?? 0) + ($pengumumanStats->ditolak ?? 0) }}
                    </h3>
                    <small class="text-muted">Pendaftar Diproses</small>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="table-custom-card mb-4" data-aos="fade-up">
        <div class="p-4 border-bottom bg-light">
            <form action="{{ route('pengumuman') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari berdasarkan Nama Siswa atau Nomor Pendaftaran..." 
                               value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('pengumuman') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold w-100 w-md-auto">
                        <i class="bi bi-filter me-1"></i> Cari Pengumuman
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover table-psb mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Asal TK/RA</th>
                        <th class="text-center">Status Hasil</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasil as $index => $item)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                {{ $hasil->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="badge bg-dark-subtle text-dark border px-2 py-1 fw-bold">
                                    {{ $item->no_pendaftaran }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->nama_lengkap }}</span>
                            </td>
                            <td>
                                @if($item->jenis_kelamin === 'Laki-laki')
                                    <span class="text-primary"><i class="bi bi-gender-male me-1"></i>Laki-laki</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-gender-female me-1"></i>Perempuan</span>
                                @endif
                            </td>
                            <td>{{ $item->asal_sekolah ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->status === 'diterima')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle badge-status">
                                        <i class="bi bi-check-circle-fill me-1"></i> DITERIMA
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-status">
                                        <i class="bi bi-x-circle-fill me-1"></i> TIDAK LOLOS
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pendaftaran.cek-status', ['no_pendaftaran' => $item->no_pendaftaran]) }}" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Lihat Detail & Bukti">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    @if(request('search'))
                                        <h5>Tidak ada hasil untuk pencarian "{{ request('search') }}"</h5>
                                        <p class="small">Periksa kembali ejaan nama atau nomor pendaftaran.</p>
                                    @else
                                        <h5>Belum ada pengumuman hasil seleksi yang dirilis.</h5>
                                        <p class="small">Proses seleksi sedang berlangsung oleh panitia penerimaan siswa baru.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($hasil->hasPages())
            <div class="p-4 border-top bg-light d-flex justify-content-between align-items-center flex-wrap">
                <small class="text-muted">
                    Menampilkan {{ $hasil->firstItem() }} sampai {{ $hasil->lastItem() }} dari {{ $hasil->total() }} data
                </small>
                <div>
                    {{ $hasil->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- INFO DAFTAR ULANG --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h5 class="fw-bold mb-2">Informasi Daftar Ulang Bagi Siswa DITERIMA</h5>
                <p class="mb-0 text-white-50">
                    Bagi calon peserta didik yang dinyatakan <strong>DITERIMA</strong>, harap melakukan registrasi ulang di loket TU SD Negeri Nusantara dengan membawa cetak bukti pendaftaran, fotokopi Akta Kelahiran, dan Kartu Keluarga asli.
                </p>
            </div>
            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <a href="{{ route('pendaftaran.cek-status') }}" class="btn btn-light fw-bold px-4 py-2">
                    <i class="bi bi-card-checklist me-1"></i> Cek Status Anda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
