
@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-card auth-card-register">

        {{-- Header --}}
        <div class="text-center mb-3">
            <a class="navbar-brand brand-logo auth-logo" href="{{ url('/') }}">
                <i class="bi bi-camera-reels-fill"></i>
                TIX <span>Bioskop</span>
            </a>

            <h4 class="fw-bold mt-3 mb-1">Buat Akun Baru</h4>

            <p class="auth-subtitle mb-0">
                Daftar dan nikmati pengalaman menonton bersama TIX Bioskop.
            </p>
        </div>

        {{-- Pesan Error --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Register --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-2">
                <label for="name" class="auth-label">Nama Lengkap</label>

                <input id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control auth-input @error('name') is-invalid @enderror"
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
                    required
                    autofocus>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-2">
                <label for="email" class="auth-label">Email</label>

                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control auth-input @error('email') is-invalid @enderror"
                    placeholder="Masukkan alamat email"
                    autocomplete="email"
                    required>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-2">
                <label for="password" class="auth-label">Password</label>

                <div class="input-group">
                    <input id="password"
                        type="password"
                        name="password"
                        class="form-control auth-input @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        required>

                    <button class="btn password-toggle"
                        type="button"
                        data-toggle-password="password"
                        aria-label="Tampilkan password">

                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-3">
                <label for="password-confirm" class="auth-label">
                    Konfirmasi Password
                </label>

                <div class="input-group">
                    <input id="password-confirm"
                        type="password"
                        name="password_confirmation"
                        class="form-control auth-input"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        required>

                    <button class="btn password-toggle"
                        type="button"
                        data-toggle-password="password-confirm"
                        aria-label="Tampilkan konfirmasi password">

                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Tombol Daftar --}}
            <button type="submit" class="btn auth-button w-100">
                DAFTAR SEKARANG
            </button>
        </form>

        <hr class="auth-divider my-3">

        <p class="auth-muted text-center mb-2">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="auth-link">
                Login sekarang
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
        document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
            button.addEventListener('click', function() {
                const inputId = this.dataset.togglePassword;
                const password = document.getElementById(inputId);
                const icon = this.querySelector('i');

                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    password.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });
    </script>
@endpush
