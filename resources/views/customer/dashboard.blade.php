@php
    $name = auth()->user()->name ?? 'Pelanggan';
    $firstName = explode(' ', trim($name))[0];

    $aktif = $orders->where('status', '!=', 'selesai')->values();

    $order = [
        'kode' => 'SW-00128',
        'status' => 'menunggu_pembayaran',
        'layanan' => 'Cuci & Setrika',
        'tipe' => 'Antar Jemput',
        'jadwal' => 'Jumat, 23 Okt 2026 · 09.00–10.00 WIB',
        'alamat' => 'Jl. Contoh No. 12, Kel. Contoh',
        'berat' => 3.5,
        'tagihan' => 35000,
        'estimasi_selesai' => '25 Okt 2026',
        'driver' => ['nama' => 'Budi S.', 'kendaraan' => 'Motor · B 1234 XX', 'telepon' => '081234567890'],
    ];

    $rp = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');
    $kg = number_format($order['berat'], 1, ',', '.') . ' kg';

    $steps = ['Dipesan', 'Dijadwalkan', 'Penjemputan', 'Pembayaran', 'Diproses', 'Pengantaran', 'Selesai'];

    // step = jumlah tahap yang sudah selesai (7 = semua selesai)
    $statusMap = [
        'menunggu_konfirmasi' => ['step' => 0, 'label' => 'Menunggu Konfirmasi', 'class' => 'status-waiting', 'hint' => 'Admin sedang mengecek pesananmu. Kamu akan dapat notifikasi saat jadwal dan jam kedatangan driver sudah dikonfirmasi.'],
        'dijadwalkan' => ['step' => 1, 'label' => 'Dijadwalkan', 'class' => 'status-scheduled', 'hint' => 'Jadwal sudah dikonfirmasi. Siapkan cucianmu dan pastikan HP aktif saat driver tiba.'],
        'driver_menuju' => ['step' => 2, 'label' => 'Driver Menuju Lokasi', 'class' => 'status-onway', 'hint' => 'Driver sedang menuju lokasimu. Cucian akan ditimbang di tempat.'],
        'menunggu_pembayaran' => ['step' => 3, 'label' => 'Menunggu Pembayaran', 'class' => 'status-payment', 'hint' => 'Cucian sudah ditimbang. Pesanan mulai diproses setelah pembayaran dikonfirmasi.'],
        'diproses' => ['step' => 4, 'label' => 'Diproses', 'class' => 'status-processing', 'hint' => 'Cucianmu sedang dikerjakan, biasanya 1–2 hari.'],
        'dalam_pengantaran' => ['step' => 5, 'label' => 'Dalam Pengantaran', 'class' => 'status-delivery', 'hint' => 'Driver sedang mengantar cucianmu. Pantau posisinya di peta.'],
        'selesai' => ['step' => 7, 'label' => 'Selesai', 'class' => 'status-complete', 'hint' => 'Pesanan selesai. Terima kasih sudah memakai SWASH!'],
    ];
    $status = $statusMap[$order['status']];
    $stepIndex = $status['step'];

    // Class warna status untuk tabel riwayat (label diambil dari $o->status_label)
    $riwayatStatus = [
        'menunggu_pickup'     => 'status-scheduled',
        'menunggu_konfirmasi' => 'status-waiting',
        'menunggu_pembayaran' => 'status-payment',
        'diproses'            => 'status-processing',
        'diantar'             => 'status-delivery',
        'selesai'             => 'status-complete',
    ];

    $stats = [
        ['Pesanan Aktif', '1', 'Sedang berjalan', ''],
        ['Jadwal Terdekat', '23 Okt', '09.00–10.00 WIB', 'stat-money'],
        ['Perlu Dibayar', $rp($order['tagihan']), '1 pesanan menunggu', 'stat-money'],
        ['Total Pesanan', '12', 'Sejak bergabung', ''],
    ];

    $detail = [
        'Layanan' => $order['layanan'],
        'Jenis' => $order['tipe'],
        'Jadwal' => $order['jadwal'],
        'Alamat' => $order['alamat'],
    ];
    if ($stepIndex >= 3) {
        $detail['Berat'] = $kg;
        $detail['Tagihan'] = $rp($order['tagihan']);
    }

    $layanan = [
        ['nama' => 'Cuci & Setrika', 'desc' => 'Dicuci bersih lalu disetrika rapi, siap dipakai.', 'harga' => 'Rp10.000/kg', 'slug' => 'cuci-setrika'],
        ['nama' => 'Cuci Kering', 'desc' => 'Dicuci dan dikeringkan, tanpa setrika.', 'harga' => 'Rp8.000/kg', 'slug' => 'cuci-kering'],
        ['nama' => 'Setrika Saja', 'desc' => 'Punya baju bersih yang butuh dirapikan.', 'harga' => 'Rp6.000/kg', 'slug' => 'setrika-saja'],
    ];
