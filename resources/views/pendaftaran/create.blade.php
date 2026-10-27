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
        </div>

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

        <form action="{{ route('pendaftaran.store') }}" method="POST" id="formPendaftaran">
            @csrf

            <div class="form-step active" id="step-1">
                <div class="form-section" data-aos="fade-up">
                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Data Calon Siswa
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-psb" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nama_lengkap') is-invalid @enderror"
                                id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                placeholder="Masukkan nama lengkap sesuai akta" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="nik">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nik') is-invalid @enderror"
                                id="nik" name="nik" value="{{ old('nik') }}"
                                maxlength="16" inputmode="numeric" pattern="[0-9]{16}" title="NIK harus 16 digit angka"
                                placeholder="16 digit NIK" required>
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
                                placeholder="Kota kelahiran" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-psb @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                min="{{ now()->subYears(12)->addDay()->toDateString() }}" max="{{ now()->subYears(5)->toDateString() }}" required>
                            <small class="text-muted">Umur calon siswa 5–11 tahun pada tanggal pendaftaran.</small>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-psb" for="tinggi_badan">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" min="50" max="200"
                                class="form-control form-control-psb @error('tinggi_badan') is-invalid @enderror"
                                id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan') }}"
                                placeholder="Contoh: 115.5" required>
                            @error('tinggi_badan')
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
                            <label class="form-label-psb" for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-psb @error('alamat') is-invalid @enderror"
                                id="alamat" name="alamat" rows="3" maxlength="500"
                                placeholder="Contoh: Jl. Kenanga No. 12, RT 03/RW 05, Kel. Sukajaya, Kec. Nusantara, Kota Nusantara 13450" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary-psb btn-step" onclick="nextStep(2)">
                        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <div class="form-step" id="step-2">
                <div class="form-section" data-aos="fade-up">
                    <div class="form-section-title">
                        <i class="bi bi-person-heart"></i> Data Ayah
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-psb" for="nama_ayah">Nama Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-psb @error('nama_ayah') is-invalid @enderror"
                                id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf" required>
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_ayah">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_ayah" name="pekerjaan_ayah"
                                value="{{ old('pekerjaan_ayah') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_ayah">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_ayah" name="no_hp_ayah"
                                value="{{ old('no_hp_ayah') }}" placeholder="08xxxxxxxxxx"
                                maxlength="12" inputmode="numeric" pattern="[0-9]{1,12}" title="Hanya angka, maksimal 12 digit">
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
                                id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf" required>
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_ibu">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_ibu" name="pekerjaan_ibu"
                                value="{{ old('pekerjaan_ibu') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_ibu">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_ibu" name="no_hp_ibu"
                                value="{{ old('no_hp_ibu') }}" placeholder="08xxxxxxxxxx"
                                maxlength="12" inputmode="numeric" pattern="[0-9]{1,12}" title="Hanya angka, maksimal 12 digit">
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
                                value="{{ old('nama_wali') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="pekerjaan_wali">Pekerjaan</label>
                            <input type="text" class="form-control form-control-psb" id="pekerjaan_wali" name="pekerjaan_wali"
                                value="{{ old('pekerjaan_wali') }}" pattern="[\p{L}\s.,'\-]+" title="Hanya boleh berisi huruf">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-psb" for="no_hp_wali">No. HP</label>
                            <input type="text" class="form-control form-control-psb" id="no_hp_wali" name="no_hp_wali"
                                value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxxxx"
                                maxlength="12" inputmode="numeric" pattern="[0-9]{1,12}" title="Hanya angka, maksimal 12 digit">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-psb btn-step" onclick="prevStep(1)">
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
        const fields = stepEl.querySelectorAll('input, select, textarea');
        let valid = true;

        fields.forEach(function(field) {
            if ((field.required && !field.value.trim()) || !field.checkValidity()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!valid) {
            const alertEl = document.createElement('div');
            alertEl.className = 'alert-psb error mb-3 animate-fade-in';
            alertEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Harap lengkapi field wajib (bertanda *) dan periksa format isian yang ditandai merah';

            const existingAlert = stepEl.querySelector('.alert-psb');
            if (existingAlert) existingAlert.remove();

            stepEl.insertBefore(alertEl, stepEl.firstChild);
            setTimeout(() => alertEl.remove(), 3000);
        }

        return valid;
    }

    @if($errors->any())
        document.querySelectorAll('.form-step').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.step-item').forEach(el => el.classList.add('active'));
    @endif
</script>
@endsection
