<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PSB Online - Penerimaan Siswa Baru SD Negeri Nusantara. Daftar sekarang untuk tahun ajaran 2026/2027.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PSB Online SD')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- AOS Animation --}}
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4F46E5;
            --primary-light: #818CF8;
            --primary-dark: #3730A3;
            --secondary: #0EA5E9;
            --accent: #F59E0B;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #06B6D4;
            --dark: #1E293B;
            --darker: #0F172A;
            --light: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --gradient-primary: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%);
            --gradient-hero: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            --gradient-card: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.05) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar-psb {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }

        .navbar-psb.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-lg);
        }

        .navbar-brand-psb {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand-psb .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .navbar-brand-psb .brand-text {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .navbar-brand-psb .brand-sub {
            color: var(--gray-400);
            font-size: 0.7rem;
            font-weight: 400;
            display: block;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .nav-link-psb {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-psb:hover,
        .nav-link-psb.active {
            color: white !important;
            background: rgba(79, 70, 229, 0.2);
        }

        .btn-daftar-nav {
            background: var(--gradient-primary);
            color: white !important;
            border: none;
            padding: 0.5rem 1.5rem !important;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-daftar-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }

        /* ===== BUTTONS ===== */
        .btn-primary-psb {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-psb:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
            color: white;
        }

        .btn-outline-psb {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-psb:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* ===== CARDS ===== */
        .card-psb {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-psb:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .card-psb .card-body {
            padding: 1.5rem;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.primary::before { background: var(--gradient-primary); }
        .stat-card.success::before { background: var(--success); }
        .stat-card.warning::before { background: var(--warning); }
        .stat-card.info::before { background: var(--info); }
        .stat-card.danger::before { background: var(--danger); }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .stat-card .stat-label {
            color: var(--gray-500);
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ===== FORM STYLES ===== */
        .form-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            border: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .form-label-psb {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .form-control-psb {
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--gray-100);
        }

        .form-control-psb:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .form-control-psb.is-invalid {
            border-color: var(--danger);
        }

        /* ===== FOOTER ===== */
        .footer-psb {
            background: var(--darker);
            color: rgba(255, 255, 255, 0.7);
            padding: 3rem 0 1.5rem;
        }

        .footer-psb .footer-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .footer-psb a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-psb a:hover {
            color: var(--primary-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }

        /* ===== BADGE STYLES ===== */
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #B45309; }
        .badge-info { background: rgba(6, 182, 212, 0.15); color: #0E7490; }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #047857; }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #B91C1C; }

        /* ===== TABLE STYLES ===== */
        .table-psb {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-psb thead th {
            background: var(--gray-100);
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border: none;
        }

        .table-psb tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table-psb tbody tr:hover {
            background: rgba(79, 70, 229, 0.03);
        }

        /* ===== ALERT STYLES ===== */
        .alert-psb {
            border: none;
            border-radius: var(--radius);
            padding: 1rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-psb.success {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
        }

        .alert-psb.error {
            background: rgba(239, 68, 68, 0.1);
            color: #B91C1C;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .stat-card .stat-number {
                font-size: 1.5rem;
            }

            .form-section {
                padding: 1.25rem;
            }

            .hero-section h1 {
                font-size: 2rem !important;
            }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* ===== MISC ===== */
        .page-header {
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.02em;
        }

        .page-header p {
            color: var(--gray-500);
            font-size: 1rem;
        }

        .divider {
            height: 1px;
            background: var(--gray-200);
            margin: 1.5rem 0;
        }

        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    @yield('styles')
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-psb sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand-psb" href="{{ route('home') }}">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <span class="brand-text">PSB Online</span>
                    <span class="brand-sub">SD Negeri Nusantara</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-psb {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-psb {{ request()->routeIs('pendaftaran.cek-status') ? 'active' : '' }}" href="{{ route('pendaftaran.cek-status') }}">
                            <i class="bi bi-search me-1"></i> Cek Status
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-psb {{ request()->routeIs('pengumuman') ? 'active' : '' }}" href="{{ route('pengumuman') }}">
                            <i class="bi bi-megaphone me-1"></i> Pengumuman
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-daftar-nav {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}" href="{{ route('pendaftaran.create') }}">
                            <i class="bi bi-pencil-square me-1"></i> Daftar Sekarang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert-psb success animate-fade-in" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert-psb error animate-fade-in" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-psb">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-brand">
                        <i class="bi bi-mortarboard-fill me-2"></i>PSB Online SD
                    </div>
                    <p class="mb-3" style="font-size: 0.9rem;">
                        Sistem Penerimaan Siswa Baru Online<br>
                        SD Negeri Nusantara
                    </p>
                    <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4);">
                        Tahun Ajaran 2026/2027
                    </p>
                </div>
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <h6 class="text-white fw-bold mb-3">Menu</h6>
                    <ul class="list-unstyled" style="font-size: 0.9rem;">
                        <li class="mb-2"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('pendaftaran.create') }}">Pendaftaran</a></li>
                        <li class="mb-2"><a href="{{ route('pendaftaran.cek-status') }}">Cek Status</a></li>
                        <li class="mb-2"><a href="{{ route('pengumuman') }}">Pengumuman</a></li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <h6 class="text-white fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled" style="font-size: 0.9rem;">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Jl. Pendidikan No. 1, Kota Nusantara</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>(021) 1234-5678</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>info@sdnusantara.sch.id</li>
                        <li class="mb-2"><i class="bi bi-clock me-2"></i>Senin - Jumat: 07:00 - 15:00 WIB</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p style="font-size: 0.8rem; margin: 0;">
                    &copy; {{ date('Y') }} PSB Online SD Negeri Nusantara. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- AOS Animation --}}
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-psb');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
