@php
    // DATA CONTOH - nanti diganti dengan data dari database.
    $ringkasanStatus = [
        'waiting'  => ['label' => 'Menunggu (konfirmasi / bayar)', 'jumlah' => 12],
        'process'  => ['label' => 'Diproses', 'jumlah' => 9],
        'delivery' => ['label' => 'Dalam Pengantaran', 'jumlah' => 5],
        'complete' => ['label' => 'Selesai', 'jumlah' => 107],
    ];
    $totalPesanan = array_sum(array_column($ringkasanStatus, 'jumlah'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

        <p class="sidebar-label">MENU UTAMA</p>
        <nav class="sidebar-nav">
            <a href="{{ url('/admin/dashboard') }}" class="sidebar-link active">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ url('/admin/pesanan') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
                Pesanan
            </a>
            <a href="{{ url('/admin/jadwal-pickup') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                Jadwal Pickup
            </a>
            <a href="{{ url('/admin/driver') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6h11v10H2zM13 9h4l4 3v4h-8"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                Driver
            </a>
            <a href="{{ url('/admin/pembayaran') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></svg>
                Pembayaran
            </a>
            <a href="{{ url('/admin/laporan') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>
                Laporan
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-profile">
                <div class="profile-avatar">A</div>
                <div><strong>Administrator</strong><small>Admin SWASH</small></div>
            </div>
            <a class="back-home" href="{{ url('/') }}">Kembali ke halaman utama</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Dashboard</h1></div>
            <div class="topbar-actions">
                <a href="#perlu-tindakan" class="icon-button" aria-label="Pesanan perlu tindakan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span class="notif-count">4</span>
                </a>
                <div class="topbar-date"><span class="status-dot"></span>Ringkasan operasional</div>
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">SELAMAT DATANG</p>
                <h2>Halo, Admin.</h2>
                <p>Ada 4 pesanan yang menunggu tindakanmu hari ini.</p>
            </div>
            <a href="#perlu-tindakan" class="primary-button">Lihat Antrian</a>
        </section>

        <section class="stats-grid" aria-label="Ringkasan statistik">
            <article class="stat-card"><div class="stat-heading"><span>Perlu Dikonfirmasi</span><span class="stat-index">01</span></div><strong class="stat-value">5</strong><p>Pesanan baru menunggu ACC</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Perlu Input Berat</span><span class="stat-index">02</span></div><strong class="stat-value">3</strong><p>Sudah ditimbang driver</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Menunggu Pembayaran</span><span class="stat-index">03</span></div><strong class="stat-value">7</strong><p>Perlu dicek admin</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pendapatan Lunas</span><span class="stat-index">04</span></div><strong class="stat-value stat-money">Rp4,8 jt</strong><p>Contoh ringkasan pendapatan</p></article>
        </section>

        <section class="dashboard-grid" id="perlu-tindakan">
            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">ANTRIAN</span><h3>Perlu Tindakan</h3></div><a href="{{ url('/admin/pesanan') }}" class="text-action">Semua Pesanan</a></div>
                <div class="table-responsive">
                    <table class="orders-table compact">
                        <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Jenis</th><th>Perlu</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <tr><td class="order-id">SW-00131</td><td>Nadia A.</td><td>Antar Jemput</td><td><span class="order-status status-waiting">Menunggu Konfirmasi</span></td><td><a href="#" class="btn btn-primary btn-sm">ACC &amp; Atur Jam</a></td></tr>
                            <tr><td class="order-id">SW-00130</td><td>Rafi F.</td><td>Antar Saja</td><td><span class="order-status status-waiting">Menunggu Konfirmasi</span></td><td><a href="#" class="btn btn-primary btn-sm">ACC &amp; Atur Jam</a></td></tr>
                            <tr><td class="order-id">SW-00129</td><td>Dina P.</td><td>Antar Jemput</td><td><span class="order-status status-onway">Sudah Ditimbang</span></td><td><a href="#" class="btn btn-primary btn-sm">Input Berat</a></td></tr>
                            <tr><td class="order-id">SW-00125</td><td>Andi S.</td><td>Antar Jemput</td><td><span class="order-status status-payment">Menunggu Pembayaran</span></td><td><a href="#" class="btn btn-ghost btn-sm">Cek Pembayaran</a></td></tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">HARI INI</span><h3>Jadwal Driver</h3></div><a href="{{ url('/admin/jadwal-pickup') }}" class="text-action">Semua</a></div>
                <div class="schedule-list">
                    <div class="schedule-item"><span class="schedule-time">09.00</span><div><strong>Jemput · Nadia A.</strong><small>Driver: Budi · Jl. Contoh No. 12</small></div></div>
                    <div class="schedule-item"><span class="schedule-time">11.00</span><div><strong>Antar · Dina P.</strong><small>Driver: Sari · Jl. Contoh No. 7</small></div></div>
                    <div class="schedule-item"><span class="schedule-time">14.00</span><div><strong>Jemput · Rafi F.</strong><small>Driver: Budi · Jl. Contoh No. 3</small></div></div>
                </div>
            </article>
        </section>

        <section class="dashboard-grid" id="ringkasan">
            <article class="panel orders-panel" id="pesanan-terbaru">
                <div class="panel-heading orders-heading"><div><span class="panel-eyebrow">AKTIVITAS TERBARU</span><h3>Pesanan Terbaru</h3></div><a href="{{ url('/admin/pesanan') }}" class="text-action">Semua Pesanan</a></div>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Layanan</th><th>Jadwal</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td class="order-id">SW-00131</td><td><div class="customer-cell"><span class="customer-avatar">NA</span><span>Nadia A.</span></div></td><td>Cuci &amp; Setrika</td><td>23 Okt 2026</td><td>—</td><td><span class="order-status status-waiting">Menunggu Konfirmasi</span></td></tr>
                            <tr><td class="order-id">SW-00128</td><td><div class="customer-cell"><span class="customer-avatar">RF</span><span>Rafi F.</span></div></td><td>Setrika Saja</td><td>23 Okt 2026</td><td>Rp20.000</td><td><span class="order-status status-scheduled">Dijadwalkan</span></td></tr>
                            <tr><td class="order-id">SW-00126</td><td><div class="customer-cell"><span class="customer-avatar">DP</span><span>Dina P.</span></div></td><td>Cuci Kering</td><td>22 Okt 2026</td><td>Rp45.000</td><td><span class="order-status status-processing">Diproses</span></td></tr>
                            <tr><td class="order-id">SW-00125</td><td><div class="customer-cell"><span class="customer-avatar">AS</span><span>Andi S.</span></div></td><td>Cuci &amp; Setrika</td><td>21 Okt 2026</td><td>Rp30.000</td><td><span class="order-status status-payment">Menunggu Pembayaran</span></td></tr>
                            <tr><td class="order-id">SW-00120</td><td><div class="customer-cell"><span class="customer-avatar">MK</span><span>Maya K.</span></div></td><td>Cuci Kering</td><td>20 Okt 2026</td><td>Rp40.000</td><td><span class="order-status status-complete">Selesai</span></td></tr>
                        </tbody>
                    </table>
                </div>
                <p class="demo-note">Data pada dashboard ini masih contoh untuk keperluan tampilan.</p>
            </article>

            <article class="panel status-panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">STATUS</span><h3>Status Pesanan</h3></div></div>
                <div class="status-list">
                    @foreach ($ringkasanStatus as $kunci => $item)
                        <div class="status-item">
                            <div class="status-row"><span class="status-name"><span class="status-color {{ $kunci }}"></span>{{ $item['label'] }}</span><strong>{{ $item['jumlah'] }}</strong></div>
                            <div class="status-track"><span class="track-{{ $kunci }}" style="width: {{ round($item['jumlah'] / $totalPesanan * 100) }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>
</body>
</html>