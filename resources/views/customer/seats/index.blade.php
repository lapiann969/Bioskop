<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - {{ $schedule->film->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Desain khusus untuk kotak kursi */
        .seat-checkbox { display: none; }
        .seat-label {
            display: inline-block; width: 40px; height: 40px; 
            line-height: 40px; text-align: center; 
            background-color: #444; color: white; 
            border-radius: 5px; cursor: pointer; transition: 0.3s;
        }
        .seat-checkbox:checked + .seat-label {
            background-color: #ffc107; /* Warna kuning warning Bootstrap */
            color: black; font-weight: bold;
        }
        .seat-checkbox:disabled + .seat-label {
            background-color: #dc3545; /* Warna merah (terisi) */
            cursor: not-allowed; opacity: 0.7;
        }
    </style>
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
    <div class="container">
        <a class="navbar-brand text-warning fw-bold fs-4" href="{{ route('customer.dashboard') }}">🎥 TIX Bioskop</a>
        <a href="{{ route('schedules.index', ['film_id' => $schedule->film_id]) }}" class="btn btn-outline-light btn-sm">← Kembali ke Jadwal</a>
    </div>
</nav>

<div class="container my-5">
    <div class="row">
        <!-- Bagian Kiri: Denah Kursi -->
        <div class="col-lg-8 mb-4">
            <div class="card bg-secondary text-white border-0 shadow p-4 text-center">
                <h4 class="mb-4 text-warning">Pilih Kursi - {{ $schedule->studio->name }}</h4>
                
                <!-- Layar Bioskop -->
                <div class="w-100 bg-light text-dark fw-bold py-2 mb-5 rounded shadow" style="transform: perspective(200px) rotateX(-5deg);">
                    LAYAR BIOSKOP
                </div>

                <!-- Form yang akan mengirim kursi ke halaman Checkout -->
                <form action="{{ route('checkout.show', ['booking' => 'dummy-id']) }}" method="GET" id="seatForm">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        @forelse($seats as $seat)
                            @php 
                                // Cek apakah kursi ini sudah dipesan
                                $isBooked = in_array($seat->id, $bookedSeats); 
                            @endphp
                            
                            <div>
                                <input type="checkbox" name="selected_seats[]" value="{{ $seat->id }}" id="seat-{{ $seat->id }}" class="seat-checkbox" {{ $isBooked ? 'disabled' : '' }}>
                                <label for="seat-{{ $seat->id }}" class="seat-label shadow-sm" title="Kursi {{ $seat->seat_number }}">
                                    {{ $seat->seat_number }} <!-- Asumsi kolom nama kursinya 'seat_number' atau 'name' -->
                                </label>
                            </div>
                        @empty
                            <p class="text-white-50">Data kursi belum tersedia untuk studio ini.</p>
                        @endforelse
                    </div>
                </form>
            </div>

            <!-- Keterangan Warna -->
            <div class="d-flex justify-content-center gap-4 mt-3">
                <div><span class="d-inline-block rounded" style="width:15px; height:15px; background:#444;"></span> Tersedia</div>
                <div><span class="d-inline-block rounded" style="width:15px; height:15px; background:#ffc107;"></span> Dipilih</div>
                <div><span class="d-inline-block rounded" style="width:15px; height:15px; background:#dc3545;"></span> Terisi</div>
            </div>
        </div>

        <!-- Bagian Kanan: Ringkasan Pesanan -->
        <div class="col-lg-4">
            <div class="card bg-black text-white border-secondary shadow sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title text-warning fw-bold border-bottom border-secondary pb-3 mb-3">Ringkasan Pesanan</h5>
                    <p class="mb-1"><strong>Film:</strong> {{ $schedule->film->title }}</p>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $schedule->show_date ?? $schedule->date ?? '-' }}</p>
                    <p class="mb-1"><strong>Jam:</strong> {{ $schedule->start_time ?? $schedule->show_time ?? '-' }}</p>
                    <p class="mb-3"><strong>Harga:</strong> Rp {{ number_format($schedule->price ?? 35000, 0, ',', '.') }} / tiket</p>
                    
                    <button type="button" onclick="document.getElementById('seatForm').submit();" class="btn btn-warning w-100 fw-bold fs-5">
                        Lanjut Checkout →
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>