@extends('layouts.customer')

@section('content')
<style>
    .seat-checkbox {
        display: none;
    }

    .seat-label {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        background-color: #444;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        user-select: none;
    }

    .seat-checkbox:checked + .seat-label {
        background-color: #ffc107;
        color: black;
        font-weight: bold;
    }

    .seat-checkbox:disabled + .seat-label {
        background-color: #28a745;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .screen-display {
        transform: perspective(200px) rotateX(-5deg);
    }
</style>

<div class="container my-5" style="padding-top: 60px;">

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('schedules.index', ['film_id' => $schedule->film_id]) }}"
           class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Jadwal
        </a>
    </div>

    <div class="row">

        <!-- Denah Kursi -->
        <div class="col-lg-8 mb-4">
            <div class="card bg-secondary text-white border-0 shadow p-4 text-center">

                <h4 class="mb-4 text-warning">
                    Pilih Kursi - {{ $schedule->studio->name }}
                </h4>

                <div class="w-100 bg-light text-dark fw-bold py-2 mb-5 rounded shadow screen-display">
                    LAYAR BIOSKOP
                </div>

                <form action="{{ route('bookings.store') }}"
                      method="POST"
                      id="seatForm">
                    @csrf

                    <input type="hidden"
                           name="schedule_id"
                           value="{{ $schedule->id }}">

                    <div class="d-flex flex-wrap justify-content-center gap-2">

                        @forelse($seats as $seat)
                            @php
                                $isBooked = in_array($seat->id, $bookedSeats);
                            @endphp

                            <div>
                                <input
                                    type="checkbox"
                                    name="selected_seats[]"
                                    value="{{ $seat->id }}"
                                    id="seat-{{ $seat->id }}"
                                    class="seat-checkbox"
                                    data-seat-name="{{ $seat->seat_number }}"
                                    {{ $isBooked ? 'disabled' : '' }}
                                >

                                <label
                                    for="seat-{{ $seat->id }}"
                                    class="seat-label shadow-sm"
                                    title="Kursi {{ $seat->seat_number }}">
                                    {{ $seat->seat_number }}
                                </label>
                            </div>

                        @empty
                            <p class="text-white-50">
                                Data kursi belum tersedia untuk studio ini.
                            </p>
                        @endforelse

                    </div>
                </form>
            </div>

            <!-- Keterangan Warna -->
            <div class="d-flex justify-content-center flex-wrap gap-4 mt-3 text-white">
                <div>
                    <span class="d-inline-block rounded"
                          style="width:15px;height:15px;background:#444;"></span>
                    Tersedia
                </div>

                <div>
                    <span class="d-inline-block rounded"
                          style="width:15px;height:15px;background:#ffc107;"></span>
                    Dipilih
                </div>

                <div>
                    <span class="d-inline-block rounded"
                          style="width:15px;height:15px;background:#28a745;"></span>
                    Terisi
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="col-lg-4">
            <div class="card bg-black text-white border-secondary shadow sticky-top"
                 style="top: 100px;">

                <div class="card-body">

                    <h5 class="card-title text-warning fw-bold border-bottom border-secondary pb-3 mb-3">
                        Ringkasan Pesanan
                    </h5>

                    <p class="mb-1">
                        <strong>Film:</strong> {{ $schedule->film->title }}
                    </p>

                    <p class="mb-1">
                        <strong>Tanggal:</strong>
                        {{ $schedule->show_time
                            ? \Carbon\Carbon::parse($schedule->show_time)->format('d M Y')
                            : '-' }}
                    </p>

                    <p class="mb-1">
                        <strong>Jam:</strong>
                        {{ $schedule->show_time
                            ? \Carbon\Carbon::parse($schedule->show_time)->format('H:i')
                            : '-' }}
                    </p>

                    <p class="mb-3">
                        <strong>Harga:</strong>
                        Rp {{ number_format($schedule->price ?? 35000, 0, ',', '.') }}
                        / tiket
                    </p>

                    <hr class="border-secondary">

                    <p class="mb-1">
                        <strong>Kursi Dipilih:</strong>
                        <span id="selectedSeatsText" class="text-warning fw-bold">-</span>
                    </p>

                    <p class="mb-4 fs-5">
                        <strong>Total:</strong>
                        <span id="totalPriceText" class="text-warning fw-bold">
                            Rp 0
                        </span>
                    </p>

                    <button type="button"
                            onclick="submitBooking()"
                            class="btn btn-warning w-100 fw-bold fs-5">
                        Lanjut Checkout
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const checkboxes = document.querySelectorAll('.seat-checkbox:not(:disabled)');
    const selectedSeatsText = document.getElementById('selectedSeatsText');
    const totalPriceText = document.getElementById('totalPriceText');
    const ticketPrice = {{ $schedule->price ?? 35000 }};

    function updateSummary() {
        const selected = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                selected.push(cb.getAttribute('data-seat-name'));
            }
        });

        if (selected.length > 0) {
            selectedSeatsText.textContent = selected.join(', ');

            const total = selected.length * ticketPrice;

            totalPriceText.textContent =
                'Rp ' + total.toLocaleString('id-ID');
        } else {
            selectedSeatsText.textContent = '-';
            totalPriceText.textContent = 'Rp 0';
        }
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSummary);
    });

    // Jalankan sekali saat halaman dimuat (antisipasi browser mengingat centang setelah refresh)
    updateSummary();

    function submitBooking() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

        if (!anyChecked) {
            alert('Silakan pilih minimal 1 kursi terlebih dahulu!');
            return;
        }

        document.getElementById('seatForm').submit();
    }
</script>
@endsection