@extends('layouts.app')

@section('title', 'PSB Online - SD Negeri Nusantara | Pendaftaran Siswa Baru 2026/2027')

@section('styles')
<style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        background: var(--gradient-hero);
        min-height: 90vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    } 

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 900;
        color: white;
        line-height: 1.1;
        letter-spacing: -0.03em;
        margin-bottom: 1.5rem;
    }

    .hero-section .lead {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.7;
        max-width: 500px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(79, 70, 229, 0.2);
        color: var(--primary-light);
        border: 1px solid rgba(79, 70, 229, 0.3);
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .hero-badge .dot {
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .hero-illustration {
        position: relative;
        z-index: 2;
    }

    .hero-card-floating {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-xl);
        padding: 2rem;
        color: white;
    }

    .hero-stat-item {
        text-align: center;
        padding: 1rem;
    }

    .hero-stat-item .number {
        font-size: 2.5rem;
        font-weight: 900;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .hero-stat-item .label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 0.3rem;
        font-weight: 500;
    }

    /* ===== INFO SECTION ===== */
    .info-section {
        padding: 5rem 0;
        position: relative;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: var(--gray-500);
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ===== FEATURE CARDS ===== */
    .feature-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .feature-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }

    .feature-card:hover::after {
        transform: scaleX(1);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .feature-card h5 {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .feature-card p {
        color: var(--gray-500);
        font-size: 0.9rem;
        line-height: 1.6;
        margin: 0;
    }

    /* ===== TIMELINE ===== */
    .timeline-section {
        background: var(--gray-100);
        padding: 5rem 0;
    }

    .timeline-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-dot {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        color: white;
        background: var(--gradient-primary);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        z-index: 1;
    }

    .timeline-content {
        background: white;
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        flex: 1;
        border: 1px solid var(--gray-200);
    }

    .timeline-content h6 {
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .timeline-content p {
        color: var(--gray-500);
        margin: 0;
        font-size: 0.9rem;
    }

    .timeline-content .date {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* ===== CTA SECTION ===== */
    .cta-section {
        background: var(--gradient-hero);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cta-section h2 {
        color: white;
        font-weight: 800;
        font-size: 2.2rem;
        letter-spacing: -0.02em;
    }

    .cta-section p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.05rem;
    }

    .btn-cta {
        background: white;
        color: var(--primary);
        padding: 0.85rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        color: var(--primary-dark);
    }

    /* ===== REQUIREMENTS SECTION ===== */
    .requirements-section {
        padding: 5rem 0;
    }

    .req-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 1rem;
        border-radius: var(--radius);
        transition: background 0.3s ease;
    }

    .req-item:hover {
        background: var(--gray-100);
    }

    .req-item .check-icon {
        width: 28px;
        height: 28px;
        min-width: 28px;
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .req-item span {
        font-size: 0.95rem;
        color: var(--gray-600);
    }
</style>
@endsection

@section('content')
    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        Pendaftaran Dibuka — Tahun Ajaran 2026/2027
                    </div>

                    <h1>Wujudkan Masa Depan <span class="text-gradient">Cerah</span> Anak Anda</h1>

                    <p class="lead mb-4">
                        Daftarkan putra/putri Anda di SD Negeri Nusantara melalui sistem pendaftaran online yang mudah, cepat, dan transparan.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('pendaftaran.create') }}" class="btn-primary-psb" id="btn-hero-daftar">
                            <i class="bi bi-pencil-square"></i> Daftar Sekarang
                        </a>
                        <a href="{{ route('pendaftaran.cek-status') }}" class="btn-outline-psb" style="border-color: rgba(255,255,255,0.3); color: white;" id="btn-hero-cek">
                            <i class="bi bi-search"></i> Cek Status
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0 hero-illustration" data-aos="fade-left" data-aos-delay="200">
                    <div class="hero-card-floating animate-float">
                        <div class="text-center mb-3">
                            <i class="bi bi-mortarboard-fill" style="font-size: 3rem; color: var(--primary-light);"></i>
                            <h4 class="mt-2 fw-bold">SD Negeri Nusantara</h4>
                            <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">Statistik Pendaftaran T.A. 2026/2027</p>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="hero-stat-item">
                                    <div class="number">{{ $statistik->total_pendaftar ?? 0 }}</div>
                                    <div class="label">Total Pendaftar</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="hero-stat-item">
                                    <div class="number">{{ $statistik->total_diterima ?? 0 }}</div>
                                    <div class="label">Diterima</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="hero-stat-item">
                                    <div class="number">{{ $statistik->total_menunggu ?? 0 }}</div>
                                    <div class="label">Menunggu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section class="info-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Kenapa Memilih <span class="text-gradient">SD Nusantara</span>?</h2>
                <p class="section-subtitle">Komitmen kami untuk memberikan pendidikan terbaik bagi putra-putri bangsa</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5>Kurikulum Merdeka</h5>
                        <p>Menerapkan kurikulum terbaru yang berfokus pada pengembangan karakter dan kompetensi siswa.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5>Guru Berkualitas</h5>
                        <p>Tenaga pendidik profesional dan berpengalaman dalam membimbing siswa meraih prestasi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5>Fasilitas Lengkap</h5>
                        <p>Ruang kelas ber-AC, laboratorium komputer, perpustakaan, dan lapangan olahraga.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: rgba(6, 182, 212, 0.1); color: var(--info);">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <h5>Prestasi Gemilang</h5>
                        <p>Berbagai prestasi akademik dan non-akademik di tingkat kota hingga nasional.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TIMELINE / ALUR PENDAFTARAN --}}
    <section class="timeline-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Alur <span class="text-gradient">Pendaftaran</span></h2>
                <p class="section-subtitle">Ikuti langkah-langkah berikut untuk mendaftarkan putra/putri Anda</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="timeline-dot">1</div>
                        <div class="timeline-content">
                            <h6>Isi Formulir Online</h6>
                            <p>Lengkapi data calon siswa dan data orang tua/wali pada formulir pendaftaran online.</p>
                            <span class="date">1 November - 30 November 2026</span>
                        </div>
                    </div>

                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="timeline-dot">2</div>
                        <div class="timeline-content">
                            <h6>Upload Dokumen</h6>
                            <p>Unggah dokumen persyaratan: foto siswa, akta kelahiran, kartu keluarga, dan ijazah TK.</p>
                            <span class="date">Bersamaan dengan pengisian formulir</span>
                        </div>
                    </div>

                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="timeline-dot">3</div>
                        <div class="timeline-content">
                            <h6>Verifikasi Data</h6>
                            <p>Tim panitia PSB akan memverifikasi kelengkapan dan keabsahan data yang Anda kirim.</p>
                            <span class="date">1 - 7 Desember 2026</span>
                        </div>
                    </div>

                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="timeline-dot">4</div>
                        <div class="timeline-content">
                            <h6>Pengumuman Hasil</h6>
                            <p>Hasil seleksi akan diumumkan melalui halaman pengumuman di website ini.</p>
                            <span class="date">15 Desember 2026</span>
                        </div>
                    </div>

                    <div class="timeline-item" data-aos="fade-up" data-aos-delay="500">
                        <div class="timeline-dot">5</div>
                        <div class="timeline-content">
                            <h6>Daftar Ulang</h6>
                            <p>Calon siswa yang diterima wajib melakukan daftar ulang ke sekolah dengan membawa dokumen asli.</p>
                            <span class="date">16 - 20 Desember 2026</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- REQUIREMENTS SECTION --}}
    <section class="requirements-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="section-title">Persyaratan <span class="text-gradient">Pendaftaran</span></h2>
                    <p class="section-subtitle mb-0">Pastikan Anda telah menyiapkan dokumen berikut sebelum mendaftar</p>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Pas foto 3x4 (latar merah)</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Fotokopi Akta Kelahiran</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Fotokopi Kartu Keluarga (KK)</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Ijazah TK / Surat Keterangan</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Usia minimal 6 tahun (per Juli 2027)</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>NIK / KTP Orang Tua</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Nomor NISN (jika ada)</span>
                            </div>
                            <div class="req-item">
                                <div class="check-icon"><i class="bi bi-check2"></i></div>
                                <span>Nomor HP aktif orang tua</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="cta-section">
        <div class="container text-center position-relative" data-aos="zoom-in">
            <h2 class="mb-3">Jangan Lewatkan Kesempatan Ini!</h2>
            <p class="mb-4" style="max-width: 500px; margin: 0 auto;">
                Pendaftaran akan ditutup pada 30 November 2026. Segera daftarkan putra/putri Anda sekarang.
            </p>
            <a href="{{ route('pendaftaran.create') }}" class="btn-cta" id="btn-cta-daftar">
                <i class="bi bi-pencil-square"></i> Daftar Sekarang
            </a>
        </div>
    </section>
@endsection
