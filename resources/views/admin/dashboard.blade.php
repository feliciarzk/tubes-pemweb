
@php
    $statusMap = [
        'waiting_acc' => ['label'=>'Perlu Konfirmasi','class'=>'status-waiting','grup'=>'tindakan','aksi'=>'ACC & Atur Jam'],
        'pickup' => ['label'=>'Menunggu Pickup','class'=>'status-scheduled','grup'=>'berjalan','aksi'=>null],
        'weighed' => ['label'=>'Sudah Ditimbang','class'=>'status-onway','grup'=>'tindakan','aksi'=>'Input Berat'],
        'payment' => ['label'=>'Menunggu Pembayaran','class'=>'status-payment','grup'=>'berjalan','aksi'=>null],
        'processing' => ['label'=>'Diproses','class'=>'status-processing','grup'=>'berjalan','aksi'=>null],
        'delivery' => ['label'=>'Dalam Pengantaran','class'=>'status-onway','grup'=>'berjalan','aksi'=>null],
        'complete' => ['label'=>'Selesai','class'=>'status-complete','grup'=>'selesai','aksi'=>null],
    ];

    $pesanan = [
        ['id'=>'SW-00131','pelanggan'=>'Nadia A.','jenis'=>'Antar Jemput','layanan'=>'Cuci & Setrika','hari'=>0,'berat'=>null,'total'=>null,'driver'=>null,'jadwal'=>null,'status'=>'waiting_acc'],
        ['id'=>'SW-00130','pelanggan'=>'Rafi F.','jenis'=>'Antar Saja','layanan'=>'Setrika Saja','hari'=>0,'berat'=>null,'total'=>null,'driver'=>null,'jadwal'=>null,'status'=>'waiting_acc'],
        ['id'=>'SW-00129','pelanggan'=>'Dina P.','jenis'=>'Antar Jemput','layanan'=>'Cuci Kering','hari'=>0,'berat'=>null,'total'=>null,'driver'=>'Sari','jadwal'=>'11.00','status'=>'weighed'],
        ['id'=>'SW-00128','pelanggan'=>'Tono W.','jenis'=>'Antar Jemput','layanan'=>'Setrika Saja','hari'=>-1,'berat'=>null,'total'=>null,'driver'=>'Deni','jadwal'=>'14.00','status'=>'pickup'],
        ['id'=>'SW-00127','pelanggan'=>'Andi S.','jenis'=>'Antar Jemput','layanan'=>'Cuci & Setrika','hari'=>-1,'berat'=>3,'total'=>30000,'driver'=>'Rizky','jadwal'=>'10.30','status'=>'payment'],
        ['id'=>'SW-00126','pelanggan'=>'Sari L.','jenis'=>'Antar Saja','layanan'=>'Cuci Kering','hari'=>-1,'berat'=>5,'total'=>40000,'driver'=>'Bagas','jadwal'=>'13.00','status'=>'processing'],
        ['id'=>'SW-00125','pelanggan'=>'Budi H.','jenis'=>'Antar Jemput','layanan'=>'Cuci & Setrika','hari'=>-2,'berat'=>3.5,'total'=>35000,'driver'=>'Rizky','jadwal'=>'15.30','status'=>'delivery'],
        ['id'=>'SW-00120','pelanggan'=>'Maya K.','jenis'=>'Antar Saja','layanan'=>'Cuci Kering','hari'=>-3,'berat'=>5,'total'=>40000,'driver'=>'Bagas','jadwal'=>'09.00','status'=>'complete'],
        ['id'=>'SW-00119','pelanggan'=>'Joko P.','jenis'=>'Antar Jemput','layanan'=>'Cuci & Setrika','hari'=>-4,'berat'=>5,'total'=>50000,'driver'=>'Deni','jadwal'=>'16.00','status'=>'complete'],
        ['id'=>'SW-00118','pelanggan'=>'Lina R.','jenis'=>'Antar Saja','layanan'=>'Setrika Saja','hari'=>-4,'berat'=>4,'total'=>24000,'driver'=>'Sari','jadwal'=>'10.00','status'=>'complete'],
    ];

    $semua = collect($pesanan)->map(function ($p) {
        $p['tanggal'] = now()->addDays($p['hari'])->locale('id')->translatedFormat('d M Y');
        return $p;
    });

    $perluTindakan = $semua->filter(fn($p) => $statusMap[$p['status']]['aksi'] !== null)->values();
    $jumlahTindakan = $perluTindakan->count();
    $perluKonfirmasi = $semua->where('status','waiting_acc')->count();
    $perluInputBerat = $semua->where('status','weighed')->count();
    $menungguBayar = $semua->where('status','payment')->count();
    $pendapatan = $semua->where('status','complete')->sum('total');
    $totalPesanan = max($semua->count(), 1);

    $berjalan = $semua->whereIn('status',['pickup','payment','processing','delivery'])->count();
    $selesai = $semua->where('status','complete')->count();

    $rp = fn($n) => 'Rp'.number_format($n,0,',','.');
    $inisial = fn($nama) => collect(explode(' ',$nama))->map(fn($w)=>mb_substr($w,0,1))->implode('');
    
    $ringkasanStatus = [
        'waiting' => ['label'=>'Menunggu','jumlah'=>$semua->whereIn('status',['waiting_acc','pickup','payment'])->count()],
        'process' => ['label'=>'Diproses','jumlah'=>$semua->whereIn('status',['weighed','processing'])->count()],
        'delivery' => ['label'=>'Dalam Pengantaran','jumlah'=>$semua->where('status','delivery')->count()],
        'complete' => ['label'=>'Selesai','jumlah'=>$selesai],
    ];

    $terbaru = $semua->take(5);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
