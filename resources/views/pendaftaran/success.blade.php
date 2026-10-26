@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - PSB Online SD')

@section('styles')
<style>
    .success-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        padding: 3rem 0;
    }

    .success-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        padding: 3rem;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid var(--gray-200);
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        color: var(--success);
        animation: pulse 2s infinite;
    }

    .no-pendaftaran-display {
        background: var(--gradient-primary);
        color: white;
        padding: 1rem 2rem;
        border-radius: var(--radius);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        margin: 1.5rem 0;
        display: inline-block;
    }

    .info-list {
        text-align: left;
        background: var(--gray-100);
        border-radius: var(--radius);
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .info-list .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--gray-200);
        font-size: 0.9rem;
    }

    .info-list .info-item:last-child {
        border-bottom: none;
    }

    .info-list .info-label {
        color: var(--gray-500);
        font-weight: 500;
    }

    .info-list .info-value {
        font-weight: 600;
        color: var(--dark);
    }
</style>
@endsection

@section('content')
<section class="success-section">
    <div class="container">
        <div class="success-card" data-aos="zoom-in">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h2 class="fw-bold mb-2">Pendaftaran Berhasil!</h2>
            <p class="text-muted mb-3">Data pendaftaran Anda telah berhasil dikirim. Simpan nomor pendaftaran berikut:</p>

            <div class="no-pendaftaran-display">
                {{ $pendaftaran->no_pendaftaran }}
            </div>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Nama Siswa</span>
                    <span class="info-value">{{ $pendaftaran->nama_lengkap }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Daftar</span>
                    <span class="info-value">{{ $pendaftaran->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="badge-status badge-warning">Menunggu Verifikasi</span>
                </div>
            </div>

            <p class="text-muted" style="font-size: 0.85rem;">
                <i class="bi bi-info-circle me-1"></i>
                Gunakan nomor pendaftaran ini untuk mengecek status pendaftaran Anda.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="{{ route('pendaftaran.cetak', $pendaftaran->no_pendaftaran) }}" class="btn-primary-psb" target="_blank" id="btn-cetak-bukti">
                    <i class="bi bi-printer"></i> Cetak Bukti
                </a>
                <a href="{{ route('pendaftaran.cek-status') }}?no_pendaftaran={{ $pendaftaran->no_pendaftaran }}" class="btn-outline-psb" id="btn-cek-status">
                    <i class="bi bi-search"></i> Cek Status
                </a>
                <a href="{{ route('home') }}" class="btn-outline-psb" id="btn-kembali-home">
                    <i class="bi bi-house-door"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
