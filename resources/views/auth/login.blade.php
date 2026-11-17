@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">

        <div class="text-center mb-4">
            <a class="navbar-brand brand-logo auth-logo" href="{{ url('/') }}">
                <i class="bi bi-camera-reels-fill"></i>
                TIX <span>Bioskop</span>
            </a>

            <h3 class="fw-bold mt-4 mb-2">Selamat Datang Kembali</h3>

            <p class="auth-subtitle">
                Masuk untuk melanjutkan pengalaman menontonmu.
            </p>
        </div>

        @if (session('success'))
            <div id="successAlert" class="alert alert-success py-2 small">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="auth-label">Email</label>

                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-control auth-input @error('email') is-invalid @enderror" placeholder="Masukkan email kamu"
                    autocomplete="email" required autofocus>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="auth-label">Password</label>

                <div class="input-group">
                    <input id="password" type="password" name="password" class="form-control auth-input"
                        placeholder="Masukkan password" autocomplete="current-password" required>

                    <button class="btn password-toggle" type="button" id="togglePassword" aria-label="Tampilkan password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn auth-button w-100">
                MASUK SEKARANG
            </button>
        </form>

        <hr class="auth-divider">

        <p class="auth-muted text-center mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link">
                Daftar sekarang
            </a>
        </p>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="auth-muted text-decoration-none">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
@endpush
