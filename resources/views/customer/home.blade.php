
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SWASH — Laundry Service</title>
    <meta name="description" content="SWASH membantu urusan laundry jadi lebih praktis dengan layanan dan jadwal penjemputan yang mudah diatur.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-swash sticky-top">
    <div class="container">

        <a class="navbar-brand brand" href="#beranda">
            <svg class="brand-logo" viewBox="0 0 48 48" fill="none"
                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z"
                      fill="currentColor"/>
                <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5"
                      stroke="white" stroke-width="3" stroke-linecap="round"/>
                <path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7"
                      stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>

            <span class="brand-copy">
                <strong>SWASH</strong>
                <small>Laundry Service</small>
            </span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false"
                aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link active" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang Kami</a>
                </li>
            </ul>

            <a href="#booking" class="btn btn-primary-swash">
                Pesan Sekarang
            </a>
        </div>

    </div>
</nav>


<main>

    <!-- HERO -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-6 hero-copy">
                    <span class="eyebrow">LAUNDRY MADE SIMPLE</span>

                    <h1>
                        Pakaian bersih,<br>
                        <span>hidup lebih praktis.</span>
                    </h1>

                    <p class="hero-description">
                        SWASH membantu urusan laundry jadi lebih mudah.
                        Pilih layanan dan atur jadwal penjemputan sesuai
                        kebutuhanmu.
                    </p>

                    <div class="hero-actions">
                        <a href="#booking" class="btn btn-primary-swash btn-lg">
                            Pesan Laundry
                        </a>

                        <a href="#layanan" class="btn btn-outline-swash btn-lg">
                            Lihat Layanan
                        </a>
                    </div>

                    <div class="hero-benefits">

                        <div class="benefit-item">
                            <span class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 7v5l3 2"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Praktis</strong>
                                <small>Pemesanan mudah</small>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <span class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                    <path d="M16 3v4M8 3v4M3 10h18"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Terjadwal</strong>
                                <small>Pilih waktu pickup</small>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <span class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="m12 3 8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6l8-3Z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Terpantau</strong>
                                <small>Status pesanan jelas</small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual">

                        <div class="hero-orb"></div>

                        <div class="hero-photo">
                            <img
                                src="https://images.unsplash.com/photo-1600369671236-e74521d4b6ad?auto=format&fit=crop&w=1100&q=85"
                                alt="Handuk bersih yang tertata rapi">
                        </div>

                        <div class="floating-note">
                            <span class="note-mark"></span>
                            <div>
                                <strong>Bersih. Rapi. Wangi.</strong>
                                <small>Perawatan untuk pakaianmu</small>
                            </div>
                        </div>

                        <div class="visual-caption">
                            <span>01</span>
                            <span>PERAWATAN PAKAIAN</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- LAYANAN -->
    <section class="services-section section-padding" id="layanan">
        <div class="container">

            <div class="section-heading">
                <div>
                    <span class="eyebrow">LAYANAN KAMI</span>
                    <h2>Pilihan layanan untuk<br>setiap kebutuhan.</h2>
                </div>

                <p>
                    Perawatan pakaian untuk menemani aktivitas sehari-hari,
                    tanpa perlu repot mengurus semuanya sendiri.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <article class="service-card">
                        <div class="service-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="m7 4 5-2 5 2 4 3-3 4-2-1v12H8V10l-2 1-3-4 4-3Z"/>
                                <path d="M9 5c0 2 1 3 3 3s3-1 3-3"/>
                            </svg>
                        </div>

                        <span class="service-number">01 / DAILY CARE</span>
                        <h3>Cuci &amp; Setrika</h3>

                        <p>
                            Pakaian dicuci dan disetrika agar bersih,
                            rapi, dan siap dipakai kembali.
                        </p>

                        <a href="#booking" class="text-link">
                            Pelajari layanan
                        </a>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="service-card">
                        <div class="service-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <circle cx="12" cy="13" r="5"/>
                                <path d="M7 7h2M11 7h2"/>
                            </svg>
                        </div>

                        <span class="service-number">02 / FABRIC CARE</span>
                        <h3>Cuci Kering</h3>

                        <p>
                            Perawatan khusus untuk pakaian tertentu
                            sesuai jenis bahan dan kebutuhannya.
                        </p>

                        <a href="#booking" class="text-link">
                            Pelajari layanan
                        </a>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="service-card">
                        <div class="service-icon">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M3 17h18l-2 3H5l-2-3Z"/>
                                <path d="M5 17 8 5h6l5 12"/>
                                <path d="M10 9h4M9 12h6"/>
                            </svg>
                        </div>

                        <span class="service-number">03 / FINISHING</span>
                        <h3>Setrika Saja</h3>

                        <p>
                            Pakaian kusut jadi lebih rapi tanpa perlu
                            mencucinya kembali.
                        </p>

                        <a href="#booking" class="text-link">
                            Pelajari layanan
                        </a>
                    </article>
                </div>

            </div>
        </div>
    </section>


    <!-- TENTANG SWASH -->
    <section class="about-section section-padding" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <span class="eyebrow">TENTANG SWASH</span>

                    <h2 class="about-title">
                        Laundry yang lebih mudah untuk sehari-hari.
                    </h2>

                    <p class="about-description">
                        Kami ingin membantu kamu menghemat waktu melalui
                        layanan laundry yang praktis, jadwal penjemputan
                        yang teratur, dan informasi pesanan yang mudah
                        dipantau.
                    </p>

                    <div class="feature-grid">

                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <div>
                                <strong>Kualitas terjaga</strong>
                                <small>Pakaian dirawat dengan baik</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <div>
                                <strong>Proses teratur</strong>
                                <small>Informasi pesanan lebih jelas</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <div>
                                <strong>Jadwal fleksibel</strong>
                                <small>Penjemputan sesuai pilihan</small>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <div>
                                <strong>Status pesanan</strong>
                                <small>Progres pesanan mudah dipantau</small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-visual">
                        <img
                            src="https://images.unsplash.com/photo-1517677208171-0bc6725a3e60?auto=format&fit=crop&w=1000&q=85"
                            alt="Proses pencucian pakaian"
                            loading="lazy">

                        <div class="about-image-label">
                            <span class="label-dot"></span>
                            <div>
                                <strong>Perhatian pada setiap pakaian.</strong>
                                <small>Dirawat dengan teliti.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- AJAKAN PESAN -->
    <section class="booking-section" id="booking">
        <div class="container">
            <div class="booking-banner">

                <div>
                    <span class="eyebrow">LEBIH BANYAK WAKTU UNTUKMU</span>

                    <h2>Urusan laundry,<br>biar lebih sederhana.</h2>

                    <p>
                        Pilih layanan dan tentukan jadwal penjemputan
                        sesuai kebutuhanmu.
                    </p>
                </div>

                <a href="#layanan" class="btn btn-light btn-lg">
                    Mulai Sekarang
                </a>

            </div>
        </div>
    </section>

</main>


<!-- FOOTER -->
<footer class="footer">
    <div class="container">

        <div class="footer-main">
            <a class="navbar-brand brand" href="#beranda">
                <svg class="brand-logo" viewBox="0 0 48 48" fill="none"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z"
                          fill="currentColor"/>
                    <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5"
                          stroke="white" stroke-width="3" stroke-linecap="round"/>
                    <path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7"
                          stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>

                <span class="brand-copy">
                    <strong>SWASH</strong>
                    <small>Laundry Service</small>
                </span>
            </a>

            <p>Laundry lebih mudah, hidup lebih nyaman.</p>

            <div class="footer-links">
                <a href="#beranda">Beranda</a>
                <a href="#layanan">Layanan</a>
                <a href="#tentang">Tentang Kami</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 SWASH Laundry Service</span>
            <span>Perawatan pakaian yang lebih praktis.</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
