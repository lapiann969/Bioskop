
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-warning fw-bold">
                Edit Film: {{ $film->title }}
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.films.update', $film->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">TMDB ID</label>
                        <input type="number" name="tmdb_id"
                            class="form-control"
                            value="{{ old('tmdb_id', $film->tmdb_id) }}"
                            min="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul Film</label>
                        <input type="text" name="title"
                            class="form-control"
                            value="{{ old('title', $film->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sinopsis</label>
                        <textarea name="description"
                            class="form-control"
                            rows="4">{{ old('description', $film->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Durasi (menit)</label>
                        <input type="number" name="duration"
                            class="form-control"
                            value="{{ old('duration', $film->duration) }}"
                            min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL Poster</label>
                        <input type="url" name="poster_url"
                            class="form-control"
                            value="{{ old('poster_url', $film->poster_url) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Film</label>
                        <select name="status" class="form-select" required>
                            <option value="Upcoming" {{ old('status', $film->status) == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="Showing" {{ old('status', $film->status) == 'Showing' ? 'selected' : '' }}>Showing</option>
                            <option value="Ended" {{ old('status', $film->status) == 'Ended' ? 'selected' : '' }}>Ended</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">Update Film</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
