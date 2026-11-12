@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    
    <!-- Bagian Error Validasi -->
    @if ($errors->any())
        <div class="alert alert-danger mx-auto" style="max-width: 600px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white fw-bold">
            Tambah Jadwal Tayang
        </div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Pilih Film</label>
                    <select name="film_id" class="form-select" required>
                        <option value="">-- Pilih Film --</option>
                        @foreach($films as $f)
                            <option value="{{ $f->id }}">{{ $f->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Studio</label>
                    <select name="studio_id" class="form-select" required>
                        <option value="">-- Pilih Studio --</option>
                        @foreach($studios as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Tayang</label>
                        <input type="date" name="show_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Tayang</label>
                        <input type="time" name="show_time" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Harga Tiket (Rp)</label>
                    <input type="number" name="price" class="form-control" value="35000" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div> 
@endsection