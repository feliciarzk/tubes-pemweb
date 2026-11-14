@php
    $name = auth()->user()->name ?? 'Pelanggan';
    $firstName = explode(' ', trim($name))[0];

    $aktif = $orders->where('status', '!=', 'selesai')->values();

    $order = [
        'kode' => 'SW-00128',
        'status' => 'menunggu_pembayaran',
        'layanan' => 'Cuci & Setrika',
        'tipe' => 'Antar Jemput',
        'tanggal' => 'Selasa, 17 Nov 2026',
        'tanggal_singkat' => '17 Nov',
        'jam' => '09.00–10.00 WIB',
        'alamat' => 'Jl. Contoh No. 12, Kel. Contoh',
        'berat' => 3.5,
        'tagihan' => 35000,
        'estimasi_selesai' => '19 Nov 2026',
        'driver' => ['nama' => 'Budi S.', 'kendaraan' => 'Motor · B 1234 XX', 'telepon' => '081915854566'],
    ];
    $order['jadwal'] = $order['tanggal'] . ' · ' . $order['jam'];

    $rp = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');
    $kg = number_format($order['berat'], 1, ',', '.') . ' kg';

    $steps = ['Dipesan', 'Dijadwalkan', 'Penjemputan', 'Pembayaran', 'Diproses', 'Pengantaran', 'Selesai'];

    // step = jumlah tahap yang sudah selesai (7 = semua selesai)
    $statusMap = [
        'menunggu_konfirmasi' => ['step' => 0, 'label' => 'Menunggu Konfirmasi', 'class' => 'status-waiting', 'hint' => 'Pesananmu sedang dicek admin.'],
        'dijadwalkan' => ['step' => 1, 'label' => 'Dijadwalkan', 'class' => 'status-scheduled', 'hint' => 'Jadwal dikonfirmasi. Siapkan cucianmu.'],
        'driver_menuju' => ['step' => 2, 'label' => 'Driver Menuju Lokasi', 'class' => 'status-onway', 'hint' => 'Driver sedang menuju lokasimu.'],
        'menunggu_pembayaran' => ['step' => 3, 'label' => 'Menunggu Pembayaran', 'class' => 'status-payment', 'hint' => 'Cucian sudah ditimbang. Bayar untuk mulai diproses.'],
        'diproses' => ['step' => 4, 'label' => 'Diproses', 'class' => 'status-processing', 'hint' => 'Cucianmu sedang dikerjakan.'],
        'dalam_pengantaran' => ['step' => 5, 'label' => 'Dalam Pengantaran', 'class' => 'status-delivery', 'hint' => 'Driver sedang mengantar cucianmu.'],
        'selesai' => ['step' => 7, 'label' => 'Selesai', 'class' => 'status-complete', 'hint' => 'Pesanan selesai. Terima kasih!'],
    ];
    $status = $statusMap[$order['status']];
    $stepIndex = $status['step'];

    $riwayatStatus = [
        'menunggu_pickup'     => 'status-scheduled',
        'menunggu_konfirmasi' => 'status-waiting',
        'menunggu_pembayaran' => 'status-payment',
        'diproses'            => 'status-processing',
        'diantar'             => 'status-delivery',
        'selesai'             => 'status-complete',
    ];

    $stats = [
        ['Pesanan Aktif', '1', '', ''],
        ['Jadwal Terdekat', $order['tanggal_singkat'], $order['jam'], 'stat-money'],
        ['Perlu Dibayar', $rp($order['tagihan']), '', 'stat-money'],
        ['Total Pesanan', '12', '', ''],
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

    $notifikasi = [
        ['tipe' => 'tagihan',     'judul' => 'Cucian sudah ditimbang',  'pesan' => $order['kode'] . ' · ' . $kg . '. Tagihan ' . $rp($order['tagihan']) . ' menunggu pembayaran.', 'waktu' => 'Hari ini, 08.15', 'baru' => true],
        ['tipe' => 'jadwal',      'judul' => 'Jadwal dikonfirmasi',     'pesan' => 'Penjemputan ' . $order['jadwal'] . '.', 'waktu' => 'Kemarin, 16.40', 'baru' => false],
        ['tipe' => 'dibuat',      'judul' => 'Pesanan berhasil dibuat', 'pesan' => $order['kode'] . ' · ' . $order['layanan'] . ', ' . $order['tipe'] . '.', 'waktu' => 'Kemarin, 14.05', 'baru' => false],
        ['tipe' => 'selesai',     'judul' => 'Pesanan selesai',         'pesan' => 'SW-00127 sudah diantar. Terima kasih!', 'waktu' => '12 Nov, 18.30', 'baru' => false],
        ['tipe' => 'dibayar',     'judul' => 'Pembayaran berhasil',     'pesan' => 'Pesanan SW-00127 sudah dibayar Rp28.000.', 'waktu' => '12 Nov, 10.22', 'baru' => false],
    ];
    $belumDibaca = count(array_filter($notifikasi, fn ($n) => $n['baru']));

    $notifIkon = [
        'tagihan' => '<path d="M4 4h16v16l-3-2-3 2-2-2-2 2-3-2-3 2z"/><path d="M8 9h8M8 13h5"/>',
        'jadwal'  => '<rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'dibuat'  => '<path d="M12 5v14M5 12h14"/>',
        'dibayar' => '<rect x="2" y="5" width="20" height="14" rx="3"/><path d="M2 10h20"/>',
        'selesai' => '<path d="M20 6 9 17l-5-5"/>',
    ];
@endphp
@extends('layouts.customer')

@section('title', 'Dashboard')

@push('styles')
    @if ($order['status'] === 'dalam_pengantaran')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    @endif
@endpush

@section('content')
    <header class="admin-topbar">
        <h1>Dashboard</h1>
        <div class="topbar-actions notif">
            <button type="button" class="icon-button" id="notif-toggle" aria-label="Notifikasi" aria-haspopup="true" aria-expanded="false" aria-controls="notif-panel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span class="notif-count" @if (! $belumDibaca) hidden @endif>{{ $belumDibaca }}</span>
            </button>

            <div class="notif-panel" id="notif-panel" hidden>
                <div class="notif-head">
                    <strong>Notifikasi</strong>
                    <button type="button" class="text-action" id="notif-read">Tandai dibaca</button>
                </div>
                <ul class="notif-list">
                    @foreach ($notifikasi as $n)
                        <li class="notif-item {{ $n['baru'] ? 'unread' : '' }}">
                            <span class="notif-icon notif-{{ $n['tipe'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $notifIkon[$n['tipe']] !!}</svg>
                            </span>
                            <div>
                                <strong>{{ $n['judul'] }}</strong>
                                <p>{{ $n['pesan'] }}</p>
                                <small>{{ $n['waktu'] }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="flash" role="status">{{ session('success') }}</div>
    @endif

    <section class="welcome-section">
        <h2>Halo, {{ $firstName }}!</h2>
        <a href="{{ route('customer.orders.create') }}" class="primary-button">Buat Pesanan</a>
    </section>

    <section class="stats-grid">
        @foreach ($stats as [$judul, $nilai, $ket, $kelas])
            <article class="stat-card">
                <div class="stat-heading"><span>{{ $judul }}</span></div>
                <strong class="stat-value {{ $kelas }}">{{ $nilai }}</strong>
                @if ($ket)<p>{{ $ket }}</p>@endif
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading">
                <div><h3>Lacak Pesananmu</h3></div>
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
                        <div><span>Total tagihan</span><strong>{{ $rp($order['tagihan']) }}</strong></div>
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
                        <a href="https://wa.me/62{{ ltrim($order['driver']['telepon'], '0') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">Chat</a>
                        <a href="tel:{{ $order['driver']['telepon'] }}" class="btn btn-primary btn-sm">Telepon</a>
                    </div>
                </div>
            @endif
        </article>

        <article class="panel">
            <div class="panel-heading"><div><h3>Detail Pesanan</h3></div></div>
            <div class="detail-list">
                @foreach ($detail as $label => $isi)
                    <div class="detail-row"><span>{{ $label }}</span><strong>{{ $isi }}</strong></div>
                @endforeach
            </div>
        </article>
    </section>

    @if ($order['status'] === 'dalam_pengantaran')
        <section class="panel">
            <div class="panel-heading"><div><h3>Posisi Driver</h3></div></div>
            <div id="map" class="map-box"></div>
        </section>
    @endif


    <section class="panel orders-panel">
        <div class="panel-heading orders-heading">
            <div><h3>Pesanan Berjalan</h3></div>
            <a href="{{ route('customer.orders.index') }}" class="text-action">Semua Pesanan</a>
        </div>

        @if ($aktif->isEmpty())
            <p class="status-hint">Belum ada pesanan berjalan.</p>
        @else
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr><th>ID Pesanan</th><th>Layanan</th><th>Jenis</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($aktif as $o)
                            <tr>
                                <td class="order-id">{{ $o->code }}</td>
                                <td>{{ $o->service_name }}</td>
                                <td>{{ $o->delivery_label }}</td>
                                <td>{{ \Carbon\Carbon::parse($o->pickup_date ?? $o->created_at)->translatedFormat('d M Y') }}</td>
                                <td>{{ $o->price ? $rp($o->price) : 'Belum ditentukan' }}</td>
                                <td><span class="order-status {{ $riwayatStatus[$o->status] ?? '' }}">{{ $o->status_label }}</span></td>
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
    <script>
        (() => {
            const toggle = document.getElementById('notif-toggle');
            const panel = document.getElementById('notif-panel');
            const badge = toggle.querySelector('.notif-count');
            const setOpen = open => {
                panel.hidden = !open;
                toggle.setAttribute('aria-expanded', open);
            };
            toggle.addEventListener('click', e => { e.stopPropagation(); setOpen(panel.hidden); });
            panel.addEventListener('click', e => e.stopPropagation());
            document.addEventListener('click', () => setOpen(false));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') setOpen(false); });
            document.getElementById('notif-read').addEventListener('click', () => {
                panel.querySelectorAll('.unread').forEach(el => el.classList.remove('unread'));
                if (badge) badge.hidden = true;
            });
        })();
    </script>
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