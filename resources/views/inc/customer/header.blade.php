
@php
    // Ambil film yang memiliki trailer sebagai slide pertama.
    $heroFilms = $showingFilms ?? collect();

    $featuredFilm = $heroFilms->first(fn($film) => filled($film->trailer_url))
        ?? $heroFilms->first();

    // Pindahkan film unggulan ke posisi pertama.
    if ($featuredFilm) {
        $heroFilms = collect([$featuredFilm])
            ->concat(
                $heroFilms->reject(fn($film) => $film->id == $featuredFilm->id)
            )
            ->values();
    }

    // Siapkan data semua slide.
    $heroSlides = $heroFilms->map(function ($film) {

        $youtubeId = null;

        if ($film->trailer_url) {
            $url = $film->trailer_url;
            $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
            $path = parse_url($url, PHP_URL_PATH) ?? '';

            if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'])) {
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);

                if (!empty($query['v'])) {
                    $youtubeId = $query['v'];
                } elseif (preg_match('~^/(embed|shorts)/([a-zA-Z0-9_-]+)~', $path, $matches)) {
                    $youtubeId = $matches[2];
                }
            } elseif ($host === 'youtu.be') {
                $youtubeId = trim($path, '/');
            }

            if (!is_string($youtubeId) || !preg_match('/^[a-zA-Z0-9_-]{11}$/', $youtubeId)) {
                $youtubeId = null;
            }
        }

        return [
            'id' => $film->id,
            'title' => $film->title,
            'description' => \Illuminate\Support\Str::limit(
                $film->description ?: 'Sinopsis film belum tersedia.',
                250
            ),
            'poster_url' => $film->poster_url,
            'schedule_url' => route('schedules.index', ['film_id' => $film->id]),
            'youtube_id' => $youtubeId,
        ];
    })->values();

    $firstSlide = $heroSlides->first();
@endphp

<style>
    .hero-banner {
        width: 100%;
        min-height: 620px;
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #080808;
        background-size: cover;
        background-position: center 30%;
    }

    .hero-banner::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg,
                rgba(0, 0, 0, .96) 0%,
                rgba(0, 0, 0, .80) 40%,
                rgba(0, 0, 0, .35) 100%),
            linear-gradient(0deg,
                #080808 0%,
                transparent 50%);
        z-index: 1;
    }

    .hero-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        backdrop-filter: blur(3px);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 150px 0 80px;
    }

    /* Transisi pergantian konten */
    .hero-slide-content {
        opacity: 1;
        transform: translateY(0);
        transition: opacity .35s ease, transform .35s ease;
    }

    .hero-slide-content.changing {
        opacity: 0;
        transform: translateY(10px);
    }

    .hero-label {
        display: inline-block;
        background: #ffc629;
        color: #111;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        padding: 9px 19px;
        border-radius: 30px;
        margin-bottom: 22px;
    }

    .hero-title {
        color: white;
        font-size: clamp(38px, 5vw, 58px);
        font-weight: 800;
        line-height: 1.1;
        max-width: 650px;
        margin-bottom: 18px;
    }

    .hero-description {
        color: #e0e0e0;
        font-size: 15px;
        line-height: 1.8;
        max-width: 520px;
        margin-bottom: 30px;
    }

    .hero-btn-primary {
        background: #ffc629;
        color: #111;
        border: 2px solid #ffc629;
        padding: 14px 30px;
        border-radius: 35px;
        font-size: 12px;
        font-weight: 800;
        transition: .3s;
    }

    .hero-btn-primary:hover {
        background: white;
        border-color: white;
        color: #111;
    }

    .hero-btn-secondary {
        border: 2px solid white;
        color: white;
        padding: 14px 30px;
        border-radius: 35px;
        font-size: 12px;
        font-weight: 800;
        transition: .3s;
    }

    .hero-btn-secondary:hover {
        background: white;
        color: #111;
    }

    .hero-poster {
        width: 245px;
        height: 350px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, .8);
        border-radius: 14px;
        transform: rotate(3deg);
        box-shadow: 0 15px 45px rgba(0, 0, 0, .7);
        transition: .3s;
    }

    .hero-poster:hover {
        transform: rotate(0deg) scale(1.04);
    }

    .hero-brand {
        color: #ffc629;
        letter-spacing: 6px;
        font-weight: 800;
        font-size: 13px;
        margin-top: 35px;
    }

    @media(max-width: 767px) {
        .hero-banner {
            min-height: 600px;
            background-position: center;
        }

        .hero-content {
            padding: 140px 20px 60px;
        }

        .hero-title {
            font-size: 36px;
        }

        .hero-description {
            font-size: 13px;
        }

        .hero-poster {
            display: none;
        }
    }
</style>

<section
    class="hero-banner"
    id="heroBanner"
    @if ($firstSlide && $firstSlide['poster_url'])
        style="background-image: url('{{ $firstSlide['poster_url'] }}');"
    @endif
