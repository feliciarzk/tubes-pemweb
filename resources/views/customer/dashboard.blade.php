@php
    $name = auth()->user()->name ?? 'Pelanggan';
    $firstName = explode(' ', trim($name))[0];
    $initial = strtoupper(substr($name, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <a href="{{ url('/') }}" class="admin-brand" aria-label="SWASH">
            <svg class="brand-logo" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/>
                <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <span class="brand-copy"><strong>SWASH</strong><small>Laundry Service</small></span>
        </a>

        <p class="sidebar-label">MENU</p>
        <nav class="sidebar-nav">
            <a href="{{ url('/dashboard') }}" class="sidebar-link active">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ url('/pesanan/baru') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
                Buat Pesanan
            </a>
            <a href="{{ url('/pesanan') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
                Pesanan Saya
            </a>
            <a href="{{ url('/pickup') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                Jadwal Pickup
            </a>
            <a href="{{ url('/pembayaran') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></svg>
                Pembayaran
            </a>
            <a href="{{ url('/profil') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                Profil
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-profile">
                <div class="profile-avatar">{{ $initial }}</div>
                <div><strong>{{ $name }}</strong><small>Pelanggan</small></div>
            </div>
            <a class="back-home" href="{{ url('/') }}">Kembali ke halaman utama</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / PELANGGAN</span><h1>Dashboard</h1></div>
            <div class="topbar-date"><span class="status-dot"></span>Akun aktif</div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">SELAMAT DATANG</p>
                <h2>Halo, {{ $firstName }}!</h2>
                <p>Cucian numpuk? Jadwalkan pickup, kami yang urus sisanya.</p>
            </div>
            <a href="{{ url('/pesanan/baru') }}" class="primary-button">Buat Pesanan</a>
        </section>

        <section class="stats-grid" aria-label="Ringkasan pesanan">
            <article class="stat-card"><div class="stat-heading"><span>Pesanan Aktif</span><span class="stat-index">01</span></div><strong class="stat-value">1</strong><p>Sedang dalam proses</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Total Pesanan</span><span class="stat-index">02</span></div><strong class="stat-value">12</strong><p>Sejak bergabung</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pickup Berikutnya</span><span class="stat-index">03</span></div><strong class="stat-value stat-money">23 Okt</strong><p>Pukul 09.00 - 11.00</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Belum Dibayar</span><span class="stat-index">04</span></div><strong class="stat-value stat-money">Rp30.000</strong><p>1 pesanan menunggu</p></article>
        </section>

        <section class="dashboard-grid">
            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">PESANAN AKTIF</span><h3>Lacak Cucianmu</h3></div><span class="order-status status-processing">Diproses</span></div>

                <div class="order-summary">
                    <div><strong>SW-00128</strong><span>Cuci &amp; Setrika</span></div>
                    <div><span>Estimasi selesai</span><strong>24 Okt 2026</strong></div>
                </div>

                <ol class="tracker">
                    <li class="tracker-step done">
                        <div class="tracker-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
                        <strong>Dijemput</strong><small>21 Okt</small>
                    </li>
                    <li class="tracker-step current">
                        <div class="tracker-dot">2</div>
                        <strong>Diproses</strong><small>Sedang dicuci</small>
                    </li>
                    <li class="tracker-step">
                        <div class="tracker-dot">3</div>
                        <strong>Diantar</strong><small>Menunggu</small>
                    </li>
                    <li class="tracker-step">
                        <div class="tracker-dot">4</div>
                        <strong>Selesai</strong><small>Menunggu</small>
                    </li>
                </ol>
            </article>

            <article class="panel pickup-card">
                <div class="panel-heading" style="margin-bottom:0"><div><span class="panel-eyebrow">JADWAL</span><h3>Pickup Berikutnya</h3></div></div>
                <div class="pickup-date">
                    <div class="pickup-day"><strong>23</strong><small>Okt</small></div>
                    <div><p>Jumat, 23 Okt 2026</p><span>09.00 - 11.00 WIB</span></div>
                </div>
                <div class="pickup-info">
                    <div><span>Layanan</span><strong>Cuci Kering</strong></div>
                    <div><span>Alamat</span><strong>Alamat tersimpan di profil</strong></div>
                </div>
                <a href="{{ url('/pickup') }}" class="outline-button">Atur Jadwal</a>
            </article>
        </section>

        <section class="panel">
            <div class="panel-heading"><div><span class="panel-eyebrow">LAYANAN</span><h3>Mau Cuci Apa Hari Ini?</h3></div></div>
            <div class="service-grid">
                <a href="{{ url('/pesanan/baru') }}" class="service-card">
                    <span class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3C9 8 5 11 5 15a7 7 0 0 0 14 0c0-4-4-7-7-12Z"/></svg></span>
                    <strong>Cuci &amp; Setrika</strong>
                    <p>Dicuci bersih lalu disetrika rapi, siap dipakai.</p>
                    <span class="service-link">Pilih layanan →</span>
                </a>
                <a href="{{ url('/pesanan/baru') }}" class="service-card">
                    <span class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="13" r="4"/><path d="M7 6h.01M10 6h.01"/></svg></span>
                    <strong>Cuci Kering</strong>
                    <p>Dicuci dan dikeringkan, tanpa setrika.</p>
                    <span class="service-link">Pilih layanan →</span>
                </a>
                <a href="{{ url('/pesanan/baru') }}" class="service-card">
                    <span class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17h13l5-5H8a5 5 0 0 0-5 5Z"/><path d="M3 21h18"/></svg></span>
                    <strong>Setrika Saja</strong>
                    <p>Punya baju bersih yang butuh dirapikan.</p>
                    <span class="service-link">Pilih layanan →</span>
                </a>
            </div>
        </section>

        <section class="panel orders-panel">
            <div class="panel-heading orders-heading"><div><span class="panel-eyebrow">RIWAYAT</span><h3>Pesanan Terakhir</h3></div><a href="{{ url('/pesanan') }}" class="text-action">Semua Pesanan</a></div>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead><tr><th>ID Pesanan</th><th>Layanan</th><th>Pickup</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td class="order-id">SW-00128</td><td>Cuci &amp; Setrika</td><td>21 Okt 2026</td><td>Rp35.000</td><td><span class="order-status status-processing">Diproses</span></td></tr>
                        <tr><td class="order-id">SW-00119</td><td>Cuci Kering</td><td>14 Okt 2026</td><td>Rp30.000</td><td><span class="order-status status-payment">Menunggu Pembayaran</span></td></tr>
                        <tr><td class="order-id">SW-00104</td><td>Setrika Saja</td><td>07 Okt 2026</td><td>Rp20.000</td><td><span class="order-status status-complete">Selesai</span></td></tr>
                    </tbody>
                </table>
            </div>
            <p class="demo-note">Data pada dashboard ini masih contoh untuk keperluan tampilan.</p>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Dashboard Pelanggan</span></footer>
    </main>
</div>
</body>
</html>