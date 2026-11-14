@php
    $tempat = 'SWASH Laundry';
    $alamat = 'Jl. Ketintang Madya No. 21, Ketintang, Gayungan, Surabaya 60231';
    $wa     = '0881027836460';
    $waLink = 'https://wa.me/62' . ltrim($wa, '0');
    $jam    = ['Senin–Sabtu' => '08.00–20.00', 'Minggu' => '09.00–17.00'];

    $layanan = [
        ['nama' => 'Cuci & Setrika', 'harga' => 8000, 'estimasi' => '2 hari', 'ket' => 'Dicuci bersih, diberi pewangi, disetrika rapi, lalu dilipat.'],
        ['nama' => 'Cuci Kering', 'harga' => 6000, 'estimasi' => '1 hari', 'ket' => 'Dicuci dan dikeringkan tanpa setrika, cocok untuk pakaian harian.'],
        ['nama' => 'Setrika Saja', 'harga' => 4000, 'estimasi' => '1 hari', 'ket' => 'Pakaian sudah bersih tapi kusut? Cukup disetrika dan dilipat rapi.'],
    ];

    $rp = fn ($n) => 'Rp' . number_format($n, 0, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tempat }} — Laundry Antar-Jemput</title>
    <meta name="description" content="{{ $tempat }}: laundry antar-jemput gratis, harga per kilo jelas, bisa pesan online atau datang langsung ke toko.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body data-bs-spy="scroll" data-bs-target="#navLinks" data-bs-root-margin="-30% 0px -60% 0px" data-bs-smooth-scroll="true" tabindex="0">

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
            <ul class="navbar-nav mx-auto gap-lg-3" id="navLinks">
                <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#cara-kerja">Cara Kerja</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="#ulasan">Ulasan</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
            </ul>
            <div class="hm-nav-actions">
                <a href="{{ url('/login') }}" class="btn-line">Login</a>
                <a href="{{ url('/register') }}" class="btn-swash">Register</a>
            </div>
        </div>
    </div>
</nav>

