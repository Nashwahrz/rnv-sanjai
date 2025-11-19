@extends('layouts.main')

@section('title', 'Login - R&V Sanjai')

@section('content')
<div class="login-page d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="login-card p-4 shadow-lg rounded-4 bg-white" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <div class="brand-logo mb-3">
                <i class="fas fa-box-open fa-3x text-warning"></i>
            </div>
            <h3 class="fw-bold text-brown">Login ke R&V Sanjai</h3>
            <p class="text-muted small">Masuk untuk mulai memesan produk kami 🍪</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-envelope text-warning"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" required autofocus>
                </div>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-lock text-warning"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>
                <a href="#" class="small text-decoration-none text-warning">Lupa password?</a>
            </div>

            <button type="submit" class="btn w-100 py-2 fw-semibold text-white"
                style="background: linear-gradient(135deg,#ff6b35,#ffc107); border: none;">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>

            <div class="text-center mt-3">
                <p class="small text-muted mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-warning fw-semibold text-decoration-none">Daftar sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
.text-brown { color: #8B4513; }
.login-card input:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 .15rem rgba(255,107,53,.25);
}
.login-card .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(255,107,53,.3);
}
</style>
@endsection
