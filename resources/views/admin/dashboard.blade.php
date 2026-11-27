
@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Utama')

@section('content')

{{-- Statistik Utama --}}
<div class="row mb-4">

    {{-- Total Film --}}
    <div class="col-md-3">
        <div class="card bg-white shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Film</h6>
                <h2 class="fw-bold m-0 text-dark">{{ $totalFilms }}</h2>
                <small class="text-primary">
                    <i class="bi bi-film"></i> Koleksi film
                </small>
            </div>
        </div>
    </div>

    {{-- Total Pelanggan --}}
    <div class="col-md-3">
        <div class="card bg-white shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Pelanggan</h6>
                <h2 class="fw-bold m-0 text-dark">{{ $totalCustomers }}</h2>
                <small class="text-success">
                    <i class="bi bi-people"></i> Pelanggan terdaftar
                </small>
            </div>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="col-md-3">
        <div class="card bg-white shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Pesanan</h6>
                <h2 class="fw-bold m-0 text-dark">{{ $totalBookings }}</h2>
                <small class="text-warning">
                    <i class="bi bi-ticket-perforated"></i> Total booking
                </small>
            </div>
        </div>
    </div>

    {{-- Total Transaksi --}}
    <div class="col-md-3">
        <div class="card bg-white shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Transaksi</h6>
                <h2 class="fw-bold m-0 text-dark">{{ $totalPayments }}</h2>
                <small class="text-info">
                    <i class="bi bi-credit-card"></i> Data pembayaran
                </small>
            </div>
        </div>
    </div>

</div>

    {{-- Grafik Dashboard --}}
    <div class="row mb-4">

        {{-- Grafik Pemesanan --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Grafik Pemesanan
                    </h5>
                    <small class="text-muted">
                        Jumlah booking dalam 7 hari terakhir
                    </small>
                </div>

                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

        {{-- Grafik Pembayaran --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Status Pembayaran
                    </h5>
                    <small class="text-muted">
                        Statistik seluruh pembayaran
                    </small>
                </div>

                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Akses Cepat --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white fw-bold py-3">
            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
            Akses Cepat Kelola Data
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.films.index') }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-film me-1"></i>
                    Kelola Film
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                   class="btn btn-outline-success">
                    <i class="bi bi-calendar-event me-1"></i>
                    Kelola Jadwal
                </a>

                <a href="{{ route('admin.studios.index') }}"
                   class="btn btn-outline-dark">
                    <i class="bi bi-building me-1"></i>
                    Kelola Studio
                </a>

                <a href="{{ route('admin.seats.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-grid-3x3-gap me-1"></i>
                    Kelola Kursi
                </a>

            </div>
        </div>

    </div>

@endsection

@push('scripts')

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Grafik Pemesanan
            const bookingCanvas = document.getElementById('bookingChart');

            new Chart(bookingCanvas, {
                type: 'line',

                data: {
                    labels: @json($bookingLabels),

                    datasets: [{
                        label: 'Jumlah Pemesanan',
                        data: @json($bookingData),

                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.12)',

                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,

                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: '#eeeeee'
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });


            // Grafik Status Pembayaran
            const paymentCanvas = document.getElementById('paymentChart');

            new Chart(paymentCanvas, {
                type: 'doughnut',

                data: {
                    labels: @json($paymentLabels),

                    datasets: [{
                        data: @json($paymentData),

                        backgroundColor: [
                            '#198754',
                            '#ffc107',
                            '#dc3545'
                        ],

                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 8
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },

                    cutout: '65%'
                }
            });

        });
    </script>

@endpush