
<style>
    .hero-banner {
        min-height: 220px;
        background:
            linear-gradient(90deg, rgba(0, 0, 0, .9), rgba(0, 0, 0, .25)),
            linear-gradient(135deg, #8b0000, #250000, #111);
        background-size: cover;
        background-position: center;
        border-radius: 10px;
        display: flex;
        align-items: center;
        padding: 35px;
        overflow: hidden;
        position: relative;
    }

    .hero-banner h1 {
        font-size: 30px;
        font-weight: 800;
        max-width: 450px;
    }

    .hero-banner p {
        color: #cbd5e1;
        max-width: 350px;
    }

    .hero-banner .btn {
        min-width: 130px;
    }

    .section-title {
        color: #ffc107;
        font-weight: 700;
    }

    @media (max-width: 576px) {
        .hero-banner {
            min-height: 200px;
            padding: 22px;
        }

        .hero-banner h1 {
            font-size: 24px;
        }
    }
</style>

<div class="container mt-4">

    <!-- Banner Promosi -->
    <section class="hero-banner shadow">

        <div>
            <h1 class="text-white">
                Nikmati Pengalaman Menonton Terbaik
            </h1>

            <p class="mb-3">
                Tiket bioskop sekarang lebih mudah,
                cepat dan praktis.
            </p>

            <a href="{{ url('/films') }}"
                class="btn btn-info text-white fw-bold">
                Lihat Film
            </a>
        </div>

    </section>

</div>
