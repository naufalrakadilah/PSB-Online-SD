@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - PSB Online SD')

@section('styles')
<style>
    .status-hero {
        background: var(--gradient-hero);
        padding: 4rem 0 3rem;
        color: white;
        text-align: center;
        position: relative;
    }

    .status-search-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        padding: 2.5rem;
        border: 1px solid var(--gray-200);
        margin-top: -2rem;
        position: relative;
        z-index: 10;
    }

    .status-result-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        margin-top: 2rem;
    }

    .result-header {
        background: var(--gradient-hero);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0;
    }

    .timeline-steps::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 40px;
        right: 40px;
        height: 3px;
        background: var(--gray-200);
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .timeline-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--gray-300);
        color: var(--gray-400);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .timeline-step.completed .timeline-icon {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .timeline-step.active .timeline-icon {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 0 0 6px rgba(79, 70, 229, 0.2);
    }

    .timeline-step.rejected .timeline-icon {
        background: var(--danger);
        border-color: var(--danger);
        color: white;
    }

    .timeline-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .timeline-desc {
        font-size: 0.75rem;
        color: var(--gray-500);
    }

    .badge-status-lg {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 50rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .badge-menunggu {
        background: #FEF3C7;
        color: #D97706;
        border: 1px solid #FCD34D;
    }

    .badge-diverifikasi {
        background: #E0F2FE;
        color: #0284C7;
        border: 1px solid #BAE6FD;
    }

    .badge-diterima {
        background: #D1FAE5;
        color: #059669;
        border: 1px solid #A7F3D0;
    }

    .badge-ditolak {
        background: #FEE2E2;
        color: #DC2626;
        border: 1px solid #FECACA;
    }
</style>
@endsection

@section('content')
<section class="status-hero">
    <div class="container">
        <h1 class="fw-bold mb-2">Cek Status Pendaftaran</h1>
        <p class="text-white-50 mb-0">Pantau perkembangan seleksi penerimaan siswa baru secara berkala</p>
    </div>
</section>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="status-search-card" data-aos="fade-up">
                <form action="{{ route('pendaftaran.cek-status') }}" method="GET">
                    <label for="no_pendaftaran" class="form-label fw-bold mb-2">
                        <i class="bi bi-search me-1 text-primary"></i> Masukkan Nomor Pendaftaran
                    </label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-card-heading text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 border-end-0" 
                               id="no_pendaftaran" 
                               name="no_pendaftaran" 
                               value="{{ request('no_pendaftaran') }}" 
                               placeholder="Contoh: PSB-2026-0001" 
                               required 
                               autocomplete="off">
                        <button class="btn btn-primary px-4 fw-semibold" type="submit" id="btn-cari-status">
                            <i class="bi bi-search me-1"></i> Cari Status
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i> Nomor pendaftaran tertera pada bukti pendaftaran yang telah diunduh/dicetak saat mendaftar.
                    </small>
                </form>
            </div>

            @if(request()->filled('no_pendaftaran'))
                @if($pendaftaran)
                    <div class="status-result-card animate-fade-in" data-aos="fade-up">
                        <div class="result-header">
                            <div>
                                <span class="badge bg-white text-dark mb-1 fw-bold">{{ $pendaftaran->no_pendaftaran }}</span>
                                <h4 class="mb-0 fw-bold">{{ $pendaftaran->nama_lengkap }}</h4>
                            </div>
                            <div>
                                @if($pendaftaran->status === 'menunggu')
                                    <span class="badge-status-lg badge-menunggu">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($pendaftaran->status === 'diverifikasi')
                                    <span class="badge-status-lg badge-diverifikasi">
                                        <i class="bi bi-patch-check me-1"></i> Berkas Diverifikasi
                                    </span>
                                @elseif($pendaftaran->status === 'diterima')
                                    <span class="badge-status-lg badge-diterima">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selamat, Diterima!
                                    </span>
                                @elseif($pendaftaran->status === 'ditolak')
                                    <span class="badge-status-lg badge-ditolak">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Lolos Seleksi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 p-md-5">
                            <div class="timeline-steps">
                                <div class="timeline-step completed">
                                    <div class="timeline-icon">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <div class="timeline-title">Pendaftaran</div>
                                    <div class="timeline-desc">{{ $pendaftaran->created_at->format('d M Y') }}</div>
                                </div>

                                <div class="timeline-step {{ in_array($pendaftaran->status, ['diverifikasi', 'diterima', 'ditolak']) ? 'completed' : 'active' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="timeline-title">Verifikasi Berkas</div>
                                    <div class="timeline-desc">
                                        {{ $pendaftaran->status === 'menunggu' ? 'Dalam Proses' : 'Selesai Diperiksa' }}
                                    </div>
                                </div>

                                <div class="timeline-step {{ $pendaftaran->status === 'diterima' ? 'completed' : ($pendaftaran->status === 'ditolak' ? 'rejected' : '') }}">
                                    <div class="timeline-icon">
                                        @if($pendaftaran->status === 'diterima')
                                            <i class="bi bi-trophy"></i>
                                        @elseif($pendaftaran->status === 'ditolak')
                                            <i class="bi bi-x-lg"></i>
                                        @else
                                            <i class="bi bi-megaphone"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-title">Hasil Seleksi</div>
                                    <div class="timeline-desc">
                                        @if($pendaftaran->status === 'diterima')
                                            Diterima
                                        @elseif($pendaftaran->status === 'ditolak')
                                            Ditolak
                                        @else
                                            Menunggu Jadwal
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($pendaftaran->status === 'diterima')
                                <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-award-fill text-success fs-1 me-3"></i>
                                        <div>
                                            <h5 class="fw-bold mb-1">Selamat! Calon Peserta Didik Dinyatakan DITERIMA</h5>
                                            <p class="mb-0 text-secondary">
                                                Silakan segera melakukan daftar ulang di bagian tata usaha SD Negeri Nusantara sesuai jadwal yang ditentukan dengan membawa berkas asli.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($pendaftaran->status === 'ditolak')
                                <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-octagon-fill text-danger fs-1 me-3"></i>
                                        <div>
                                            <h5 class="fw-bold mb-1">Mohon Maaf, Belum Memenuhi Kriteria Seleksi</h5>
                                            <p class="mb-0 text-secondary">
                                                Terima kasih atas partisipasi dan minat Anda. Tetap semangat dalam menggapai pendidikan yang terbaik.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($pendaftaran->catatan_admin)
                                <div class="card border-0 bg-light rounded-4 p-3 mb-4">
                                    <div class="fw-bold text-dark mb-1">
                                        <i class="bi bi-chat-quote-fill text-primary me-2"></i>Catatan dari Panitia:
                                    </div>
                                    <p class="mb-0 text-secondary fst-italic ps-4">{{ $pendaftaran->catatan_admin }}</p>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Nomor Induk Kependudukan (NIK)</small>
                                        <span class="fw-semibold">{{ $pendaftaran->nik }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                                        <span class="fw-semibold">{{ $pendaftaran->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Jenis Kelamin</small>
                                        <span class="fw-semibold">{{ $pendaftaran->jenis_kelamin }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Nama Orang Tua (Ayah / Ibu)</small>
                                        <span class="fw-semibold">{{ $pendaftaran->nama_ayah }} / {{ $pendaftaran->nama_ibu }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Tahun Ajaran</small>
                                        <span class="fw-semibold">{{ $pendaftaran->tahun_ajaran }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block">Asal Sekolah (TK/RA)</small>
                                        <span class="fw-semibold">{{ $pendaftaran->asal_sekolah ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                                </a>
                                <a href="{{ route('pendaftaran.cetak', $pendaftaran->no_pendaftaran) }}" class="btn btn-primary">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Bukti Pendaftaran (PDF)
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center mt-4" data-aos="fade-up">
                        <div class="text-danger mb-3" style="font-size: 3rem;">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <h4 class="fw-bold">Nomor Pendaftaran Tidak Ditemukan</h4>
                        <p class="text-muted mb-4">
                            Nomor pendaftaran <code>{{ request('no_pendaftaran') }}</code> tidak terdaftar di sistem.
                            Pastikan format nomor sudah benar (contoh: <code>PSB-2026-0001</code>).
                        </p>
                        <div>
                            <a href="{{ route('pendaftaran.cek-status') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset Pencarian
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