<div class="admin-layout">

    @include('admin.sidebar', ['active'=>'dashboard'])

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <span class="topbar-label">SWASH / ADMIN</span>
                <h1>Dashboard</h1>
            </div>

            <div class="topbar-actions">
                <details class="notification-menu">
                    <summary class="icon-button" aria-label="Lihat notifikasi" title="Notifikasi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>

                        <span class="notif-count"
                              data-notification-count
                              @if($jumlahTindakan === 0) hidden @endif>
                            {{ $jumlahTindakan }}
                        </span>
                    </summary>

                    <div class="notification-dropdown">
                        <div class="notification-heading">
                            <strong>Notifikasi</strong>
                            <span><span data-notification-label>{{ $jumlahTindakan }}</span> perlu tindakan</span>
                        </div>

                        <div data-notification-items>
                            @forelse($perluTindakan as $p)
                                @php $s = $statusMap[$p['status']]; @endphp

                                <a class="notification-item" href="{{ url('/admin/pesanan') }}">
                                    <span class="notification-dot"></span>
                                    <span>
                                        <strong>{{ $p['id'] }} · {{ $p['pelanggan'] }}</strong>
                                        <small>{{ $s['label'] }} — {{ $s['aksi'] }}</small>
                                    </span>
                                </a>
                            @empty
                                <p class="notification-empty">
                                    Tidak ada pesanan yang perlu ditindaklanjuti.
                                </p>
                            @endforelse
                        </div>

                        <a class="notification-footer" href="{{ url('/admin/pesanan') }}">
                            Lihat semua pesanan
                        </a>
                    </div>
                </details>
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">SELAMAT DATANG</p>
                <h2>Halo, Admin.</h2>
                <p>
                    @if($jumlahTindakan > 0)
                        Ada {{ $jumlahTindakan }} pesanan yang perlu ditindaklanjuti.
                    @else
                        Semua pesanan sudah ditangani.
                    @endif
                </p>
            </div>
            <a href="{{ url('/admin/pesanan') }}" class="primary-button">Lihat Pesanan</a>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-heading"><span>Total Pesanan</span></div>
                <strong class="stat-value">{{ $totalPesanan }}</strong>
                <p>Seluruh pesanan tercatat</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Perlu Tindakan</span></div>
                <strong class="stat-value">{{ $jumlahTindakan }}</strong>
                <p>Konfirmasi dan input berat</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Sedang Berjalan</span></div>
                <strong class="stat-value">{{ $berjalan }}</strong>
                <p>Pickup, bayar, proses, dan antar</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Selesai</span></div>
                <strong class="stat-value">{{ $selesai }}</strong>
                <p>Pesanan telah selesai</p>
            </article>
        </section>

        <section class="dashboard-grid" id="perlu-tindakan">

            <article class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-eyebrow">ANTRIAN</span>
                        <h3>Perlu Tindakan</h3>
                    </div>
                    <a href="{{ url('/admin/pesanan') }}" class="text-action">Semua Pesanan</a>
                </div>

                <div class="table-responsive">
                    <table class="orders-table compact">
                        <thead>
                            <tr>
                                <th>Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perluTindakan as $p)
                                @php $s = $statusMap[$p['status']]; @endphp
                                <tr>
                                    <td class="order-id">{{ $p['id'] }}</td>
                                    <td>{{ $p['pelanggan'] }}</td>
                                    <td>{{ $p['jenis'] }}</td>
                                    <td>
                                        <span class="order-status {{ $s['class'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/pesanan') }}" class="text-action">
                                            {{ $s['aksi'] }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Tidak ada pesanan yang perlu ditindaklanjuti.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-eyebrow">HARI INI</span>
                        <h3>Jadwal Driver</h3>
                    </div>
                    <a href="{{ url('/admin/jadwal-pickup') }}" class="text-action">Semua</a>
                </div>

                <div class="schedule-list">
                    @forelse($semua->whereNotNull('jadwal')->take(3) as $p)
                        <div class="schedule-item">
                            <span class="schedule-time">{{ $p['jadwal'] }}</span>
                            <div>
                                <strong>{{ $p['jenis'] }} · {{ $p['pelanggan'] }}</strong>
                                <small>Driver: {{ $p['driver'] ?? 'Belum ditentukan' }}</small>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada jadwal driver.</p>
                    @endforelse
                </div>
            </article>

        </section>

        <section class="dashboard-grid" id="ringkasan">

            <article class="panel orders-panel" id="pesanan-terbaru">
                <div class="panel-heading orders-heading">
                    <div>
                        <span class="panel-eyebrow">AKTIVITAS TERBARU</span>
                        <h3>Pesanan Terbaru</h3>
                    </div>
                    <a href="{{ url('/admin/pesanan') }}" class="text-action">Semua Pesanan</a>
                </div>

                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Jadwal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($terbaru as $p)
                                @php $s = $statusMap[$p['status']]; @endphp
                                <tr>
                                    <td class="order-id">{{ $p['id'] }}</td>
                                    <td>
                                        <div class="customer-cell">
                                            <span class="customer-avatar">{{ $inisial($p['pelanggan']) }}</span>
                                            <span>{{ $p['pelanggan'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $p['layanan'] }}</td>
                                    <td>{{ $p['tanggal'] }}</td>
                                    <td>{{ $p['total'] ? $rp($p['total']) : '—' }}</td>
                                    <td>
                                        <span class="order-status {{ $s['class'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="demo-note">Data pesanan masih berupa contoh.</p>
            </article>

            <article class="panel status-panel">
                <div class="panel-heading">
                    <div>
                        <span class="panel-eyebrow">STATUS</span>
                        <h3>Status Pesanan</h3>
                    </div>
                </div>

                <div class="status-list">
                    @foreach($ringkasanStatus as $key => $item)
                        <div class="status-item">
                            <div class="status-row">
                                <span class="status-name">
                                    <span class="status-color {{ $key }}"></span>
                                    {{ $item['label'] }}
                                </span>
                                <strong>{{ $item['jumlah'] }}</strong>
                            </div>
                            <div class="status-track">
                                <span class="track-{{ $key }}"
                                      style="width:{{ ($item['jumlah'] / $totalPesanan) * 100 }}%">
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

        </section>

        <footer class="admin-footer">
            <span>SWASH Laundry Service</span>
            <span>Panel Administrasi</span>
        </footer>

    </main>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
