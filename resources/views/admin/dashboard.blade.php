@php
    use Illuminate\Support\Str;

    $statusMap = [
        'waiting_acc' => ['label' => 'Perlu Konfirmasi',    'class' => 'status-waiting',    'grup' => 'waiting',  'aksi' => 'ACC & Atur Jam'],
        'weighed'     => ['label' => 'Sudah Ditimbang',     'class' => 'status-onway',      'grup' => 'process',  'aksi' => 'Input Berat'],
        'pickup'      => ['label' => 'Menunggu Pickup',     'class' => 'status-scheduled',  'grup' => 'waiting',  'aksi' => null],
        'payment'     => ['label' => 'Menunggu Pembayaran', 'class' => 'status-payment',    'grup' => 'waiting',  'aksi' => null],
        'processing'  => ['label' => 'Diproses',            'class' => 'status-processing', 'grup' => 'process',  'aksi' => null],
        'delivery'    => ['label' => 'Dalam Pengantaran',   'class' => 'status-onway',      'grup' => 'delivery', 'aksi' => null],
        'complete'    => ['label' => 'Selesai',             'class' => 'status-complete',   'grup' => 'complete', 'aksi' => null],
    ];

    $pesanan = [
        ['id' => 'SW-00131', 'pelanggan' => 'Nadia A.', 'jenis' => 'Antar Jemput', 'layanan' => 'Cuci & Setrika', 'hari' => 1,  'total' => null,  'status' => 'waiting_acc'],
        ['id' => 'SW-00130', 'pelanggan' => 'Rafi F.',  'jenis' => 'Antar Saja',   'layanan' => 'Setrika Saja',   'hari' => 1,  'total' => null,  'status' => 'waiting_acc'],
        ['id' => 'SW-00129', 'pelanggan' => 'Dina P.',  'jenis' => 'Antar Jemput', 'layanan' => 'Cuci Kering',    'hari' => 0,  'total' => 45000, 'status' => 'weighed'],
        ['id' => 'SW-00128', 'pelanggan' => 'Tono W.',  'jenis' => 'Antar Jemput', 'layanan' => 'Setrika Saja',   'hari' => 1,  'total' => 20000, 'status' => 'pickup'],
        ['id' => 'SW-00127', 'pelanggan' => 'Andi S.',  'jenis' => 'Antar Jemput', 'layanan' => 'Cuci & Setrika', 'hari' => -1, 'total' => 30000, 'status' => 'payment'],
        ['id' => 'SW-00126', 'pelanggan' => 'Sari L.',  'jenis' => 'Antar Saja',   'layanan' => 'Cuci Kering',    'hari' => 0,  'total' => 40000, 'status' => 'processing'],
        ['id' => 'SW-00125', 'pelanggan' => 'Budi H.',  'jenis' => 'Antar Jemput', 'layanan' => 'Cuci & Setrika', 'hari' => 0,  'total' => 35000, 'status' => 'delivery'],
        ['id' => 'SW-00120', 'pelanggan' => 'Maya K.',  'jenis' => 'Antar Saja',   'layanan' => 'Cuci Kering',    'hari' => -2, 'total' => 40000, 'status' => 'complete'],
        ['id' => 'SW-00119', 'pelanggan' => 'Joko P.',  'jenis' => 'Antar Jemput', 'layanan' => 'Cuci & Setrika', 'hari' => -3, 'total' => 50000, 'status' => 'complete'],
        ['id' => 'SW-00118', 'pelanggan' => 'Lina R.',  'jenis' => 'Antar Saja',   'layanan' => 'Setrika Saja',   'hari' => -3, 'total' => 25000, 'status' => 'complete'],
    ];
    $semua = collect($pesanan);

    $perluTindakan   = $semua->filter(fn ($p) => $statusMap[$p['status']]['aksi'] !== null)->values();
    $jumlahTindakan  = $perluTindakan->count();
    $perluKonfirmasi = $semua->where('status', 'waiting_acc')->count();
    $perluInputBerat = $semua->where('status', 'weighed')->count();
    $menungguBayar   = $semua->where('status', 'payment')->count();
    $pendapatan      = $semua->where('status', 'complete')->sum('total');

    $rp = fn ($n) => 'Rp' . number_format($n, 0, ',', '.');
    $rpRingkas = fn ($n) => $n >= 1000000 ? 'Rp' . number_format($n / 1000000, 1, ',', '.') . ' jt' : $rp($n);
    $inisial = fn ($nama) => strtoupper(Str::of($nama)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    $tgl = fn ($hari) => now()->addDays($hari)->locale('id')->translatedFormat('d M Y');

    // Ringkasan status (dihitung dari data yang sama)
    $labelGrup = [
        'waiting'  => 'Menunggu (konfirmasi / pickup / bayar)',
        'process'  => 'Diproses',
        'delivery' => 'Dalam Pengantaran',
        'complete' => 'Selesai',
    ];
    $ringkasanStatus = [];
    foreach ($labelGrup as $kunci => $label) {
        $ringkasanStatus[$kunci] = [
            'label'  => $label,
            'jumlah' => $semua->filter(fn ($p) => $statusMap[$p['status']]['grup'] === $kunci)->count(),
        ];
    }
    $totalPesanan = max($semua->count(), 1);

    $terbaru = $semua->take(5);
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
    @include('admin.sidebar', ['active' => 'dashboard'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Dashboard</h1></div>
            <div class="topbar-actions">
                <a href="#perlu-tindakan" class="icon-button" aria-label="Pesanan perlu tindakan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @if ($jumlahTindakan > 0)
                        <span class="notif-count">{{ $jumlahTindakan }}</span>
                    @endif
                </a>
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">SELAMAT DATANG</p>
                <h2>Halo, Admin.</h2>
                @if ($jumlahTindakan > 0)
                    <p>Ada {{ $jumlahTindakan }} pesanan yang menunggu tindakanmu: {{ $perluKonfirmasi }} perlu dikonfirmasi dan {{ $perluInputBerat }} perlu input berat.</p>
                @else
                    <p>Tidak ada pesanan yang menunggu tindakanmu saat ini.</p>
                @endif
            </div>
        </section>

        <section class="stats-grid" aria-label="Ringkasan statistik">
            <article class="stat-card"><div class="stat-heading"><span>Perlu Dikonfirmasi</span><span class="stat-index">01</span></div><strong class="stat-value">{{ $perluKonfirmasi }}</strong><p>Pesanan baru menunggu ACC</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Perlu Input Berat</span><span class="stat-index">02</span></div><strong class="stat-value">{{ $perluInputBerat }}</strong><p>Sudah ditimbang driver</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Menunggu Pembayaran</span><span class="stat-index">03</span></div><strong class="stat-value">{{ $menungguBayar }}</strong><p>Belum dibayar pelanggan</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pendapatan Lunas</span><span class="stat-index">04</span></div><strong class="stat-value stat-money">{{ $rpRingkas($pendapatan) }}</strong><p>Dari pesanan yang sudah selesai</p></article>
        </section>

        <section class="dashboard-grid" id="perlu-tindakan">
            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">ANTRIAN</span><h3>Perlu Tindakan</h3></div><a href="{{ url('/admin/pesanan') }}" class="text-action">Semua Pesanan</a></div>
                <div class="table-responsive">
                    <table class="orders-table compact">
                        <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Jenis</th><th>Perlu</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($perluTindakan as $p)
                                @php $s = $statusMap[$p['status']]; @endphp
                                <tr>
                                    <td class="order-id">{{ $p['id'] }}</td>
                                    <td>{{ $p['pelanggan'] }}</td>
                                    <td>{{ $p['jenis'] }}</td>
                                    <td><span class="order-status {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                                    <td><a href="#" class="btn btn-primary btn-sm">{{ $s['aksi'] }}</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Semua pesanan sudah ditangani.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">HARI INI</span><h3>Jadwal Driver</h3></div><a href="{{ url('/admin/jadwal-pickup') }}" class="text-action">Semua</a></div>
                <div class="schedule-list">
                    <div class="schedule-item"><span class="schedule-time">09.00</span><div><strong>Jemput · Nadia A.</strong><small>Driver: Bagas · Jl. Contoh No. 12</small></div></div>
                    <div class="schedule-item"><span class="schedule-time">11.00</span><div><strong>Antar · Dina P.</strong><small>Driver: Rizky · Jl. Contoh No. 7</small></div></div>
                    <div class="schedule-item"><span class="schedule-time">14.00</span><div><strong>Jemput · Rafi F.</strong><small>Driver: Bagas · Jl. Contoh No. 3</small></div></div>
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
                            @foreach ($terbaru as $p)
                                @php $s = $statusMap[$p['status']]; @endphp
                                <tr>
                                    <td class="order-id">{{ $p['id'] }}</td>
                                    <td><div class="customer-cell"><span class="customer-avatar">{{ $inisial($p['pelanggan']) }}</span><span>{{ $p['pelanggan'] }}</span></div></td>
                                    <td>{{ $p['layanan'] }}</td>
                                    <td>{{ $tgl($p['hari']) }}</td>
                                    <td>{{ $p['total'] ? $rp($p['total']) : '—' }}</td>
                                    <td><span class="order-status {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                                </tr>
                            @endforeach
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
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>