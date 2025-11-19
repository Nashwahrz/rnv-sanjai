@extends('layouts.main')

@section('title', 'Daftar - R&V Sanjai')

@section('content')
<div class="register-page d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="register-card p-4 shadow-lg rounded-4 bg-white" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <div class="brand-logo mb-3">
                <i class="fas fa-user-plus fa-3x text-warning"></i>
            </div>
            <h3 class="fw-bold text-brown">Buat Akun Baru</h3>
            <p class="text-muted small">Daftar untuk mulai memesan produk kami 🍪</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-user text-warning"></i></span>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama kamu" required>
                </div>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-envelope text-warning"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" required>
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

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fas fa-lock text-warning"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn w-100 py-2 fw-semibold text-white"
                style="background: linear-gradient(135deg,#ff6b35,#ffc107); border: none;">
                <i class="fas fa-user-plus me-2"></i>Daftar
            </button>

            <div class="text-center mt-3">
                <p class="small text-muted mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-warning fw-semibold text-decoration-none">Login sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
.text-brown { color: #8B4513; }
.register-card input:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 .15rem rgba(255,107,53,.25);
}
.register-card .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(255,107,53,.3);
}
</style>
@endsection
