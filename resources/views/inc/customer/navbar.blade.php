
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold text-warning fs-4" href="{{ url('/') }}">
            🎥 TIX Bioskop
        </a>

        <!-- Tombol Responsive -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarCustomer">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCustomer">

            <!-- Menu -->
            <ul class="navbar-nav ms-lg-4 me-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
   href="{{ route('home') }}">
    Home
</a>
                </li>

                <li class="nav-item">
                   <a class="nav-link {{ request()->routeIs('films.*') ? 'active' : '' }}"
   href="{{ route('films.index') }}">
    Daftar Film
</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/history') }}">
                        Riwayat
                    </a>
                </li>

            </ul>

            <!-- Pencarian -->
            <form action="{{ url('/films') }}" method="GET"
                class="d-flex me-lg-4 my-3 my-lg-0">

                <input type="search"
                    name="search"
                    class="form-control form-control-sm"
                    placeholder="Cari film..."
                    value="{{ request('search') }}"
                    style="min-width: 180px;">

            </form>

            <!-- Login dan Register -->
            <div class="d-flex align-items-center gap-2">

                @auth
                    <span class="text-white me-2">
                        Halo, {{ auth()->user()->name }}!
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-light btn-sm">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="btn btn-warning btn-sm fw-semibold">
                        Register
                    </a>
                @endauth

            </div>

        </div>
    </div>
</nav>
