
@php
    $featuredFilm = $showingFilms->first();
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

    /* Lapisan gelap background */
    .hero-banner::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(
                90deg,
                rgba(0, 0, 0, .96) 0%,
                rgba(0, 0, 0, .80) 40%,
                rgba(0, 0, 0, .35) 100%
            ),
            linear-gradient(
                0deg,
                #080808 0%,
                transparent 50%
            );
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

    /* Sinopsis film */
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
        border: 4px solid rgba(255,255,255,.8);
        border-radius: 14px;
        transform: rotate(3deg);
        box-shadow: 0 15px 45px rgba(0,0,0,.7);
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

<section class="hero-banner"
    @if($featuredFilm && $featuredFilm->poster_url)
        style="background-image: url('{{ $featuredFilm->poster_url }}');"
    @endif>

    <div class="container hero-content">

        <div class="row align-items-center">

            <!-- INFORMASI FILM -->
            <div class="col-md-7">

                <span class="hero-label">
                    NOW SHOWING
                </span>

                <!-- JUDUL FILM -->
                <h1 class="hero-title">
                    {{ $featuredFilm->title ?? 'Nikmati Pengalaman Menonton Terbaik' }}
                </h1>

                <!-- SINOPSIS ASLI FILM -->
                <p class="hero-description">
                    @if($featuredFilm && !empty($featuredFilm->description))
                        {{ \Illuminate\Support\Str::limit(
                            $featuredFilm->description,
                            250
                        ) }}
                    @else
                        Sinopsis film belum tersedia.
                    @endif
                </p>

                <!-- TOMBOL -->
                <div class="d-flex flex-wrap gap-3">

                    @if($featuredFilm)
                        <a href="{{ route('schedules.index', ['film_id' => $featuredFilm->id]) }}"
                            class="btn hero-btn-primary">
                            PESAN SEKARANG
                        </a>
                    @endif

                    <a href="{{ route('films.index') }}"
                        class="btn hero-btn-secondary">
                        LIHAT SEMUA FILM
                    </a>

                </div>

                <div class="hero-brand">
                    TIX BIOSKOP
                </div>

            </div>

            <!-- POSTER FILM -->
            <div class="col-md-5 text-center">

                @if($featuredFilm && $featuredFilm->poster_url)

                    <img src="{{ $featuredFilm->poster_url }}"
                        alt="{{ $featuredFilm->title }}"
                        class="hero-poster">

                @endif

            </div>

        </div>

    </div>

</section>
