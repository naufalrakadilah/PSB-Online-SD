@extends('layouts.admin')

@section('title', 'Detail Pendaftar - ' . $pendaftaran->nama_lengkap)
@section('page-title', 'Detail & Verifikasi Calon Siswa')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pendaftar') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pendaftar
    </a>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN: STUDENT & PARENTS DATA --}}
    <div class="col-lg-8">
        {{-- PROFILE HEADER CARD --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div>
                    @if($pendaftaran->foto_siswa)
                        <img src="{{ asset('storage/' . $pendaftaran->foto_siswa) }}" 
                             alt="Foto Siswa" 
                             class="rounded-4 object-fit-cover shadow-sm border" 
                             style="width: 100px; height: 125px;">
                    @else
                        <div class="rounded-4 bg-light border d-flex flex-column align-items-center justify-content-center text-muted" 
                             style="width: 100px; height: 125px;">
                            <i class="bi bi-person-bounding-box fs-1"></i>
                            <span class="small" style="font-size: 0.65rem;">Tanpa Foto</span>
                        </div>
                    @endif
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-dark px-2 py-1">{{ $pendaftaran->no_pendaftaran }}</span>
                        <span class="badge bg-light text-dark border">T.A. {{ $pendaftaran->tahun_ajaran }}</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $pendaftaran->nama_lengkap }}</h3>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-geo-alt me-1"></i> {{ $pendaftaran->alamat }}, {{ $pendaftaran->kota ?? 'Kota Nusantara' }}
                    </p>
                    <div class="d-flex gap-2">
                        @if($pendaftaran->status === 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border px-3 py-2 fw-semibold">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi
                            </span>
                        @elseif($pendaftaran->status === 'diverifikasi')
                            <span class="badge bg-info-subtle text-info border px-3 py-2 fw-semibold">
                                <i class="bi bi-patch-check me-1"></i> Berkas Diverifikasi
                            </span>
                        @elseif($pendaftaran->status === 'diterima')
                            <span class="badge bg-success-subtle text-success border px-3 py-2 fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Lolos Seleksi (Diterima)
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border px-3 py-2 fw-semibold">
                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <a href="{{ route('pendaftaran.cetak', $pendaftaran->no_pendaftaran) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-printer me-1"></i> Cetak Bukti
                    </a>
                </div>
            </div>
        </div>

        {{-- DETAIL BIODATA --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-person-lines-fill text-primary me-2"></i>Data Pribadi Calon Siswa
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <small class="text-muted d-block">Nomor Induk Kependudukan (NIK)</small>
                    <span class="fw-semibold"><code>{{ $pendaftaran->nik }}</code></span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">NISN (jika ada)</small>
                    <span class="fw-semibold">{{ $pendaftaran->nisn ?? '-' }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                    <span class="fw-semibold">
                        {{ $pendaftaran->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}
                        @php
                            $usiaTahun = \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->age;
                        @endphp
                        <span class="badge bg-secondary-subtle text-dark ms-1">Usia: {{ $usiaTahun }} tahun</span>
                    </span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Jenis Kelamin / Agama</small>
                    <span class="fw-semibold">{{ $pendaftaran->jenis_kelamin }} / {{ $pendaftaran->agama }}</span>
                </div>
                <div class="col-sm-12">
                    <small class="text-muted d-block">Alamat Lengkap</small>
                    <span class="fw-semibold">{{ $pendaftaran->alamat }} RT {{ $pendaftaran->rt ?? '-' }} / RW {{ $pendaftaran->rw ?? '-' }}, Kel. {{ $pendaftaran->kelurahan ?? '-' }}, Kec. {{ $pendaftaran->kecamatan ?? '-' }}, {{ $pendaftaran->kota ?? '-' }} {{ $pendaftaran->kode_pos ? '('.$pendaftaran->kode_pos.')' : '' }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Asal Sekolah (TK / RA / PAUD)</small>
                    <span class="fw-semibold">{{ $pendaftaran->asal_sekolah ?? 'Tidak Sekolah TK' }}</span>
                </div>
            </div>
        </div>

        {{-- PEMERIKSAAN STUNTING SISWA (naufalfunction) --}}
        @php
            $defaultAgeCategory = match(true) {
                $usiaTahun <= 5 => '5 tahun 1 bulan',
                $usiaTahun >= 7 => '7 tahun',
                default => '6 tahun',
            };
            $whoStandards = \App\Helpers\StuntingHelper::lookupWhoStandard($defaultAgeCategory, $pendaftaran->jenis_kelamin);
            $sessionResult = session('stunting_result');
        @endphp
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-heart-pulse-fill text-danger me-2"></i>Pemeriksaan Stunting Siswa (<code>naufalfunction</code>)
                </h5>
                <span class="badge bg-danger-subtle text-danger border fw-semibold">
                    <i class="bi bi-shield-check me-1"></i>Standar Antropometri WHO 2007
                </span>
            </div>

            <div class="alert alert-light border rounded-3 p-3 mb-3 small">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    <strong>Rumus Z-Score WHO:</strong>
                    <code>Z = (X - &mu;) / &sigma;</code>
                </div>
                <div class="text-muted">
                    X = Tinggi badan diukur, &mu; = Nilai median WHO, &sigma; = Simpangan baku (SD) WHO.
                    Ambang batas stunting: <strong>Z &le; -2.00 SD</strong>.
                </div>
            </div>

            <form action="{{ route('admin.cek-stunting') }}" method="POST" id="stuntingForm">
                @csrf
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-4">
                        <label for="tinggi_badan" class="form-label fw-bold small text-muted text-uppercase">Tinggi Badan Siswa (cm)</label>
                        <div class="input-group">
                            <input type="number" 
                                   step="0.1" 
                                   min="30" 
                                   max="200" 
                                   class="form-control" 
                                   id="tinggi_badan" 
                                   name="tinggi" 
                                   placeholder="Contoh: 105.0" 
                                   value="{{ old('tinggi', $sessionResult['tinggi_badan'] ?? '') }}" 
                                   required>
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Kategori Usia & Gender</label>
                        <select class="form-select" name="umur" id="stunting_umur">
                            <option value="5 tahun 1 bulan" {{ $defaultAgeCategory === '5 tahun 1 bulan' ? 'selected' : '' }}>5 tahun 1 bulan</option>
                            <option value="6 tahun" {{ $defaultAgeCategory === '6 tahun' ? 'selected' : '' }}>6 tahun</option>
                            <option value="7 tahun" {{ $defaultAgeCategory === '7 tahun' ? 'selected' : '' }}>7 tahun</option>
                        </select>
                        <input type="hidden" name="jenis_kelamin" id="stunting_gender" value="{{ $pendaftaran->jenis_kelamin }}">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-danger w-100 fw-bold">
                            <i class="bi bi-calculator me-1"></i> Hitung (naufalfunction)
                        </button>
                    </div>
                </div>

                {{-- Preset Contoh Cepat --}}
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="small text-muted fw-semibold">Contoh Cepat:</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" onclick="setTinggi(80, 86, 3)">
                        Gambar 1 (80 cm, &mu;=86, &sigma;=3)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" onclick="setTinggi(105)">
                        Tinggi 105 cm
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" onclick="setTinggi(116)">
                        Tinggi 116 cm (Normal)
                    </button>
                </div>
                <input type="hidden" name="median" id="custom_median" value="">
                <input type="hidden" name="sd" id="custom_sd" value="">
            </form>

            {{-- HASIL PENGUKURAN STUNTING --}}
            <div id="stuntingResultBox" class="{{ $sessionResult ? '' : 'd-none' }} mt-3">
                @php
                    $isStunting = $sessionResult['is_stunting'] ?? false;
                @endphp
                <div class="card border {{ $isStunting ? 'border-danger bg-danger-subtle' : 'border-success bg-success-subtle' }} rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $isStunting ? 'bg-danger' : 'bg-success' }} fs-6 px-3 py-2">
                                <i class="bi {{ $isStunting ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }} me-1"></i>
                                <span id="resStatus">{{ $sessionResult['status'] ?? '' }}</span>
                            </span>
                            <span class="fw-bold text-dark" id="resKategori">{{ $sessionResult['kategori'] ?? '' }}</span>
                        </div>
                        <div class="badge bg-dark fs-6 px-3 py-2">
                            Z-Score: <span id="resZScore">{{ isset($sessionResult['z_score']) ? number_format($sessionResult['z_score'], 2) : '' }}</span> SD
                        </div>
                    </div>

                    <div class="row g-2 text-muted small mt-1">
                        <div class="col-sm-4">
                            <strong>Tinggi (X):</strong> <span id="resTinggi">{{ $sessionResult['tinggi_badan'] ?? '' }}</span> cm
                        </div>
                        <div class="col-sm-4">
                            <strong>Median (&mu;):</strong> <span id="resMedian">{{ $sessionResult['median'] ?? '' }}</span> cm
                        </div>
                        <div class="col-sm-4">
                            <strong>Simpangan Baku (&sigma;):</strong> <span id="resSd">{{ $sessionResult['simpangan_baku'] ?? '' }}</span> cm
                        </div>
                    </div>

                    <p class="small text-dark mb-0 mt-2" id="resKeterangan">
                        {{ $sessionResult['keterangan'] ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ORANG TUA --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-people-fill text-primary me-2"></i>Data Orang Tua / Wali
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <small class="text-muted d-block">Nama Ayah Kandung</small>
                    <span class="fw-semibold">{{ $pendaftaran->nama_ayah }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Pekerjaan & Kontak Ayah</small>
                    <span class="fw-semibold">{{ $pendaftaran->pekerjaan_ayah ?? '-' }} ({{ $pendaftaran->no_hp_ayah ?? '-' }})</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Nama Ibu Kandung</small>
                    <span class="fw-semibold">{{ $pendaftaran->nama_ibu }}</span>
                </div>
                <div class="col-sm-6">
                    <small class="text-muted d-block">Pekerjaan & Kontak Ibu</small>
                    <span class="fw-semibold">{{ $pendaftaran->pekerjaan_ibu ?? '-' }} ({{ $pendaftaran->no_hp_ibu ?? '-' }})</span>
                </div>
                @if($pendaftaran->nama_wali)
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Wali</small>
                        <span class="fw-semibold">{{ $pendaftaran->nama_wali }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Pekerjaan & Kontak Wali</small>
                        <span class="fw-semibold">{{ $pendaftaran->pekerjaan_wali ?? '-' }} ({{ $pendaftaran->no_hp_wali ?? '-' }})</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- MULTIMEDIA DOCUMENTS (Kompetensi J.620100.030.02) --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-file-earmark-medical-fill text-primary me-2"></i>Dokumen Terupload
                </h5>
                <span class="badge-competency">J.620100.030.02 (Multimedia)</span>
            </div>

            <div class="row g-3">
                {{-- Foto Siswa --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card h-100 border p-2 text-center rounded-3">
                        <small class="fw-bold mb-2 text-dark">Foto Siswa</small>
                        @if($pendaftaran->foto_siswa)
                            <a href="{{ asset('storage/' . $pendaftaran->foto_siswa) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pendaftaran->foto_siswa) }}" class="img-fluid rounded mb-2" style="max-height: 120px; object-fit: cover;">
                            </a>
                            <a href="{{ asset('storage/' . $pendaftaran->foto_siswa) }}" target="_blank" class="btn btn-sm btn-light w-100">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Foto
                            </a>
                        @else
                            <div class="py-4 text-muted small">Tidak Diunggah</div>
                        @endif
                    </div>
                </div>

                {{-- Akta Kelahiran --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card h-100 border p-2 text-center rounded-3">
                        <small class="fw-bold mb-2 text-dark">Akta Kelahiran</small>
                        @if($pendaftaran->akta_kelahiran)
                            <div class="py-3 text-primary">
                                <i class="bi bi-file-earmark-pdf fs-1"></i>
                            </div>
                            <a href="{{ asset('storage/' . $pendaftaran->akta_kelahiran) }}" target="_blank" class="btn btn-sm btn-light w-100 mt-auto">
                                <i class="bi bi-eye"></i> Lihat Berkas
                            </a>
                        @else
                            <div class="py-4 text-muted small">Tidak Diunggah</div>
                        @endif
                    </div>
                </div>

                {{-- Kartu Keluarga --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card h-100 border p-2 text-center rounded-3">
                        <small class="fw-bold mb-2 text-dark">Kartu Keluarga (KK)</small>
                        @if($pendaftaran->kartu_keluarga)
                            <div class="py-3 text-info">
                                <i class="bi bi-file-earmark-text fs-1"></i>
                            </div>
                            <a href="{{ asset('storage/' . $pendaftaran->kartu_keluarga) }}" target="_blank" class="btn btn-sm btn-light w-100 mt-auto">
                                <i class="bi bi-eye"></i> Lihat Berkas
                            </a>
                        @else
                            <div class="py-4 text-muted small">Tidak Diunggah</div>
                        @endif
                    </div>
                </div>

                {{-- Ijazah TK --}}
                <div class="col-sm-6 col-md-3">
                    <div class="card h-100 border p-2 text-center rounded-3">
                        <small class="fw-bold mb-2 text-dark">Ijazah / Surat TK</small>
                        @if($pendaftaran->ijazah_tk)
                            <div class="py-3 text-success">
                                <i class="bi bi-file-earmark-check fs-1"></i>
                            </div>
                            <a href="{{ asset('storage/' . $pendaftaran->ijazah_tk) }}" target="_blank" class="btn btn-sm btn-light w-100 mt-auto">
                                <i class="bi bi-eye"></i> Lihat Berkas
                            </a>
                        @else
                            <div class="py-4 text-muted small">Tidak Diunggah</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: STATUS VERIFICATION FORM --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 90px;">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-check2-circle text-primary me-2"></i>Verifikasi & Keputusan
            </h5>

            <form action="{{ route('admin.update-status', $pendaftaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Pilih Status Seleksi</label>
                    
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check p-3 rounded-3 border {{ $pendaftaran->status === 'menunggu' ? 'border-warning bg-warning-subtle' : '' }}">
                            <input class="form-check-input" type="radio" name="status" id="status_menunggu" value="menunggu" {{ $pendaftaran->status === 'menunggu' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="status_menunggu">
                                <i class="bi bi-hourglass-split text-warning me-1"></i> Menunggu
                            </label>
                            <small class="d-block text-muted ps-4">Pendaftar baru, belum diverifikasi panitia.</small>
                        </div>

                        <div class="form-check p-3 rounded-3 border {{ $pendaftaran->status === 'diverifikasi' ? 'border-info bg-info-subtle' : '' }}">
                            <input class="form-check-input" type="radio" name="status" id="status_diverifikasi" value="diverifikasi" {{ $pendaftaran->status === 'diverifikasi' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="status_diverifikasi">
                                <i class="bi bi-patch-check text-info me-1"></i> Diverifikasi
                            </label>
                            <small class="d-block text-muted ps-4">Berkas fisik & digital telah sesuai persyaratan.</small>
                        </div>

                        <div class="form-check p-3 rounded-3 border {{ $pendaftaran->status === 'diterima' ? 'border-success bg-success-subtle' : '' }}">
                            <input class="form-check-input" type="radio" name="status" id="status_diterima" value="diterima" {{ $pendaftaran->status === 'diterima' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-success" for="status_diterima">
                                <i class="bi bi-check-circle-fill me-1"></i> Diterima (Lolos Seleksi)
                            </label>
                            <small class="d-block text-muted ps-4">Peserta lulus seleksi penerimaan siswa baru.</small>
                        </div>

                        <div class="form-check p-3 rounded-3 border {{ $pendaftaran->status === 'ditolak' ? 'border-danger bg-danger-subtle' : '' }}">
                            <input class="form-check-input" type="radio" name="status" id="status_ditolak" value="ditolak" {{ $pendaftaran->status === 'ditolak' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-danger" for="status_ditolak">
                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                            </label>
                            <small class="d-block text-muted ps-4">Tidak memenuhi syarat usia / berkas tidak valid.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="catatan_admin" class="form-label fw-bold small text-muted text-uppercase">Catatan Panitia (Opsional)</label>
                    <textarea class="form-control" 
                              id="catatan_admin" 
                              name="catatan_admin" 
                              rows="3" 
                              placeholder="Contoh: Berkas lengkap, wajib bawa Akta Kelahiran asli saat daftar ulang.">{{ old('catatan_admin', $pendaftaran->catatan_admin) }}</textarea>
                    <small class="text-muted">Catatan ini dapat dibaca oleh calon siswa saat mengecek status.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan Status
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function setTinggi(tinggi, median = null, sd = null) {
    document.getElementById('tinggi_badan').value = tinggi;
    if (median && sd) {
        document.getElementById('custom_median').value = median;
        document.getElementById('custom_sd').value = sd;
    } else {
        document.getElementById('custom_median').value = '';
        document.getElementById('custom_sd').value = '';
    }
}
</script>
@endsection