<main>

    <section class="hm-hero" id="beranda">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h1>Cucian beres,<br>kamu tinggal santai.</h1>
                    <p class="hm-lead">
                        Driver kami jemput cucianmu, timbang di tempat, dan antar kembali
                        dalam 1–2 hari. Antar-jemput gratis, harga per kilo jelas, status
                        pesanan bisa dipantau dari website.
                    </p>
                    <div class="hm-actions">
                        <a href="{{ url('/register') }}" class="btn-swash">Daftar dan pesan</a>
                        <a href="#layanan" class="btn-line">Lihat harga</a>
                    </div>
                    <div class="hm-proof">
                        <div class="hm-avatars" aria-hidden="true"><span>C</span><span>R</span><span>D</span><span>K</span></div>
                        <div><strong>600+ pelanggan</strong> sudah langganan di {{ $tempat }}</div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hm-hero-visual">
                        <img src="{{ asset('images/hero.svg') }}" alt="Ilustrasi layanan laundry SWASH">

                        <div class="hm-chip hm-chip--a">
                            <span class="hm-dot"></span>
                            <div><strong>Driver menuju lokasimu</strong><small>Tiba sekitar 10 menit lagi</small></div>
                        </div>
                        <div class="hm-chip hm-chip--b">
                            <div><strong>{{ $rp($layanan[0]['harga']) }} /kg</strong><small>{{ $layanan[0]['nama'] }}</small></div>
                        </div>
                        <div class="hm-chip hm-chip--c">
                            <div><strong>4,9 dari 5</strong><small>1.000+ ulasan pelanggan</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container hm-stats-wrap">
        <div class="hm-stats">
            <div class="hm-stat"><strong>2.480 kg</strong><span>cucian sudah dikerjakan</span></div>
            <div class="hm-stat"><strong>600+</strong><span>pelanggan aktif</span></div>
            <div class="hm-stat"><strong>4,9 / 5</strong><span>rating rata-rata</span></div>
            <div class="hm-stat"><strong>1–2 hari</strong><span>cucian kembali ke kamu</span></div>
        </div>
    </div>

    <section class="hm-section" id="layanan">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-lg-5">
                    <h2 class="hm-h2">Harga jelas, bayar sesuai berat cucian</h2>
                    <p class="hm-sub">
                        Semua layanan dihitung per kilo. Beratnya ditimbang langsung
                        saat cucian dijemput, jadi tidak ada tebak-tebakan.
                    </p>
                    <div class="hm-ways">
                        <div class="hm-way">
                            <strong>Antar-jemput gratis</strong>
                            <span>Tanpa biaya tambahan, juga untuk layanan antar saja.</span>
                        </div>
                        <div class="hm-way">
                            <strong>Ditimbang di depan kamu</strong>
                            <span>Driver membawa timbangan, berat dan harga final dikonfirmasi admin.</span>
                        </div>
                        <div class="hm-way">
                            <strong>Bayar setelah tagihan keluar</strong>
                            <span>Pesanan baru diproses setelah kamu melihat dan membayar tagihan.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="hm-nota">
                        <div class="hm-nota-head">
                            <strong>Daftar harga {{ $tempat }}</strong>
                            <span>harga per kilogram</span>
                        </div>

                        @foreach ($layanan as $l)
                            <div class="hm-row">
                                <div class="hm-row-body">
                                    <h3>{{ $l['nama'] }}</h3>
                                    <p>{{ $l['ket'] }}</p>
                                </div>
                                <div class="hm-row-price">
                                    <strong>{{ $rp($l['harga']) }}</strong>
                                    <small>per kg, selesai {{ $l['estimasi'] }}</small>
                                </div>
                            </div>
                        @endforeach

                        <div class="hm-nota-foot">
                            Antar-jemput gratis untuk semua layanan. Berat dan harga final dikonfirmasi admin setelah cucian ditimbang.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hm-section hm-dark" id="cara-kerja">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="hm-sticky">
                        <h2 class="hm-h2">Dari cucian kotor sampai diantar kembali</h2>
                        <p class="hm-sub">
                            Ada enam langkah, dan kamu bisa melihat posisinya langsung
                            di dashboard. Ada dua cara menyerahkan cucian:
                        </p>
                        <div class="hm-ways">
                            <div class="hm-way">
                                <strong>Antar jemput</strong>
                                <span>Driver datang ke alamatmu untuk menjemput, lalu mengantar kembali.</span>
                            </div>
                            <div class="hm-way">
                                <strong>Antar saja</strong>
                                <span>Kamu membawa cucian ke toko sendiri, hasilnya kami antar ke alamatmu.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <ol class="hm-steps">
                        <li>
                            <h3>Daftar dan lengkapi profil</h3>
                            <p>Buat akun, lalu isi nama, nomor HP, dan alamat utamamu. Alamat ini bisa dipakai otomatis setiap kali memesan.</p>
                            <span class="hm-tag">Kamu</span>
                        </li>
                        <li>
                            <h3>Buat pesanan</h3>
                            <p>Pilih layanan, pilih antar jemput atau antar saja, tentukan alamat dari profil atau alamat lain, lalu atur tanggal dan jam penjemputan.</p>
                            <span class="hm-tag">Kamu</span>
                        </li>
                        <li>
                            <h3>Cucian dijemput dan ditimbang</h3>
                            <p>Driver datang sesuai jadwal dengan membawa timbangan. Cucianmu ditimbang di tempat, lalu hasilnya dikirim ke admin. Untuk antar saja, penimbangan dilakukan di toko.</p>
                            <span class="hm-tag">Driver</span>
                        </li>
                        <li>
                            <h3>Admin menyetujui berat dan harga</h3>
                            <p>Admin memeriksa berat dan menetapkan harga final. Tagihannya langsung muncul di dashboard, lengkap dengan rinciannya.</p>
                            <span class="hm-tag">Admin</span>
                        </li>
                        <li>
                            <h3>Bayar tagihan</h3>
                            <p>Lihat tagihan, lalu lakukan pembayaran. Pesananmu mulai dicuci setelah pembayaran dikonfirmasi.</p>
                            <span class="hm-tag">Kamu</span>
                        </li>
                        <li>
                            <h3>Diproses, lalu diantar kembali</h3>
                            <p>Pantau status pesanan sampai selesai. Saat pengantaran, posisi driver tampil di peta, dan kamu bisa menghubunginya begitu ia tiba di depan rumah.</p>
                            <span class="hm-tag">Tim SWASH</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="hm-section" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="hm-h2">Satu laundry, dua cara memakainya</h2>
                    <p class="hm-sub">
                        {{ $tempat }} melayani pemesanan online maupun langsung di toko.
                        Mau praktis dari rumah boleh, mau mampir sambil lihat sendiri
                        cucianmu dikerjakan juga boleh.
                    </p>
                    <div class="hm-modes">
                        <div class="hm-mode">
                            <strong>Pesan online</strong>
                            <span>Pesan lewat website, driver menjemput dan mengantar tanpa biaya.</span>
                        </div>
                        <div class="hm-mode">
                            <strong>Datang langsung</strong>
                            <span>Bawa cucianmu ke toko, ditimbang di tempat dan diproses seperti biasa.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hm-contact">
                        <h3>Kunjungi atau hubungi kami</h3>
                        <dl>
                            <div>
                                <dt>Alamat</dt>
                                <dd><strong>{{ $tempat }}</strong><br>{{ $alamat }}</dd>
                            </div>
                            <div>
                                <dt>Jam buka</dt>
                                <dd>
                                    @foreach ($jam as $hari => $buka)
                                        {{ $hari }}: {{ $buka }} WIB<br>
                                    @endforeach
                                </dd>
                            </div>
                            <div>
                                <dt>Manager (WhatsApp)</dt>
                                <dd>{{ $wa }}</dd>
                            </div>
                        </dl>
                        <a href="{{ $waLink }}" class="btn-swash" target="_blank" rel="noopener">Chat via WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hm-section hm-tint hm-testi" id="ulasan">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="hm-h2">Kata mereka yang sudah nyuci di SWASH</h2>
                <p class="hm-sub mx-auto">Lebih dari 1.200 ulasan dengan rating rata-rata 4,9 dari 5.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>bajuku wangiii bgtt kakkk 😭✨ sumpah rekomended pol, bakal langganan terus!!1!</p>
                        <div class="hm-who"><i>C</i><div><strong>Cindy_Cantikk</strong><small>Cuci &amp; Setrika</small></div></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>Drivernya ramah, cucian rapi kyk baru beli. mantul gan 👍👍 gak nyesel pokoknya</p>
                        <div class="hm-who"><i>J</i><div><strong>Bang Jaya</strong><small>Cuci Kering</small></div></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>awalnya ragu, ternyata kerennn!! bisa pantau drivernya kyk lagi nunggu ojol wkwkwk 🛵💨</p>
                        <div class="hm-who"><i>D</i><div><strong>Dewi Aprilia</strong><small>Antar Jemput</small></div></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>Setrikaannya licinnn bgt, kemeja kantor aman sentosa 😎 bintang 5 deh gak pake lama</p>
                        <div class="hm-who"><i>R</i><div><strong>Rafli_ganteng99</strong><small>Setrika Saja</small></div></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>Harga jelas, gak ada biaya aneh2. thx swash, luv u 💙💙 admin fast respon jugaaa</p>
                        <div class="hm-who"><i>S</i><div><strong>Mba Sari</strong><small>Cuci &amp; Setrika</small></div></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="hm-bubble">
                        <div class="hm-stars" aria-label="5 dari 5">★★★★★</div>
                        <p>nyuci selimut tebel jg beres, no drama 🔥🔥 udh ajak temen sekosan semua wkwk</p>
                        <div class="hm-who"><i>K</i><div><strong>Kevin Anak Kos</strong><small>Cuci Kering</small></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hm-section" id="faq">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h2 class="hm-h2">Pertanyaan yang sering muncul</h2>
                    <p class="hm-sub">Belum ketemu jawabannya? Hubungi kami lewat WhatsApp di <a href="{{ $waLink }}" target="_blank" rel="noopener">{{ $wa }}</a>.</p>
                </div>
                <div class="col-lg-8">
                    <div class="accordion accordion-flush hm-faq" id="faqList">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">Berapa lama cucian selesai?</button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqList">
                                <div class="accordion-body">Cuci kering dan setrika saja biasanya selesai dalam 1 hari, sedangkan cuci dan setrika sekitar 2 hari setelah pembayaran dikonfirmasi.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">Apakah antar-jemput benar-benar gratis?</button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqList">
                                <div class="accordion-body">Ya. Layanan antar jemput maupun antar saja tidak dikenakan biaya tambahan. Kamu hanya membayar sesuai berat cucian.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">Kapan harga akhir diketahui?</button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqList">
                                <div class="accordion-body">Setelah cucian ditimbang. Admin lalu mengonfirmasi berat dan harga final, dan kamu bisa melihat tagihannya dulu sebelum membayar.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">Boleh datang langsung ke toko?</button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqList">
                                <div class="accordion-body">Boleh. Kamu bisa membawa cucian ke {{ $tempat }} di {{ $alamat }} pada jam buka, atau tetap memesan online lewat website.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">Bagaimana cara menghubungi driver?</button>
                            </h3>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqList">
                                <div class="accordion-body">Saat pesananmu sedang diantar, buka halaman Antar Jemput di dashboard. Di sana ada posisi driver di peta dan tombol untuk menghubunginya ketika ia sudah sampai.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hm-section pt-0">
        <div class="container">
            <div class="hm-cta">
                <h2>Siap pesan laundry pertamamu?</h2>
                <p>Daftar gratis, pilih layanan, lalu tentukan jadwal penjemputan. Sisanya kami yang urus.</p>
                <div class="hm-actions justify-content-center mb-0">
                    <a href="{{ url('/register') }}" class="btn-swash">Register</a>
                    <a href="{{ url('/login') }}" class="btn-line">Login</a>
                </div>
            </div>
        </div>
    </section>

</main>

<footer class="hm-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="hm-footer-brand">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/>
                        <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="#0f2538" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    {{ $tempat }}
                </div>
                <p>Laundry antar-jemput dengan harga per kilo yang jelas. Bisa dipesan online, bisa juga langsung datang ke toko.</p>
            </div>

            <div class="col-md-6 col-lg-4">
                <h4>Hubungi kami</h4>
                <p class="mb-2"><strong>{{ $tempat }}</strong><br>{{ $alamat }}</p>
                <p class="mb-0">
                    Manager, WhatsApp:<br>
                    <a href="{{ $waLink }}" target="_blank" rel="noopener">{{ $wa }}</a>
                </p>
            </div>

            <div class="col-md-6 col-lg-3">
                <h4>Jam buka</h4>
                <p class="mb-0">
                    @foreach ($jam as $hari => $buka)
                        {{ $hari }}<br><strong>{{ $buka }} WIB</strong><br>
                    @endforeach
                </p>
            </div>
        </div>

        <div class="hm-footer-bottom">
            <span>&copy; 2026 {{ $tempat }}</span>
            <span>Laundry lebih mudah, hidup lebih nyaman.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>