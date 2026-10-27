<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - PSB Online SD')</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --secondary: #0EA5E9;
            --dark: #0F172A;
            --dark-card: #1E293B;
            --light-bg: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--dark);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            padding: 1.5rem 1rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 0.75rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            color: white;
        }

        .admin-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
        }

        .nav-admin-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .nav-admin-link:hover {
            color: white;
            background: rgba(255,255,255,0.06);
        }

        .nav-admin-link.active {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .nav-admin-link i {
            font-size: 1.15rem;
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: white;
            height: 70px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        .badge-competency {
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
            font-size: 0.72rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-family: monospace;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <div class="admin-brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="fw-bold fs-6">PSB Admin</div>
                    <div class="text-white-50" style="font-size: 0.75rem;">SD Negeri Nusantara</div>
                </div>
            </a>

            <div class="mt-4">
                <div class="text-uppercase text-white-50 small fw-bold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Menu Utama</div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.pendaftar') }}" class="nav-admin-link {{ request()->routeIs('admin.pendaftar*') || request()->routeIs('admin.detail') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Pendaftar</span>
                </a>

                <div class="text-uppercase text-white-50 small fw-bold px-3 mt-4 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Aplikasi Publik</div>

                <a href="{{ route('home') }}" target="_blank" class="nav-admin-link">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Lihat Web Utama</span>
                </a>

                <a href="{{ route('pengumuman') }}" target="_blank" class="nav-admin-link">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>Cek Pengumuman</span>
                </a>
            </div>
        </div>

        {{-- USER & LOGOUT --}}
        <div class="pt-3 border-top border-secondary-subtle">
            <div class="d-flex align-items-center justify-content-between px-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem;">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-semibold text-white small">{{ auth()->user()->name ?? 'Administrator' }}</div>
                        <div class="text-white-50" style="font-size: 0.7rem;">Admin PSB</div>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 text-white-50 hover-text-white" title="Keluar">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>
 
    {{-- MAIN CONTENT WRAPPER --}}
    <div class="admin-main">
        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill small">
                    <i class="bi bi-check-circle-fill me-1"></i> Database: SQLite (Offline Mode)
                </span>
                <span class="text-muted small d-none d-md-inline">
                    <i class="bi bi-clock me-1"></i> {{ now()->translatedFormat('l, d M Y') }}
                </span>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="admin-content">
            {{-- FLASH ALERTS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
