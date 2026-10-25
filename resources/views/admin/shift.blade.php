@php
    $pesanan = [
        ['id' => 'SW-00131', 'pelanggan' => 'Nadia A.', 'layanan' => 'Cuci & Setrika', 'total' => null,  'status' => 'Perlu Konfirmasi',    'lunas' => false],
        ['id' => 'SW-00130', 'pelanggan' => 'Rafi F.',  'layanan' => 'Setrika Saja',   'total' => 20000, 'status' => 'Menunggu Pickup',     'lunas' => false],
        ['id' => 'SW-00129', 'pelanggan' => 'Dina P.',  'layanan' => 'Cuci Kering',    'total' => 45000, 'status' => 'Diproses',            'lunas' => false],
        ['id' => 'SW-00128', 'pelanggan' => 'Andi S.',  'layanan' => 'Cuci & Setrika', 'total' => 30000, 'status' => 'Menunggu Pembayaran', 'lunas' => false],
        ['id' => 'SW-00127', 'pelanggan' => 'Maya K.',  'layanan' => 'Cuci Kering',    'total' => 40000, 'status' => 'Selesai',             'lunas' => true],
    ];
    $rp = fn ($n) => 'Rp' . number_format($n, 0, ',', '.');

    $totalPesanan = count($pesanan);
    $selesai      = collect($pesanan)->where('status', 'Selesai')->count();
    $belumSelesai = $totalPesanan - $selesai;
    $pendapatan   = collect($pesanan)->where('lunas', true)->sum('total');
    $notif        = 3;

    $tanggalHariIni = now()->locale('id')->translatedFormat('l, d F Y');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Shift | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .shift-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 16px; }
        .shift-actions button:disabled { opacity: .45; cursor: not-allowed; }
        .shift-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-weight: 600; font-size: 13px; background: #e5e7eb; color: #374151; }
        .shift-badge.open { background: #dcfce7; color: #166534; }
        .shift-badge.closed { background: #fee2e2; color: #991b1b; }
        .report-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin: 16px 0; }
        .report-meta div { padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; }
        .report-meta small { display: block; color: #6b7280; margin-bottom: 4px; }
        .report-meta strong { font-size: 18px; }
        .report-title { display: none; }

        @media print {
            .sidebar, .admin-topbar, .welcome-section, .no-print, .admin-footer, .demo-note { display: none !important; }
            .admin-layout { display: block !important; }
            .admin-main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .panel { box-shadow: none !important; border: none !important; }
            .report-title { display: block; margin-bottom: 8px; }
        }
    </style>
</head>
<body>
<div class="admin-layout">
    @include('admin.sidebar', ['active' => 'shift'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Manajemen Shift</h1></div>
            <div class="topbar-actions">
                <a href="{{ url('/admin/dashboard') }}#perlu-tindakan" class="icon-button" aria-label="Pesanan perlu tindakan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @if ($notif > 0)
                        <span class="notif-count">{{ $notif }}</span>
                    @endif
                </a>
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">SHIFT HARI INI</p>
                <h2>Buka &amp; Tutup Toko</h2>
                <p>{{ $tanggalHariIni }}. Catat jam buka dan tutup, lalu cetak laporan harian setelah toko ditutup.</p>
            </div>
        </section>

        <section class="panel no-print">
            <div class="panel-heading"><div><span class="panel-eyebrow">STATUS TOKO</span><h3>Kontrol Shift</h3></div><span id="shiftBadge" class="shift-badge">Belum dibuka</span></div>
            <div class="shift-actions">
                <button type="button" id="btnBuka" class="primary-button">Buka Toko</button>
                <button type="button" id="btnTutup" class="btn btn-ghost" disabled>Tutup Toko</button>
                <button type="button" id="btnCetak" class="btn btn-ghost" disabled>Cetak Laporan Harian</button>
            </div>
            <p class="demo-note" id="shiftHint">Tekan "Buka Toko" untuk memulai shift hari ini.</p>
        </section>

        <section class="panel" id="laporanHarian">
            <h2 class="report-title">SWASH Laundry Service: Laporan Harian</h2>
            <div class="panel-heading"><div><span class="panel-eyebrow">{{ strtoupper($tanggalHariIni) }}</span><h3>Laporan Harian</h3></div></div>

            <div class="report-meta">
                <div><small>Jam Buka</small><strong id="repBuka">-</strong></div>
                <div><small>Jam Tutup</small><strong id="repTutup">-</strong></div>
                <div><small>Total Pesanan</small><strong>{{ $totalPesanan }}</strong></div>
                <div><small>Selesai</small><strong>{{ $selesai }}</strong></div>
                <div><small>Belum Selesai</small><strong>{{ $belumSelesai }}</strong></div>
                <div><small>Pendapatan Lunas</small><strong>{{ $rp($pendapatan) }}</strong></div>
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Layanan</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            <tr>
                                <td class="order-id">{{ $p['id'] }}</td>
                                <td>{{ $p['pelanggan'] }}</td>
                                <td>{{ $p['layanan'] }}</td>
                                <td>{{ $p['total'] ? $rp($p['total']) : '—' }}</td>
                                <td>{{ $p['status'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Tidak ada pesanan hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="demo-note">Data masih contoh. Jam buka/tutup sementara disimpan di browser dan reset otomatis tiap hari; nanti dipindah ke database.</p>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>

<script>
    (function () {
        var KEY = 'swash_shift_{{ now()->format('Y-m-d') }}';
        var btnBuka = document.getElementById('btnBuka');
        var btnTutup = document.getElementById('btnTutup');
        var btnCetak = document.getElementById('btnCetak');
        var badge = document.getElementById('shiftBadge');
        var hint = document.getElementById('shiftHint');

        function load() {
            try { return JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { return {}; }
        }
        function save(s) {
            try { localStorage.setItem(KEY, JSON.stringify(s)); } catch (e) {}
        }
        function jam() {
            return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(':', '.');
        }

        function render() {
            var s = load();
            document.getElementById('repBuka').textContent = s.buka || '-';
            document.getElementById('repTutup').textContent = s.tutup || '-';

            btnBuka.disabled = !!s.buka;
            btnTutup.disabled = !s.buka || !!s.tutup;
            btnCetak.disabled = !s.tutup;

            badge.className = 'shift-badge';
            if (!s.buka) {
                badge.textContent = 'Belum dibuka';
                hint.textContent = 'Tekan "Buka Toko" untuk memulai shift hari ini.';
            } else if (!s.tutup) {
                badge.textContent = 'Toko buka sejak ' + s.buka;
                badge.classList.add('open');
                hint.textContent = 'Shift berjalan. Tekan "Tutup Toko" setelah semua pekerjaan hari ini selesai.';
            } else {
                badge.textContent = 'Toko tutup ' + s.tutup;
                badge.classList.add('closed');
                hint.textContent = 'Shift hari ini selesai. Silakan cetak laporan harian.';
            }
        }

        btnBuka.addEventListener('click', function () {
            var s = load();
            s.buka = jam();
            save(s);
            render();
        });
        btnTutup.addEventListener('click', function () {
            if (!confirm('Tutup toko sekarang?')) return;
            var s = load();
            s.tutup = jam();
            save(s);
            render();
        });
        btnCetak.addEventListener('click', function () { window.print(); });

        render();
    })();
</script>
</body>
</html>