
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel') - TIX Bioskop</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: #121212;
            color: #fff;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        #sidebar .brand {
            padding: 20px 24px;
            font-size: 20px;
            font-weight: 800;
            color: #ffc629;
            border-bottom: 1px solid #2a2a2a;
        }

        #sidebar .brand span {
            color: #fff;
        }

        #sidebar .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #777;
            padding: 16px 24px 6px;
            font-weight: 700;
        }

        #sidebar .nav-link {
            color: #ccc;
            padding: 10px 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
            transition: 0.2s;
        }

        #sidebar .nav-link:hover {
            background: #1e1e1e;
            color: #ffc629;
        }

        #sidebar .nav-link.active {
            background: rgba(255, 198, 41, 0.12);
            color: #ffc629;
            border-left-color: #ffc629;
            font-weight: 600;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 24px;
        }

        /* Tampilan mobile */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
                position: fixed;
                z-index: 1050;
            }

            #sidebar.show {
                margin-left: 0;
            }
        }

        /* Footer Admin */
        .admin-footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 16px 24px;
            color: #6b7280;
            font-size: 13px;
            text-align: center;
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <aside id="sidebar">

        <div class="brand">
            <i class="bi bi-camera-reels-fill me-2"></i>TIX
            <span>Admin</span>
        </div>

        <nav class="nav flex-column pb-4">

            <!-- UTAMA -->
            <div class="menu-label">Utama</div>

            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <!-- MASTER DATA -->
            <div class="menu-label">Master Data</div>

            <a class="nav-link {{ request()->routeIs('admin.films.*') ? 'active' : '' }}"
               href="{{ route('admin.films.index') }}">
                <i class="bi bi-film"></i>
                Kelola Film
            </a>

            <a class="nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}"
               href="{{ route('admin.genres.index') }}">
                <i class="bi bi-tags"></i>
                Kelola Genre
            </a>

            <a class="nav-link {{ request()->routeIs('admin.studios.*') ? 'active' : '' }}"
               href="{{ route('admin.studios.index') }}">
                <i class="bi bi-building"></i>
                Kelola Studio
            </a>

            <a class="nav-link {{ request()->routeIs('admin.seats.*') ? 'active' : '' }}"
               href="{{ route('admin.seats.index') }}">
                <i class="bi bi-grid-3x3-gap"></i>
                Kelola Kursi
            </a>

            <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}"
               href="{{ route('admin.schedules.index') }}">
                <i class="bi bi-calendar-event"></i>
                Kelola Jadwal
            </a>

            <!-- TRANSAKSI DAN USER -->
            <div class="menu-label">Transaksi & User</div>

            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
               href="{{ route('admin.bookings.index') }}">
                <i class="bi bi-ticket-perforated"></i>
                Data Pesanan
            </a>

            <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
               href="{{ route('admin.payments.index') }}">
                <i class="bi bi-credit-card"></i>
                Data Transaksi
            </a>

            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
               href="{{ route('admin.customers.index') }}">
                <i class="bi bi-people"></i>
                Data Pelanggan
            </a>

        </nav>
    </aside>


    <!-- KONTEN KANAN -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100">

        <!-- TOPBAR -->
        <header class="topbar d-flex justify-content-between align-items-center shadow-sm">

            <div class="d-flex align-items-center gap-3">

                <button class="btn btn-outline-dark btn-sm d-md-none"
                        id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <h5 class="mb-0 fw-bold text-dark">
                    @yield('page-title', 'Dashboard Utama')
                </h5>

            </div>

            <div class="d-flex align-items-center gap-3">

                <span class="text-muted small d-none sm-inline">
                    Halo,
                    <strong>{{ Auth::user()->name ?? 'Admin Bioskop' }}</strong>
                </span>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf

                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Logout
                    </button>
                </form>

            </div>

        </header>


        <!-- ISI HALAMAN -->
        <main class="p-4 flex-grow-1">

            @yield('content')

        </main>


        <!-- FOOTER -->
        <footer class="admin-footer">
            &copy; {{ date('Y') }} TIX Bioskop. All Rights Reserved.
        </footer>

    </div>

</div>


<!-- BOOTSTRAP JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- SIDEBAR TOGGLE -->
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>


<!-- JAVASCRIPT DARI SETIAP HALAMAN -->
@stack('scripts')

</body>
</html>
