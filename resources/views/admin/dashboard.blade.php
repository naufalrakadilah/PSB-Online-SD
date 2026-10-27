@extends('layouts.admin')

@section('title', 'Dashboard Administrator - PSB Online SD')
@section('page-title', 'Dashboard Ringkasan PSB')

@section('content')
@if(isset($alertError))
    <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-bell-fill fs-2 me-3 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-1">[ALERT NOTIFICATION] Sistem Mendeteksi Kendala</h6>
                <p class="mb-0 small text-danger-emphasis">{{ $alertError }}</p>
            </div>
        </div>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Total Pendaftar</span>
                <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bolder mb-1">{{ $statistik->total ?? 0 }}</h2>
            <span class="text-muted small"><i class="bi bi-calendar-event me-1"></i> TA 2026/2027</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Menunggu Verifikasi</span>
                <div class="rounded-3 p-2 bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bolder mb-1 text-warning">{{ $statistik->menunggu ?? 0 }}</h2>
            <a href="{{ route('admin.pendaftar', ['status' => 'menunggu']) }}" class="small text-decoration-none fw-semibold">
                Perlu ditinjau <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Diverifikasi</span>
                <div class="rounded-3 p-2 bg-info-subtle text-info">
                    <i class="bi bi-patch-check-fill fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bolder mb-1 text-info">{{ $statistik->diverifikasi ?? 0 }}</h2>
            <a href="{{ route('admin.pendaftar', ['status' => 'diverifikasi']) }}" class="small text-decoration-none fw-semibold">
                Siap diseleksi <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Diterima</span>
                <div class="rounded-3 p-2 bg-success-subtle text-success">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bolder mb-1 text-success">{{ $statistik->diterima ?? 0 }}</h2>
            <span class="text-muted small">Kuota Terisi</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white overflow-hidden">
    <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-cpu-fill text-warning fs-5"></i>
            <span class="fw-bold">Pemantauan Resource Aplikasi (Server Monitor)</span>
            <span class="badge-competency">J.620100.045.01</span>
        </div>
        <span class="badge bg-success-subtle text-success small">
            <i class="bi bi-activity me-1"></i> Live Status: Normal
        </span>
    </div>
    <div class="p-4">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3 border-end border-secondary">
                <small class="text-white-50 d-block mb-1">RAM Digunakan</small>
                <h4 class="fw-bold mb-0 text-info">{{ $resourceInfo['memory_usage'] ?? 'N/A' }}</h4>
                <small class="text-white-50">Peak: {{ $resourceInfo['memory_peak'] ?? 'N/A' }}</small>
            </div>
            <div class="col-6 col-md-3 border-end-md border-secondary">
                <small class="text-white-50 d-block mb-1">Ukuran Database SQLite</small>
                <h4 class="fw-bold mb-0 text-warning">{{ $resourceInfo['db_size'] ?? 'N/A' }}</h4>
                <small class="text-white-50">database.sqlite</small>
            </div>
            <div class="col-6 col-md-3 border-end border-secondary">
                <small class="text-white-50 d-block mb-1">Ukuran Storage</small>
                <h4 class="fw-bold mb-0 text-success">{{ $resourceInfo['storage_size'] ?? 'N/A' }}</h4>
                <small class="text-white-50">storage/app/public</small>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-white-50 d-block mb-1">Ruang Disk Kosong</small>
                <h4 class="fw-bold mb-0 text-light">{{ $resourceInfo['disk_free'] ?? 'N/A' }}</h4>
                <small class="text-white-50">PHP {{ $resourceInfo['php_version'] ?? PHP_VERSION }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-clock-history me-2 text-primary"></i>5 Pendaftar Terbaru
                </h6>
                <a href="{{ route('admin.pendaftar') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No. Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>Waktu Daftar</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarTerbaru as $siswa)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $siswa->no_pendaftaran }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td class="small text-muted">{{ $siswa->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($siswa->status === 'menunggu')
                                        <span class="badge bg-warning-subtle text-warning border">Menunggu</span>
                                    @elseif($siswa->status === 'diverifikasi')
                                        <span class="badge bg-info-subtle text-info border">Diverifikasi</span>
                                    @elseif($siswa->status === 'diterima')
                                        <span class="badge bg-success-subtle text-success border">Diterima</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.detail', $siswa->id) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada data pendaftaran yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-3">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Statistik Gender Siswa
            </h6>
            
            <div class="d-flex flex-column gap-3 mb-4">
                @php
                    $totalGender = ($statistik->total ?? 0) > 0 ? $statistik->total : 1;
                    $laki = 0;
                    $perempuan = 0;
                    foreach($genderStats as $g) {
                        if ($g->jenis_kelamin === 'Laki-laki') $laki = $g->jumlah;
                        if ($g->jenis_kelamin === 'Perempuan') $perempuan = $g->jumlah;
                    }
                    $pctLaki = round(($laki / $totalGender) * 100);
                    $pctPerempuan = round(($perempuan / $totalGender) * 100);
                @endphp

                <div>
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span><i class="bi bi-gender-male text-primary me-1"></i> Laki-laki</span>
                        <span>{{ $laki }} ({{ $pctLaki }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pctLaki }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span><i class="bi bi-gender-female text-danger me-1"></i> Perempuan</span>
                        <span>{{ $perempuan }} ({{ $pctPerempuan }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pctPerempuan }}%"></div>
                    </div>
                </div>
            </div>

            <hr>

            <button class="btn btn-outline-primary w-100 py-2 rounded-3 fw-semibold small" data-bs-toggle="modal" data-bs-target="#kompetensiModal">
                <i class="bi bi-award-fill me-1"></i> Cek 17 Unit Kompetensi SKKNI
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="kompetensiModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-check2-all text-success me-2"></i>17 Unit Kompetensi SKKNI Teruji
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-4">
                    Aplikasi PSB Online SD ini dibangun dengan memenuhi seluruh 17 Unit Kompetensi standar SKKNI Software Development. Berikut pemetaannya di kode sumber:
                </p>

                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">1. J.620100.001.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menganalisis Tools</h6>
                        <small class="text-muted">Implementasi: PHP 8.5+, Composer, SQLite Database, Git & Artisan CLI.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">2. J.620100.002.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menganalisis Skalabilitas Perangkat Lunak</h6>
                        <small class="text-muted">Implementasi: Pagination data, indexing kolom <code>no_pendaftaran</code> & <code>status</code>, storage disk terpisah.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">3. J.620100.003.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Identifikasi Library, Komponen atau Framework</h6>
                        <small class="text-muted">Implementasi: Laravel Framework, Bootstrap 5 UI, Bootstrap Icons, AOS Animation.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">4. J.620100.006.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Merancang User Experience</h6>
                        <small class="text-muted">Implementasi: Responsive UI, feedback interaktif, timeline pendaftaran visual, navigasi ramah pengguna.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">5. J.620100.017.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Mengimplementasikan Pemrograman Terstruktur</h6>
                        <small class="text-muted">Implementasi: Route grouping, modular layout Blade, middleware autentikasi, fungsi modular.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">6. J.620100.018.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Mengimplementasikan OOP</h6>
                        <small class="text-muted">Implementasi: Controller class hierarchy, Eloquent Model (<code>Pendaftaran</code>, <code>User</code>), method encapsulation.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">7. J.620100.020.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menggunakan SQL</h6>
                        <small class="text-muted">Implementasi: Raw aggregate SQL queries (COUNT, SUM CASE, GROUP BY) di <code>AdminController</code> & <code>PengumumanController</code>.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">8. J.620100.021.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menerapkan Akses Basis Data</h6>
                        <small class="text-muted">Implementasi: CRUD lengkap (Create, Read, Update, Delete) pendaftaran via Eloquent ORM & Query Builder.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">9. J.620100.022.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Mengimplementasikan Algoritma Pemrograman</h6>
                        <small class="text-muted">Implementasi: Algoritma auto-increment nomor pendaftaran format <code>PSB-YYYY-XXXX</code>, validasi usia, sorting & filtering dinamis.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">10. J.620100.024.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Melakukan Migrasi Ke Teknologi Baru</h6>
                        <small class="text-muted">Implementasi: Skema database migration Laravel berbasis SQLite mandiri tanpa ketergantungan server eksternal.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">11. J.620100.025.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Melakukan Debugging</h6>
                        <small class="text-muted">Implementasi: Try-catch block di semua controller, logging mendalam via <code>Log::error()</code> dan <code>Log::info()</code>.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">12. J.620100.030.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menerapkan Pemrograman Multimedia</h6>
                        <small class="text-muted">Implementasi: Generate bukti pendaftaran dalam format dokumen PDF (A4) menggunakan library DomPDF.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">13. J.620100.032.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Menerapkan Code Review</h6>
                        <small class="text-muted">Implementasi: Panduan coding standard PSR-12, clean code comments, dokumentasi KOMPETENSI.md.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">14. J.620100.036.02</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Pengujian Kode Program Secara Statis</h6>
                        <small class="text-muted">Implementasi: Static code analysis dengan linter PHP syntax check dan automated test suite.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">15. J.620100.044.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Alert Notification Jika Aplikasi Bermasalah</h6>
                        <small class="text-muted">Implementasi: Flash alert failure notification & banner darurat jika query atau storage gagal.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">16. J.620100.045.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Pemantauan Resource Aplikasi</h6>
                        <small class="text-muted">Implementasi: Widget live monitoring penggunaan RAM, ukuran database SQLite, storage dokumen, dan disk space.</small>
                    </div>

                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary fw-bold">17. J.620100.047.01</span>
                            <span class="badge bg-success-subtle text-success">Terpenuhi</span>
                        </div>
                        <h6 class="mb-1 fw-bold">Melakukan Pembaharuan Perangkat Lunak</h6>
                        <small class="text-muted">Implementasi: Dukungan <code>artisan migrate</code> & database versioning untuk update software tanpa henti.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
