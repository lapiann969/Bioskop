
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">

        {{-- Header --}}
        <div class="text-center mb-3">
            <a class="navbar-brand brand-logo auth-logo" href="{{ url('/') }}">
                <i class="bi bi-camera-reels-fill"></i>
                TIX <span>Bioskop</span>
            </a>

            <h4 class="fw-bold mt-3 mb-1">Selamat Datang Kembali</h4>

            <p class="auth-subtitle mb-0">
                Masuk untuk melanjutkan pengalaman menontonmu.
            </p>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div id="successAlert" class="alert alert-success py-2 small mb-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan Error --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="auth-label">Email</label>

                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control auth-input @error('email') is-invalid @enderror"
                    placeholder="Masukkan email kamu"
                    autocomplete="email"
                    required
                    autofocus>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="auth-label">Password</label>

                <div class="input-group">
                    <input id="password"
                        type="password"
                        name="password"
                        class="form-control auth-input"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required>

                    <button class="btn password-toggle"
                        type="button"
                        id="togglePassword"
                        aria-label="Tampilkan password">

                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Ingat Saya --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>
            </div>

            {{-- Tombol Login --}}
            <button type="submit" class="btn auth-button w-100">
                MASUK SEKARANG
            </button>
        </form>

        <hr class="auth-divider my-3">

        <p class="auth-muted text-center mb-2">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link">
                Daftar sekarang
            </a>
        </p>

        <div class="text-center">
            <a href="{{ route('home') }}" class="auth-muted text-decoration-none">
                &larr; Kembali ke Beranda
            </a>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Tampilkan atau sembunyikan password
            const togglePassword = document.getElementById('togglePassword');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const password = document.getElementById('password');
                    const icon = this.querySelector('i');

                    if (password.type === 'password') {
                        password.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        password.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            }

            // Hilangkan pesan sukses setelah 2 detik
            const alertMessage = document.getElementById('successAlert');

            if (alertMessage) {
                setTimeout(function() {
                    alertMessage.style.transition = 'opacity 0.5s ease';
                    alertMessage.style.opacity = '0';

                    setTimeout(function() {
                        alertMessage.remove();
                    }, 500);

                }, 2000);
            }

        });
    </script>
@endpush
