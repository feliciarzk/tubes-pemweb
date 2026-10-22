<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan | SWASH Admin</title><link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head><body><div class="admin-layout">
<aside class="sidebar"><a href="{{ url('/') }}" class="admin-brand" aria-label="SWASH">
<svg class="brand-logo" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/><path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="white" stroke-width="3" stroke-linecap="round"/><path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7" stroke="white" stroke-width="3" stroke-linecap="round"/></svg>
<span class="brand-copy"><strong>SWASH</strong><small>Laundry Service</small></span></a><p class="sidebar-label">MENU UTAMA</p><nav class="sidebar-nav"><a href="{{ url('/admin/dashboard') }}" class="sidebar-link "><span class="nav-mark"></span>Dashboard</a>
<a href="{{ url('/admin/pesanan') }}" class="sidebar-link active"><span class="nav-mark"></span>Pesanan</a>
<a href="{{ url('/admin/jadwal-pickup') }}" class="sidebar-link "><span class="nav-mark"></span>Jadwal Pickup</a>
<a href="{{ url('/admin/driver') }}" class="sidebar-link "><span class="nav-mark"></span>Driver</a>
<a href="{{ url('/admin/pembayaran') }}" class="sidebar-link "><span class="nav-mark"></span>Pembayaran</a>
<a href="{{ url('/admin/laporan') }}" class="sidebar-link "><span class="nav-mark"></span>Laporan</a></nav>
<div class="sidebar-bottom"><div class="admin-profile"><div class="profile-avatar">A</div><div><strong>Administrator</strong><small>Admin SWASH</small></div></div><a class="back-home" href="{{ url('/') }}">Kembali ke halaman utama</a></div></aside>
<main class="admin-main"><header class="admin-topbar"><div><span class="topbar-label">SWASH / ADMIN</span><h1>Pesanan</h1></div><div class="topbar-date"><span class="status-dot"></span>Pengelolaan pesanan</div></header>

<section class="welcome-section"><div><p class="welcome-label">MANAJEMEN PESANAN</p><h2>Daftar Pesanan</h2><p>Pantau status cucian pelanggan dari pesanan masuk hingga selesai.</p></div><button class="primary-button" onclick="alert('Form tambah pesanan akan dibuat pada tahap backend.')">Tambah Pesanan</button></section>
<section class="stats-grid">
<article class="stat-card"><div class="stat-heading"><span>Total Pesanan</span><span class="stat-index">01</span></div><strong class="stat-value">128</strong><p>Seluruh pesanan</p></article>
<article class="stat-card"><div class="stat-heading"><span>Menunggu</span><span class="stat-index">02</span></div><strong class="stat-value">7</strong><p>Belum mulai diproses</p></article>
<article class="stat-card"><div class="stat-heading"><span>Diproses</span><span class="stat-index">03</span></div><strong class="stat-value">9</strong><p>Sedang dikerjakan</p></article>
<article class="stat-card"><div class="stat-heading"><span>Selesai</span><span class="stat-index">04</span></div><strong class="stat-value">107</strong><p>Pesanan telah selesai</p></article></section>
<section class="panel orders-panel"><div class="panel-heading orders-heading"><div><span class="panel-eyebrow">DATA LAUNDRY</span><h3>Semua Pesanan</h3></div><input class="admin-search" placeholder="Cari ID atau pelanggan..." oninput="filterRows(this.value,'ordersRows')"></div>
<div class="table-responsive"><table class="orders-table"><thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Layanan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead><tbody id="ordersRows">
<tr><td class="order-id">SW-00128</td><td>Nadia A.</td><td>Cuci &amp; Setrika</td><td>16 Okt 2026</td><td>Rp35.000</td><td><span class="order-status status-processing">Diproses</span></td></tr>
<tr><td class="order-id">SW-00127</td><td>Rafi F.</td><td>Setrika Saja</td><td>16 Okt 2026</td><td>Rp20.000</td><td><span class="order-status status-waiting">Menunggu</span></td></tr>
<tr><td class="order-id">SW-00126</td><td>Dina P.</td><td>Cuci Kering</td><td>15 Okt 2026</td><td>Rp45.000</td><td><span class="order-status status-complete">Selesai</span></td></tr>
<tr><td class="order-id">SW-00125</td><td>Andi S.</td><td>Cuci &amp; Setrika</td><td>15 Okt 2026</td><td>Rp30.000</td><td><span class="order-status status-payment">Menunggu Pembayaran</span></td></tr></tbody></table></div><p class="demo-note">Data masih contoh tampilan dan belum terhubung ke database.</p></section>
<script>function filterRows(q,id){document.querySelectorAll('#'+id+' tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q.toLowerCase())?'':'none')}</script>
<footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer></main></div></body></html>