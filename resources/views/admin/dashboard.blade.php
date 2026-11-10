<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bioskop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🎥 Admin Panel Bioskop</a>
        <div class="d-flex align-items-center gap-3 text-white">
            <span>Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-4">Dashboard Utama</h3>

    <!-- Ringkasan Statistik Data -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Film</h6>
                    <h2 class="fw-bold m-0">{{ $totalFilms }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Pelanggan</h6>
                    <h2 class="fw-bold m-0">{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title text-secondary">Total Pesanan</h6>
                    <h2 class="fw-bold m-0">{{ $totalBookings }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Transaksi</h6>
                    <h2 class="fw-bold m-0">{{ $totalPayments }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Menu CRUD -->
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            Navigasi Kelola Data
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ url('/admin/films') }}" class="btn btn-outline-primary">🎬 Kelola Film</a>
                <a href="{{ url('/admin/schedules') }}" class="btn btn-outline-success">⏰ Kelola Jadwal</a>
                <a href="{{ url('/admin/studio') }}" class="btn btn-outline-dark">🏛️ Kelola Studio</a>
                <a href="{{ url('/admin/seats') }}" class="btn btn-outline-secondary">🪑 Kelola Kursi</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>