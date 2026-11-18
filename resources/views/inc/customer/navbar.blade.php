<style>
    .main-navbar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1050;
        background: rgba(8, 8, 8, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        padding: 15px 0;
    }

    .brand-logo {
        color: #ffc629 !important;
        font-size: 25px;
        font-weight: 800;
        white-space: nowrap;
    }

    .brand-logo span {
        color: white;
    }

    .main-navbar .nav-link {
        color: #ddd;
        font-weight: 500;
        font-size: 14px;
        margin: 0 10px;
        transition: .3s;
    }

    .main-navbar .nav-link:hover,
    .main-navbar .nav-link.active {
        color: #ffc629;
    }

    .navbar-search-group {
        width: 270px;
        background: rgba(30, 30, 30, .75);
        border: 1px solid #444;
        border-radius: 30px;
        padding: 0 14px;
    }

    .navbar-search-group:focus-within {
        border-color: #ffc629;
    }

    .navbar-search-icon {
        color: #ffc629;
    }

    .navbar-search {
        background: transparent !important;
        border: none !important;
        color: white !important;
        box-shadow: none !important;
        padding: 10px 8px;
        font-size: 12px;
    }

    .navbar-search::placeholder {
        color: #aaa;
    }

    /* Menghilangkan background putih/biru pada input */
    .navbar-search,
    .navbar-search:focus {
        background-color: transparent !important;
        color: #fff !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* Mengatasi warna autofill Google Chrome */
    .navbar-search:-webkit-autofill,
    .navbar-search:-webkit-autofill:hover,
    .navbar-search:-webkit-autofill:focus,
    .navbar-search:-webkit-autofill:active {
        -webkit-text-fill-color: #fff !important;
        -webkit-box-shadow: 0 0 0 1000px #1e1e1e inset !important;
        box-shadow: 0 0 0 1000px #1e1e1e inset !important;
        transition: background-color 9999s ease-in-out 0s;
        border: none !important;
    }

    .navbar-search-submit {
        color: #ffc629;
        font-size: 18px;
    }

    .btn-login {
        border: 1px solid #ffc629;
        color: #ffc629;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
    }

    .btn-login:hover,
    .btn-login:focus,
    .btn-login.show {
        background: #ffc629;
        color: #111;
    }

    .navbar-dropdown {
        background: #171717;
        border: 1px solid #333;
        z-index: 1060;
    }

    .navbar-dropdown .dropdown-item {
        color: white;
    }

    .navbar-dropdown .dropdown-item:hover {
        background: #292929;
        color: #ffc629;
    }

    @media(max-width: 991px) {
        .main-navbar {
            background: rgba(8, 8, 8, .96);
        }

        .navbar-search-group {
            width: 100%;
            margin: 12px 0;
        }
    }

    .btn-register {
        background: #ffc629;
        color: #111;
        border: 1px solid #ffc629;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 700;
        font-size: 13px;
        white-space: nowrap;
    }

    .btn-register:hover {
        background: #eab308;
        border-color: #eab308;
        color: #111;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark main-navbar">

    <div class="container">

        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <i class="bi bi-camera-reels-fill"></i>
            TIX <span>Bioskop</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="customerNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('films*') ? 'active' : '' }}" href="{{ route('films.index') }}">
                        Film
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('history*') ? 'active' : '' }}"
                            href="{{ route('history.index') }}">
                            Riwayat
                        </a>
                    </li>
                @endauth

            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">

                <form action="{{ route('films.index') }}" method="GET">

                    <div class="navbar-search-group d-flex align-items-center">

                        <i class="bi bi-search navbar-search-icon"></i>

                        <input type="search" name="search" class="form-control navbar-search"
                            placeholder="Cari judul film favoritmu..." value="{{ request('search') }}">

                        <button type="submit" class="btn p-0 border-0 navbar-search-submit">
                            <i class="bi bi-arrow-right-circle"></i>
                        </button>

                    </div>

                </form>

                @guest

                    <div class="d-flex gap-2">

                        <a href="{{ route('login') }}" class="btn btn-login">
                            Masuk
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-register">
                            Daftar
                        </a>

                    </div>
                @else
                    <div class="dropdown">

                        <button id="userDropdownBtn" class="btn btn-login dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Halo, {{ Auth::user()->name }}
                        </button>

                        <ul id="userDropdownMenu" class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="userDropdownBtn">

                            <li>
                                <span class="dropdown-item-text text-white-50 small">
                                    {{ Auth::user()->email }}
                                </span>
                            </li>

                            <li>
                                <hr class="dropdown-divider border-secondary">
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('history.index') }}">
                                    <i class="bi bi-ticket-perforated me-2"></i>
                                    Riwayat Pemesanan
                                </a>
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </div>

                @endguest

            </div>

        </div>

    </div>

</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof bootstrap === 'undefined') {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
            document.body.appendChild(script);
        }
    });
</script>