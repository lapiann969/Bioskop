<footer class="customer-footer">
    <div class="container py-5">
        <div class="row g-4">


            <div class="row g-4">

                {{-- Identitas --}}
                <div class="col-lg-3 col-md-6">
                    <h3 class="footer-logo">
                        <i class="bi bi-camera-reels-fill"></i>
                        TIX <span>Bioskop</span>
                    </h3>
                    <p class="footer-description">
                        Nikmati pengalaman menonton film terbaik
                        dengan mudah dan nyaman bersama TIX Bioskop.
                    </p>
                </div>

                {{-- Navigasi --}}
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('films.index') }}">Film</a></li>
                        @auth
                            <li><a href="{{ route('history.index') }}">Riwayat</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Informasi Kontak --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Informasi Kontak</h5>
                    <ul class="footer-links">
                        <li>
                            <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                            Indonesia
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill text-warning me-2"></i>
                            <a href="mailto:info@tixbioskop.com">
                                info@tixbioskop.com
                            </a>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill text-warning me-2"></i>
                            <a href="tel:+620000000000">
                                +62 895-8089-56677
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Media Sosial --}}
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Ikuti Kami</h5>
                    <div class="footer-social">
                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>

            </div>


        </div>

        <hr class="footer-divider">

        <div class="text-center footer-bottom">
            &copy; {{ date('Y') }} TIX Bioskop.
            All Rights Reserved.
        </div>
    </div>
</footer>

<style>
    .customer-footer {
        background: #080808;
        border-top: 1px solid #292929;
        color: #fff;
        margin-top: 60px;
    }

    .footer-logo {
        color: #ffc629;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .footer-logo span {
        color: #fff;
    }

    .footer-description {
        color: #aaa;
        line-height: 1.8;
        max-width: 330px;
    }

    .footer-heading {
        color: #ffc629;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #aaa;
        transition: .3s;
    }

    .footer-links a:hover {
        color: #ffc629;
        padding-left: 5px;
    }

    .footer-social {
        display: flex;
        gap: 15px;
    }

    .footer-social a {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #171717;
        color: #ffc629;
        border: 1px solid #292929;
        border-radius: 50%;
        font-size: 19px;
        transition: .3s;
    }

    .footer-social a:hover {
        background: #ffc629;
        color: #080808;
    }

    .footer-divider {
        border-color: #292929;
        margin: 35px 0 20px;
    }

    .footer-bottom {
        color: #888;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .customer-footer {
            text-align: center;
        }

        .footer-description {
            margin: auto;
        }

        .footer-social {
            justify-content: center;
        }
    }
