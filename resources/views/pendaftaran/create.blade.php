@extends('layouts.app')

@section('title', 'Formulir Pendaftaran - PSB Online SD Negeri Nusantara')

@section('styles')
<style>
    .page-header-section {
        background: var(--gradient-hero);
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .page-header-section h1 {
        color: white;
        font-weight: 800;
        font-size: 2rem;
        letter-spacing: -0.02em;
    }

    .page-header-section p {
        color: rgba(255, 255, 255, 0.6);
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 2rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .step-item .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        background: var(--gray-200);
        color: var(--gray-500);
        transition: all 0.3s ease;
    }

    .step-item.active .step-number {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
    }

    .step-item .step-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-400);
    }

    .step-item.active .step-label {
        color: var(--primary);
    }

    .step-connector {
        width: 40px;
        height: 2px;
        background: var(--gray-200);
        margin: 0 8px;
        align-self: center;
    }

    .upload-area {
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--gray-100);
    }

    .upload-area:hover {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    .upload-area i {
        font-size: 2rem;
        color: var(--gray-400);
    }

    .upload-area p {
        color: var(--gray-500);
        font-size: 0.85rem;
        margin: 0.5rem 0 0;
    }

    .upload-area .file-name {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeInUp 0.4s ease-out;
    }

    .btn-step {
        padding: 0.7rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
    {{-- PAGE HEADER --}}
    <section class="page-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 data-aos="fade-right"><i class="bi bi-pencil-square me-2"></i>Formulir Pendaftaran</h1>
                    <p data-aos="fade-right" data-aos-delay="100">Lengkapi semua data dengan benar dan sesuai dokumen resmi</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        {{-- STEP INDICATOR --}}
        <div class="step-indicator" data-aos="fade-up">
            <div class="step-item active" id="step-ind-1">
                <div class="step-number">1</div>
                <span class="step-label d-none d-md-inline">Data Siswa</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" id="step-ind-2">
                <div class="step-number">2</div>
                <span class="step-label d-none d-md-inline">Data Orang Tua</span>
            </div>
            <div class="step-connector"></div>
            <div class="step-item" id="step-ind-3">
                <div class="step-number">3</div>
                <span class="step-label d-none d-md-inline">Upload Dokumen</span>
            </div>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="alert-psb error mb-4" role="alert">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terdapat kesalahan pada formulir:</strong>
                    <ul class="mb-0 mt-2" style="font-size: 0.9rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" id="formPendaftaran">
            @csrf

            {{-- STEP 1: DATA SISWA --}}
            <div class="form-step active" id="step-1">
                <div class="form-section" data-aos="fade-up">
                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Data Calon Siswa
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label-psb" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nama_lengkap') is-invalid @enderror"
                                id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                placeholder="Masukkan nama lengkap sesuai akta" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-psb" for="nisn">NISN</label>
                            <input type="text" class="form-control form-control-psb @error('nisn') is-invalid @enderror"
                                id="nisn" name="nisn" value="{{ old('nisn') }}"
                                placeholder="Jika ada">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="nik">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nik') is-invalid @enderror"
                                id="nik" name="nik" value="{{ old('nik') }}"
                                maxlength="16" placeholder="16 digit NIK" required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control form-control-psb @error('jenis_kelamin') is-invalid @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('tempat_lahir') is-invalid @enderror"
                                id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                placeholder="Kota kelahiran" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-psb @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="agama">Agama <span class="text-danger">*</span></label>
                            <select class="form-control form-control-psb @error('agama') is-invalid @enderror"
                                id="agama" name="agama" required>
                                <option value="">-- Pilih --</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="asal_sekolah">Asal Sekolah (TK)</label>
                            <input type="text" class="form-control form-control-psb"
                                id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                placeholder="Nama TK asal">
                        </div>
                    </div>
                </div>

                <div class="form-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="form-section-title">
                        <i class="bi bi-geo-alt"></i> Alamat
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-psb" for="alamat">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-psb @error('alamat') is-invalid @enderror"
                                id="alamat" name="alamat" rows="2" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label-psb" for="rt">RT</label>
                            <input type="text" class="form-control form-control-psb" id="rt" name="rt"
                                value="{{ old('rt') }}" placeholder="001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="rw">RW</label>
                            <input type="text" class="form-control form-control-psb" id="rw" name="rw"
                                value="{{ old('rw') }}" placeholder="001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-psb" for="kelurahan">Kelurahan/Desa</label>
                            <input type="text" class="form-control form-control-psb" id="kelurahan" name="kelurahan"
                                value="{{ old('kelurahan') }}" placeholder="Nama kelurahan">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-psb" for="kecamatan">Kecamatan</label>
                            <input type="text" class="form-control form-control-psb" id="kecamatan" name="kecamatan"
                                value="{{ old('kecamatan') }}" placeholder="Nama kecamatan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-psb" for="kota">Kota/Kabupaten</label>
                            <input type="text" class="form-control form-control-psb" id="kota" name="kota"
                                value="{{ old('kota') }}" placeholder="Nama kota">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-psb" for="kode_pos">Kode Pos</label>
                            <input type="text" class="form-control form-control-psb" id="kode_pos" name="kode_pos"
                                value="{{ old('kode_pos') }}" placeholder="12345">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary-psb btn-step" onclick="nextStep(2)">
                        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2: DATA ORANG TUA --}}
            <div class="form-step" id="step-2">
                <div class="form-section" data-aos="fade-up">
                    <div class="form-section-title">
                        <i class="bi bi-person-heart"></i> Data Ayah
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-psb" for="nama_ayah">Nama Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nama_ayah') is-invalid @enderror"
                                id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" required>
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_ayah">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_ayah" name="pekerjaan_ayah"
                                value="{{ old('pekerjaan_ayah') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_ayah">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_ayah" name="no_hp_ayah"
                                value="{{ old('no_hp_ayah') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="form-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="form-section-title">
                        <i class="bi bi-person-heart"></i> Data Ibu
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-psb" for="nama_ibu">Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nama_ibu') is-invalid @enderror"
                                id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" required>
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_ibu">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_ibu" name="pekerjaan_ibu"
                                value="{{ old('pekerjaan_ibu') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_ibu">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_ibu" name="no_hp_ibu"
                                value="{{ old('no_hp_ibu') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="form-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="form-section-title">
                        <i class="bi bi-person-check"></i> Data Wali (Opsional)
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-psb" for="nama_wali">Nama Wali</label>
                            <input type="text" class="form-control form-control-psb" id="nama_wali" name="nama_wali"
                                value="{{ old('nama_wali') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_wali">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_wali" name="pekerjaan_wali"
                                value="{{ old('pekerjaan_wali') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_wali">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_wali" name="no_hp_wali"
                                value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-psb btn-step" onclick="prevStep(1)">
                        <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary-psb btn-step" onclick="nextStep(3)">
                        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 3: UPLOAD DOKUMEN --}}
            <div class="form-step" id="step-3">
                <div class="form-section" data-aos="fade-up">
                    <div class="form-section-title">
                        <i class="bi bi-cloud-arrow-up"></i> Upload Dokumen
                    </div>

                    <p class="text-muted mb-4" style="font-size: 0.9rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Format yang diterima: JPG, PNG, PDF. Maksimal 2MB untuk foto dan 5MB untuk dokumen lainnya.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-psb">Pas Foto Siswa (3x4)</label>
                            <div class="upload-area" onclick="document.getElementById('foto_siswa').click()">
                                <i class="bi bi-camera"></i>
                                <p>Klik untuk upload foto</p>
                                <span class="file-name" id="filename-foto_siswa"></span>
                            </div>
                            <input type="file" class="d-none" id="foto_siswa" name="foto_siswa"
                                accept="image/jpeg,image/png,image/jpg" onchange="showFileName(this, 'filename-foto_siswa')">
                            @error('foto_siswa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb">Akta Kelahiran</label>
                            <div class="upload-area" onclick="document.getElementById('akta_kelahiran').click()">
                                <i class="bi bi-file-earmark-text"></i>
                                <p>Klik untuk upload akta kelahiran</p>
                                <span class="file-name" id="filename-akta_kelahiran"></span>
                            </div>
                            <input type="file" class="d-none" id="akta_kelahiran" name="akta_kelahiran"
                                accept="image/jpeg,image/png,image/jpg,application/pdf" onchange="showFileName(this, 'filename-akta_kelahiran')">
                            @error('akta_kelahiran')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb">Kartu Keluarga (KK)</label>
                            <div class="upload-area" onclick="document.getElementById('kartu_keluarga').click()">
                                <i class="bi bi-file-earmark-text"></i>
                                <p>Klik untuk upload KK</p>
                                <span class="file-name" id="filename-kartu_keluarga"></span>
                            </div>
                            <input type="file" class="d-none" id="kartu_keluarga" name="kartu_keluarga"
                                accept="image/jpeg,image/png,image/jpg,application/pdf" onchange="showFileName(this, 'filename-kartu_keluarga')">
                            @error('kartu_keluarga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb">Ijazah TK / Surat Keterangan</label>
                            <div class="upload-area" onclick="document.getElementById('ijazah_tk').click()">
                                <i class="bi bi-file-earmark-text"></i>
                                <p>Klik untuk upload ijazah TK</p>
                                <span class="file-name" id="filename-ijazah_tk"></span>
                            </div>
                            <input type="file" class="d-none" id="ijazah_tk" name="ijazah_tk"
                                accept="image/jpeg,image/png,image/jpg,application/pdf" onchange="showFileName(this, 'filename-ijazah_tk')">
                            @error('ijazah_tk')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-psb btn-step" onclick="prevStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                    </button>
                    <button type="submit" class="btn btn-primary-psb btn-step" id="btn-submit-pendaftaran">
                        <i class="bi bi-check-circle me-1"></i> Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    let currentStep = 1;

    function nextStep(step) {
        // Simple validation for current step
        if (!validateStep(currentStep)) {
            return;
        }

        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('step-' + step).classList.add('active');
        document.getElementById('step-ind-' + currentStep).classList.remove('active');
        document.getElementById('step-ind-' + step).classList.add('active');
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prevStep(step) {
        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('step-' + step).classList.add('active');
        document.getElementById('step-ind-' + currentStep).classList.remove('active');
        document.getElementById('step-ind-' + step).classList.add('active');
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const stepEl = document.getElementById('step-' + step);
        const requiredFields = stepEl.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!valid) {
            // Show alert
            const alertEl = document.createElement('div');
            alertEl.className = 'alert-psb error mb-3 animate-fade-in';
            alertEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Harap lengkapi semua field yang wajib diisi (bertanda *)';

            const existingAlert = stepEl.querySelector('.alert-psb');
            if (existingAlert) existingAlert.remove();

            stepEl.insertBefore(alertEl, stepEl.firstChild);
            setTimeout(() => alertEl.remove(), 3000);
        }

        return valid;
    }

    function showFileName(input, targetId) {
        const target = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            target.textContent = input.files[0].name;
            input.closest('.upload-area').style.borderColor = 'var(--success)';
            input.closest('.upload-area').style.background = 'rgba(16, 185, 129, 0.05)';
        }
    }

    // If there are validation errors, show all steps
    @if($errors->any())
        // Show all form steps to allow user to see errors
        document.querySelectorAll('.form-step').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.step-item').forEach(el => el.classList.add('active'));
    @endif
</script>
@endsection