>

    <div class="container hero-content">

        <div class="row align-items-center hero-slide-content" id="heroSlideContent">

            {{-- INFORMASI FILM --}}
            <div class="col-md-7">

                <span class="hero-label">
                    NOW SHOWING
                </span>

                <h1 class="hero-title" id="heroTitle">
                    {{ $firstSlide['title'] ?? 'Nikmati Pengalaman Menonton Terbaik' }}
                </h1>

                <p class="hero-description" id="heroDescription">
                    {{ $firstSlide['description'] ?? 'Temukan film favoritmu di TIX Bioskop.' }}
                </p>

                <div class="d-flex flex-wrap gap-3">

                    <a
                        href="{{ $firstSlide['schedule_url'] ?? '#' }}"
                        class="btn hero-btn-primary"
                        id="heroBookButton"
                        @if (!$firstSlide) hidden @endif>
                        PESAN SEKARANG
                    </a>

                    <button
                        type="button"
                        class="btn hero-btn-secondary"
                        id="heroTrailerButton"
                        data-bs-toggle="modal"
                        data-bs-target="#heroTrailerModal"
                        @if (!$firstSlide || !$firstSlide['youtube_id']) hidden @endif
                    >
                        <i class="fas fa-play me-2"></i>
                        LIHAT TRAILER
                    </button>

                </div>

                <div class="hero-brand">
                    TIX BIOSKOP
                </div>

            </div>

            {{-- POSTER FILM --}}
            <div class="col-md-5 text-center">

                <img
                    src="{{ $firstSlide['poster_url'] ?? '' }}"
                    alt="{{ $firstSlide['title'] ?? 'Poster film' }}"
                    class="hero-poster"
                    id="heroPoster"
                    @if (!$firstSlide || !$firstSlide['poster_url']) hidden @endif
                >

            </div>

        </div>

    </div>

</section>

{{-- MODAL TRAILER --}}
<div
    class="modal fade"
    id="heroTrailerModal"
    tabindex="-1"
    aria-labelledby="heroTrailerTitle"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content bg-dark text-white">

            <div class="modal-header border-secondary">

                <h5 class="modal-title" id="heroTrailerTitle">
                    Trailer {{ $firstSlide['title'] ?? '' }}
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>

            <div class="modal-body p-0">

                <div class="ratio ratio-16x9">

                    <iframe
                        id="heroTrailerFrame"
                        src=""
                        title="Trailer film"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const slides = @json($heroSlides);

    if (!slides || slides.length === 0) {
        return;
    }

    const banner = document.getElementById('heroBanner');
    const content = document.getElementById('heroSlideContent');

    const title = document.getElementById('heroTitle');
    const description = document.getElementById('heroDescription');
    const poster = document.getElementById('heroPoster');

    const bookButton = document.getElementById('heroBookButton');
    const trailerButton = document.getElementById('heroTrailerButton');

    const modal = document.getElementById('heroTrailerModal');
    const modalTitle = document.getElementById('heroTrailerTitle');
    const iframe = document.getElementById('heroTrailerFrame');

    let currentIndex = 0;
    let slideTimer = null;

    function showSlide(index) {

        const slide = slides[index];

        content.classList.add('changing');

        setTimeout(function () {

            title.textContent = slide.title;
            description.textContent = slide.description;

            // Ganti background banner.
            banner.style.backgroundImage = slide.poster_url
                ? `url(${JSON.stringify(slide.poster_url)})`
                : 'none';

            // Ganti poster.
            if (slide.poster_url) {
                poster.src = slide.poster_url;
                poster.alt = slide.title;
                poster.hidden = false;
            } else {
                poster.hidden = true;
            }

            // Ganti tombol pesan.
            bookButton.href = slide.schedule_url;
            bookButton.hidden = !slide.schedule_url;

            // Ganti tombol trailer.
            trailerButton.hidden = !slide.youtube_id;

            modalTitle.textContent = 'Trailer ' + slide.title;

            content.classList.remove('changing');

        }, 350);
    }

    function startSlideshow() {

        if (slides.length <= 1 || slideTimer) {
            return;
        }

        slideTimer = setInterval(function () {

            currentIndex = (currentIndex + 1) % slides.length;

            showSlide(currentIndex);

        }, 6000);
    }

    function stopSlideshow() {

        if (slideTimer) {
            clearInterval(slideTimer);
            slideTimer = null;
        }
    }

    // Putar trailer dari film yang sedang tampil.
    trailerButton.addEventListener('click', function () {

        const slide = slides[currentIndex];

        if (slide && slide.youtube_id) {
            iframe.src =
                'https://www.youtube-nocookie.com/embed/' +
                slide.youtube_id +
                '?autoplay=1&rel=0';
        }

    });

    // Hentikan slideshow selama modal trailer terbuka.
    modal.addEventListener('show.bs.modal', stopSlideshow);

    modal.addEventListener('hidden.bs.modal', function () {

        iframe.src = '';

        startSlideshow();

    });

    // Mulai slideshow.
    startSlideshow();

});
</script>
