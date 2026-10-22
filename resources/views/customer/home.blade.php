<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWASH — Laundry Service</title>
    <meta name="description" content="SWASH membantu urusan laundry jadi lebih praktis dengan layanan antar-jemput yang mudah diatur.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-swash sticky-top">
    <div class="container">
        <a class="navbar-brand brand" href="#beranda">
            <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/>
                <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
            SWASH
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav"
                aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-4">
                <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#cara-kerja">Cara Kerja</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
            </ul>
            {{-- ganti href sesuai route pemesanan/login di proyekmu --}}
            <a href="{{ url('/login') }}" class="btn-swash">Pesan Sekarang</a>
        </div>
    </div>
</nav>

<main>

    <!-- HERO -->
    <section class="hero" id="beranda">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="label">Laundry antar-jemput</span>
                    <h1>Pakaian bersih,<br><span>hidup lebih praktis.</span></h1>
                    <p>
                        Pilih layanan, tentukan jadwal penjemputan, lalu pantau
                        status pesananmu langsung dari website.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ url('/login') }}" class="btn-swash">Pesan Laundry</a>
                        <a href="#layanan" class="btn-line">Lihat Layanan</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-img">
                        <img src="{{ asset('images/hero.svg') }}" alt="Ilustrasi layanan laundry SWASH">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan">
        <div class="container">
            <div class="section-title">
                <span class="label" style="background: var(--mint);">Layanan</span>
                <h2>Pilih sesuai kebutuhanmu</h2>
                <p>Tiga layanan utama untuk kebutuhan laundry sehari-hari.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <article class="card-swash">
                        <div class="icon-box">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m7 4 5-2 5 2 4 3-3 4-2-1v12H8V10l-2 1-3-4 4-3Z"/>
                            </svg>
                        </div>
                        <h3>Cuci &amp; Setrika</h3>
                        <p>Pakaian dicuci lalu disetrika, jadi bersih, rapi, dan siap dipakai.</p>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="card-swash">
                        <div class="icon-box">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <circle cx="12" cy="13" r="5"/>
                            </svg>
                        </div>
                        <h3>Cuci Kering</h3>
                        <p>Pakaian dicuci dan dikeringkan tanpa disetrika, cocok untuk pakaian harian.</p>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="card-swash">
                        <div class="icon-box">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 17h18l-2 3H5l-2-3Z"/>
                                <path d="M5 17 8 5h6l5 12"/>
                            </svg>
                        </div>
                        <h3>Setrika Saja</h3>
                        <p>Sudah bersih tapi kusut? Cukup disetrika tanpa perlu dicuci ulang.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA -->
    <section class="steps" id="cara-kerja">
        <div class="container">
            <div class="section-title">
                <span class="label">Cara kerja</span>
                <h2>Pesan laundry dalam 3 langkah</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-swash">
                        <div class="step-num">1</div>
                        <h3>Pilih layanan</h3>
                        <p>Tentukan jenis layanan yang kamu butuhkan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-swash">
                        <div class="step-num">2</div>
                        <h3>Atur jadwal jemput</h3>
                        <p>Pilih tanggal dan waktu penjemputan yang cocok.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-swash">
                        <div class="step-num">3</div>
                        <h3>Pantau status</h3>
                        <p>Lihat progres pesanan sampai pakaian diantar kembali.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section class="about" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <img src="{{ asset('images/tentang.svg') }}" alt="Tentang SWASH">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="label" style="background: var(--mint);">Tentang kami</span>
                    <h2>Laundry yang lebih mudah untuk sehari-hari</h2>
                    <p>
                        SWASH dibuat untuk menghemat waktumu. Dengan layanan
                        antar-jemput, jadwal yang bisa dipilih sendiri, dan status
                        pesanan yang jelas, urusan laundry tidak perlu ribet lagi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section style="padding-top: 0;">
        <div class="container">
            <div class="cta-box">
                <h2>Siap pesan laundry pertamamu?</h2>
                <p>Daftar atau masuk, lalu pilih layanan dan jadwal penjemputan.</p>
                <a href="{{ url('/login') }}" class="btn-swash">Mulai Sekarang</a>
            </div>
        </div>
    </section>

</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <span>&copy; 2026 SWASH Laundry Service</span>
        <span>Laundry lebih mudah, hidup lebih nyaman.</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>