@endphp
@extends('layouts.customer')

@section('title', 'Dashboard')

@push('styles')
    @if ($order['status'] === 'dalam_pengantaran')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    @endif
    <style>
        .service-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 16px; }
        .service-card { display: flex; flex-direction: column; gap: 6px; padding: 18px; border: 1px solid #e4ecee; border-radius: 12px; background: #fff; color: #12303f; text-decoration: none; }
        .service-card:hover { border-color: #0a9e9b; }
        .service-card p { margin: 0; color: #6b7f8c; font-size: 14px; }
        .service-card span { font-size: 13px; color: #6b7f8c; }
        .service-card .service-cta { margin-top: auto; padding-top: 8px; color: #087f81; font-weight: 600; }
    </style>
@endpush

@section('content')
    <header class="admin-topbar">
        <div>
            <span class="topbar-label">SWASH / PELANGGAN</span>
            <h1>Dashboard</h1>
        </div>
        <div class="topbar-actions">
            <a href="#notifikasi" class="icon-button" aria-label="Notifikasi">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span class="notif-count">1</span>
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="flash" role="status">{{ session('success') }}</div>
    @endif

    <section class="welcome-section">
        <div>
            <p class="welcome-label">SELAMAT DATANG</p>
            <h2>Halo, {{ $firstName }}!</h2>
            <p>Cucian numpuk? Buat pesanan, kami yang urus sisanya.</p>
        </div>
        <a href="{{ route('customer.orders.create') }}" class="primary-button">Buat Pesanan</a>
    </section>

    <section class="stats-grid">
        @foreach ($stats as [$judul, $nilai, $ket, $kelas])
            <article class="stat-card">
                <div class="stat-heading"><span>{{ $judul }}</span></div>
                <strong class="stat-value {{ $kelas }}">{{ $nilai }}</strong>
                <p>{{ $ket }}</p>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading">
                <div><span class="panel-eyebrow">PESANAN AKTIF</span><h3>Lacak Pesananmu</h3></div>
                <span class="order-status {{ $status['class'] }}">{{ $status['label'] }}</span>
            </div>

            <div class="order-summary">
                <div>
                    <strong>{{ $order['kode'] }}</strong>
                    <span>{{ $order['layanan'] }} · {{ $order['tipe'] }}</span>
                </div>
                <div>
                    <span>{{ $stepIndex >= 3 ? 'Estimasi selesai' : 'Jadwal' }}</span>
                    <strong>{{ $stepIndex >= 3 ? $order['estimasi_selesai'] : $order['jadwal'] }}</strong>
                </div>
            </div>

            <ol class="tracker">
                @foreach ($steps as $i => $label)
                    <li class="tracker-step {{ $i < $stepIndex ? 'done' : ($i === $stepIndex ? 'current' : '') }}">
                        <div class="tracker-dot">{{ $i < $stepIndex ? '✓' : $i + 1 }}</div>
                        <strong>{{ $label }}</strong>
                    </li>
                @endforeach
            </ol>

            <p class="status-hint">{{ $status['hint'] }}</p>

            @if ($order['status'] === 'menunggu_pembayaran')
                <div class="bill-card">
                    <div class="bill-top">
                        <div><span>Berat cucian</span><strong>{{ $kg }}</strong></div>
                        <div><span>Estimasi tagihan</span><strong>{{ $rp($order['tagihan']) }}</strong></div>
                    </div>
                    <a href="{{ route('customer.payments.index') }}" class="btn btn-primary">Bayar Sekarang</a>
                </div>
            @endif

            @if (in_array($order['status'], ['driver_menuju', 'dalam_pengantaran']))
                <div class="driver-card">
                    <div class="driver-avatar">{{ strtoupper(substr($order['driver']['nama'], 0, 1)) }}</div>
                    <div class="driver-info">
                        <strong>{{ $order['driver']['nama'] }}</strong>
                        <small>{{ $order['driver']['kendaraan'] }}</small>
                    </div>
                    <div class="driver-actions">
                        <a href="#" class="btn btn-ghost btn-sm">Chat</a>
                        <a href="tel:{{ $order['driver']['telepon'] }}" class="btn btn-primary btn-sm">Telepon</a>
                    </div>
                </div>
            @endif
        </article>

        <article class="panel">
            <div class="panel-heading"><div><span class="panel-eyebrow">DETAIL</span><h3>Detail Pesanan</h3></div></div>
            <div class="detail-list">
                @foreach ($detail as $label => $isi)
                    <div class="detail-row"><span>{{ $label }}</span><strong>{{ $isi }}</strong></div>
                @endforeach
            </div>
        </article>
    </section>

    @if ($order['status'] === 'dalam_pengantaran')
        <section class="panel">
            <div class="panel-heading"><div><span class="panel-eyebrow">PENGANTARAN</span><h3>Posisi Driver</h3></div></div>
            <div id="map" class="map-box"></div>
            <p class="demo-note">Posisi pada peta masih contoh.</p>
        </section>
    @endif

    <section class="panel">
        <div class="panel-heading"><div><span class="panel-eyebrow">LAYANAN</span><h3>Mau Cuci Apa Hari Ini?</h3></div></div>
        <div class="service-grid">
            @foreach ($layanan as $l)
                <a href="{{ route('customer.orders.create', ['layanan' => $l['slug']]) }}" class="service-card">
                    <strong>{{ $l['nama'] }}</strong>
                    <p>{{ $l['desc'] }}</p>
                    <span>{{ $l['harga'] }}</span>
                    <span class="service-cta">Pilih layanan</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="panel orders-panel">
        <div class="panel-heading orders-heading">
            <div><span class="panel-eyebrow">PERLU TINDAK LANJUT</span><h3>Pesanan Berjalan</h3></div>
            <a href="{{ route('customer.orders.index') }}" class="text-action">Semua Pesanan</a>
        </div>

        @if ($aktif->isEmpty())
            <p class="status-hint">Tidak ada pesanan yang perlu ditindaklanjuti saat ini.</p>
        @else
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr><th>ID Pesanan</th><th>Layanan</th><th>Jenis</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($aktif as $o)
                            @php
                                $statusClass = $riwayatStatus[$o->status] ?? '';
                            @endphp
                            <tr>
                                <td class="order-id">{{ $o->code }}</td>
                                <td>{{ $o->service_name }}</td>
                                <td>{{ $o->delivery_label }}</td>
                                <td>{{ ($o->pickup_date ?? $o->created_at)->translatedFormat('d M Y') }}</td>
                                <td>{{ $o->price ? $rp($o->price) : 'Belum ditentukan' }}</td>
                                <td><span class="order-status {{ $statusClass }}">{{ $o->status_label }}</span></td>
                                <td>
                                    @if ($o->status === 'menunggu_pembayaran')
                                        <a href="{{ route('customer.payments.index') }}" class="btn btn-primary btn-sm">Bayar</a>
                                    @else
                                        <a href="{{ route('customer.orders.show', $o->id) }}" class="text-action">Detail</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    @if ($order['status'] === 'dalam_pengantaran')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            const posisi = [-6.2088, 106.8456];
            const peta = L.map('map').setView(posisi, 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(peta);
            L.marker(posisi).addTo(peta).bindPopup('Driver sedang menuju lokasimu').openPopup();
        </script>
    @endif
@endpush