<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Driver | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            <a href="{{ url('/admin/dashboard') }}" class="sidebar-link"><span class="nav-mark"></span>Dashboard</a>
            <a href="{{ url('/admin/pesanan') }}" class="sidebar-link"><span class="nav-mark"></span>Pesanan</a>
            <a href="{{ url('/admin/jadwal-pickup') }}" class="sidebar-link"><span class="nav-mark"></span>Jadwal Pickup</a>
            <a href="{{ url('/admin/driver') }}" class="sidebar-link active"><span class="nav-mark"></span>Driver</a>
            <a href="{{ url('/admin/pembayaran') }}" class="sidebar-link"><span class="nav-mark"></span>Pembayaran</a>
            <a href="{{ url('/admin/laporan') }}" class="sidebar-link"><span class="nav-mark"></span>Laporan</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="admin-profile"><div class="profile-avatar">A</div><div><strong>Administrator</strong><small>Admin SWASH</small></div></div>
            <a class="back-home" href="{{ url('/') }}">Kembali ke halaman utama</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Monitoring Driver</h1></div>
            <div class="topbar-date"><span class="status-dot"></span>Operasional hari ini</div>
        </header>

        <section class="welcome-section driver-welcome">
            <div><p class="welcome-label">MONITORING OPERASIONAL</p><h2>Pantau perjalanan driver</h2><p>Lihat status pickup dan pengantaran dalam satu halaman.</p></div>
            <span class="live-label"><span></span> Status diperbarui manual</span>
        </section>

        <section class="stats-grid driver-stats">
            <article class="stat-card"><div class="stat-heading"><span>Driver Aktif</span><span class="stat-index">01</span></div><strong class="stat-value">3</strong><p>Sedang menjalankan tugas</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pickup Berjalan</span><span class="stat-index">02</span></div><strong class="stat-value">5</strong><p>Penjemputan belum selesai</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Dalam Pengantaran</span><span class="stat-index">03</span></div><strong class="stat-value">2</strong><p>Pesanan menuju pelanggan</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Tugas Selesai</span><span class="stat-index">04</span></div><strong class="stat-value">12</strong><p>Selesai hari ini</p></article>
        </section>

        <section class="driver-monitor-grid">
            <article class="panel map-panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">AREA OPERASIONAL</span><h3>Peta Monitoring</h3></div><span class="map-demo-badge">Simulasi peta</span></div>
                <div class="monitor-map" role="img" aria-label="Ilustrasi peta untuk monitoring driver, bukan lokasi GPS langsung">
                    <div class="map-block block-a"></div><div class="map-block block-b"></div><div class="map-block block-c"></div><div class="map-block block-d"></div><div class="map-block block-e"></div>
                    <div class="map-road road-horizontal"></div><div class="map-road road-vertical"></div><div class="map-road road-diagonal"></div><div class="map-road road-small"></div>
                    <div class="map-route route-one"></div><div class="map-route route-two"></div>
                    <div class="map-pin pin-one"><span>1</span></div><div class="map-pin pin-two"><span>2</span></div><div class="map-pin pin-three"><span>3</span></div>
                    <div class="map-location-label label-one">Pickup A</div><div class="map-location-label label-two">Pelanggan B</div><div class="map-location-label label-three">SWASH</div>
                    <div class="map-legend"><span><i class="legend-dot teal-dot"></i>Pickup</span><span><i class="legend-dot orange-dot"></i>Pengantaran</span><span><i class="legend-dot navy-dot"></i>Outlet</span></div>
                </div>
                <p class="demo-note">Peta ini masih ilustrasi. Posisi real-time memerlukan integrasi GPS, izin lokasi driver, dan backend.</p>
            </article>

            <article class="panel active-drivers-panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">STATUS LAPANGAN</span><h3>Driver sedang bertugas</h3></div><span class="driver-count">3 driver</span></div>
                <div class="driver-live-list">
                    <article class="driver-live-card">
                        <div class="driver-avatar">BP</div><div class="driver-live-info"><strong>Bagas Pratama</strong><span>DR-001 · Motor</span><small><i class="mini-dot dot-pickup"></i> Menuju lokasi pickup</small></div><span class="driver-eta">8 mnt</span>
                    </article>
                    <article class="driver-live-card">
                        <div class="driver-avatar avatar-two">RM</div><div class="driver-live-info"><strong>Rizky Maulana</strong><span>DR-002 · Motor</span><small><i class="mini-dot dot-delivery"></i> Mengantar pesanan</small></div><span class="driver-eta">12 mnt</span>
                    </article>
                    <article class="driver-live-card">
                        <div class="driver-avatar avatar-three">DS</div><div class="driver-live-info"><strong>Deni Saputra</strong><span>DR-003 · Motor</span><small><i class="mini-dot dot-pickup"></i> Menuju lokasi pickup</small></div><span class="driver-eta">5 mnt</span>
                    </article>
                </div>
                <p class="demo-note">Nama, status, dan estimasi waktu di atas adalah data contoh.</p>
            </article>
        </section>

        <section class="panel orders-panel driver-task-panel">
            <div class="panel-heading orders-heading"><div><span class="panel-eyebrow">TUGAS DRIVER</span><h3>Daftar Pickup dan Pengantaran</h3></div><input class="admin-search" placeholder="Cari driver atau pesanan..." oninput="filterDriverTasks(this.value)"></div>
            <div class="table-responsive"><table class="orders-table"><thead><tr><th>Driver</th><th>ID Pesanan</th><th>Jenis Tugas</th><th>Tujuan</th><th>Estimasi</th><th>Status</th></tr></thead>
                <tbody id="driverTaskRows">
                    <tr><td><div class="customer-cell"><span class="customer-avatar">BP</span><span>Bagas Pratama</span></div></td><td class="order-id">SW-00128</td><td>Pickup</td><td>Jl. Melati No. 12</td><td>8 menit</td><td><span class="order-status status-processing">Dalam perjalanan</span></td></tr>
                    <tr><td><div class="customer-cell"><span class="customer-avatar">RM</span><span>Rizky Maulana</span></div></td><td class="order-id">SW-00127</td><td>Pengantaran</td><td>Jl. Kenanga No. 8</td><td>12 menit</td><td><span class="order-status status-payment">Mengantar</span></td></tr>
                    <tr><td><div class="customer-cell"><span class="customer-avatar">DS</span><span>Deni Saputra</span></div></td><td class="order-id">SW-00124</td><td>Pickup</td><td>Jl. Mawar No. 4</td><td>5 menit</td><td><span class="order-status status-processing">Dalam perjalanan</span></td></tr>
                    <tr><td><div class="customer-cell"><span class="customer-avatar">BP</span><span>Bagas Pratama</span></div></td><td class="order-id">SW-00121</td><td>Pengantaran</td><td>Jl. Anggrek No. 5</td><td>—</td><td><span class="order-status status-complete">Selesai</span></td></tr>
                </tbody>
            </table></div>
            <p class="demo-note">Monitoring saat ini berbasis data contoh, belum menggunakan GPS atau pelacakan langsung seperti aplikasi Gojek.</p>
        </section>
        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>
<script>
function filterDriverTasks(query) {
    document.querySelectorAll('#driverTaskRows tr').forEach(function (row) {
        row.style.display = row.innerText.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}
</script>
</body>
</html>
