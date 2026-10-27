@extends('layouts.app')

@section('title', 'Daftar Akun Admin - PSB Online SD')

@section('styles')
<style>
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.08), transparent 40%),
                    radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.08), transparent 40%);
    }

    .login-card {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--gray-200);
        padding: 3rem;
        width: 100%;
        max-width: 460px;
    }

    .login-header-icon {
        width: 64px;
        height: 64px;
        background: var(--gradient-primary);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card" data-aos="fade-up">
        <div class="text-center mb-4">
            <div class="login-header-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="fw-bold mb-1">Daftar Akun Admin</h3>
            <p class="text-muted small">Akun baru bisa dipakai setelah diverifikasi oleh admin lain.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4 py-2 px-3 small">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.daftar.submit') }}" method="POST">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" placeholder="Nama lengkap" value="{{ old('name') }}" required autofocus>
                <label for="name">Nama Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="nama@sekolah.sch.id" value="{{ old('email') }}" required>
                <label for="email">Alamat Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" placeholder="Kata sandi" minlength="8" required>
                <label for="password">Kata Sandi (min. 8 karakter)</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control"
                       id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" minlength="8" required>
                <label for="password_confirmation">Ulangi Kata Sandi</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm mb-3">
                <i class="bi bi-send-check me-2"></i> Kirim Pendaftaran
            </button>

            <div class="text-center small text-muted">
                Sudah punya akun? <a href="{{ route('admin.login') }}" class="fw-semibold">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
