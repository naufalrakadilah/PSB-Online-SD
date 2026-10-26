@extends('layouts.app')

@section('title', 'Login Administrator - PSB Online SD')

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
        position: relative;
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

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card" data-aos="fade-up">
        <div class="text-center mb-4">
            <div class="login-header-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h3 class="fw-bold mb-1">Login Administrator</h3>
            <p class="text-muted small">Panel Pengelolaan Penerimaan Siswa Baru SD</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4 py-2 px-3 small">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-floating mb-3">
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       placeholder="nama@sekolah.sch.id" 
                       value="{{ old('email', 'admin@psbsd.test') }}" 
                       required 
                       autofocus>
                <label for="email">Alamat Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Password" 
                       value="admin123" 
                       required>
                <label for="password">Kata Sandi</label>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-muted" for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Dashboard
            </button>

            {{-- Demo Hint Box --}}
            <div class="alert alert-light border rounded-3 p-3 mb-0 small text-muted">
                <div class="fw-semibold text-dark mb-1">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Akun Pengujian Default:
                </div>
                <div>Email: <strong class="text-dark">admin@psbsd.test</strong></div>
                <div>Password: <strong class="text-dark">admin123</strong></div>
            </div>
        </form>
    </div>
</div>
@endsection
