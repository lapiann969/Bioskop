@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')
    <!-- Ringkasan Statistik Data -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Film</h6>
                    <h2 class="fw-bold m-0">{{ $totalFilms }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Pelanggan</h6>
                    <h2 class="fw-bold m-0">{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="card-title text-secondary">Total Pesanan</h6>
                    <h2 class="fw-bold m-0">{{ $totalBookings }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Transaksi</h6>
                    <h2 class="fw-bold m-0">{{ $totalPayments }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Akses Cepat -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            Akses Cepat Kelola Data
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.films.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-film me-1"></i> Kelola Film
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-calendar-event me-1"></i> Kelola Jadwal
                </a>
                <a href="{{ route('admin.studios.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-building me-1"></i> Kelola Studio
                </a>
                <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-grid-3x3-gap me-1"></i> Kelola Kursi
                </a>
            </div>
        </div>
    </div>
@endsection