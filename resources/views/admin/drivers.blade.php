@php
    $drivers = [
        ['code' => 'DR-001', 'name' => 'Bagas Pratama',   'vehicle' => 'Motor', 'state' => 'bertugas', 'note' => 'Menuju lokasi pickup', 'eta' => '8 mnt'],
        ['code' => 'DR-002', 'name' => 'Rizky Maulana',   'vehicle' => 'Motor', 'state' => 'bertugas', 'note' => 'Mengantar pesanan',    'eta' => '12 mnt'],
        ['code' => 'DR-003', 'name' => 'Deni Saputra',    'vehicle' => 'Motor', 'state' => 'bertugas', 'note' => 'Menuju lokasi pickup', 'eta' => '5 mnt'],
        ['code' => 'DR-004', 'name' => 'Sari Wulandari',  'vehicle' => 'Motor', 'state' => 'tersedia', 'note' => 'Siap menerima tugas',  'eta' => null],
        ['code' => 'DR-005', 'name' => 'Hendra Kurniawan', 'vehicle' => 'Motor', 'state' => 'offline',  'note' => 'Tidak aktif',          'eta' => null],
    ];

    $stateMap = [
        'bertugas' => ['label' => 'Bertugas', 'class' => 'st-busy'],
        'tersedia' => ['label' => 'Tersedia', 'class' => 'st-free'],
        'offline'  => ['label' => 'Offline',  'class' => 'st-off'],
    ];

    $assignOrders = [
        ['code' => 'SW-00131', 'customer' => 'Nadia A.', 'type' => 'Jemput', 'when' => '09.00', 'address' => 'Jl. Contoh No. 12'],
        ['code' => 'SW-00130', 'customer' => 'Rafi F.',  'type' => 'Antar',  'when' => '11.00', 'address' => 'Jl. Contoh No. 3'],
    ];

    $pending = [
        ['order' => 'SW-00129', 'customer' => 'Dina P.', 'driver' => 'Sari Wulandari', 'sent' => '2 menit lalu'],
    ];

    $collection   = collect($drivers);
    $online       = $collection->where('state', '!=', 'offline')->count();
    $bertugas     = $collection->where('state', 'bertugas')->count();
    $tersedia     = $collection->where('state', 'tersedia')->count();
    $onlineList   = $collection->where('state', '!=', 'offline')->values();
    $inisial      = fn ($nama) => strtoupper(collect(explode(' ', $nama))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    $notif        = 3;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Driver | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .drv-grid { display:grid; grid-template-columns:minmax(0,1.5fr) minmax(0,1fr); gap:1.25rem; margin-bottom:1.25rem; }
        @media (max-width:1100px) { .drv-grid { grid-template-columns:1fr; } }
        .drv-map { height:400px; border-radius:14px; overflow:hidden; border:1px solid #e5efed; }
        .drv-list { display:flex; flex-direction:column; max-height:440px; overflow-y:auto; }
        .drv-item { display:flex; align-items:center; gap:.8rem; padding:.8rem 0; border-bottom:1px solid #e8eef0; }
        .drv-item:last-child { border-bottom:0; }
        .drv-avatar { width:42px; height:42px; border-radius:50%; background:#e6f6f5; color:#087f7c; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; flex:none; }
        .drv-item.is-off .drv-avatar { background:#eef1f3; color:#8aa0ab; }
        .drv-info { flex:1; min-width:0; }
        .drv-info strong { display:block; color:#16323f; }
        .drv-info small { display:block; color:#6b8290; margin-top:.1rem; }
        .drv-side { text-align:right; flex:none; }
        .drv-eta { display:block; font-size:.8rem; color:#6b8290; margin-top:.3rem; }
        .st-chip { display:inline-block; padding:.2rem .7rem; border-radius:999px; font-size:.76rem; font-weight:700; }
        .st-busy { background:#fff3dd; color:#9a6200; }
        .st-free { background:#dcfce7; color:#166534; }
        .st-off  { background:#eef1f3; color:#6b7b85; }
        .st-wait { background:#e6f0ff; color:#1f4fa3; }

        .flash { background:#e6f6f5; color:#087f7c; border-radius:12px; padding:.7rem 1rem; margin-bottom:1rem; font-weight:600; }
        .assign-card { display:flex; gap:.75rem; align-items:center; flex-wrap:wrap; padding:1rem; border:1px solid #e3ecee; border-radius:14px; margin-bottom:.75rem; background:#fbfdfd; }
        .assign-info { flex:1 1 220px; min-width:0; }
        .assign-info strong { color:#16323f; }
        .assign-info small { display:block; color:#6b8290; margin-top:.15rem; }
        .assign-select { padding:.6rem .85rem; border:1px solid #d5e2e5; border-radius:11px; background:#fff; font:inherit; min-width:230px; color:#16323f; }
        .assign-select:focus { outline:none; border-color:#0a9e9a; box-shadow:0 0 0 3px rgba(10,158,154,.15); }
        .assign-btn { padding:.6rem 1.2rem; border:0; border-radius:11px; background:#0a9e9a; color:#fff; font:inherit; font-weight:700; cursor:pointer; }
        .assign-btn:hover { background:#087f7c; }
        .pending-row { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; padding:.8rem 0; border-bottom:1px solid #e8eef0; }
        .pending-row:last-child { border-bottom:0; }
        .sub-title { margin:1.4rem 0 .6rem; font-size:.78rem; letter-spacing:.06em; color:#6b8290; font-weight:700; }
        .drv-search { width:300px; max-width:100%; padding:.65rem 1rem; border:1px solid #d5e2e5; border-radius:12px; background:#f7fafb; font:inherit; font-size:.92rem; color:#16323f; outline:none; transition:border-color .15s, box-shadow .15s, background .15s; }
        .drv-search::placeholder { color:#8aa0ab; }
        .drv-search:focus { background:#fff; border-color:#0a9e9a; box-shadow:0 0 0 3px rgba(10,158,154,.15); }
    </style>
</head>
<body>
<div class="admin-layout">
    @include('admin.sidebar', ['active' => 'driver'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Monitoring Driver</h1></div>
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
                <p class="welcome-label">OPERASIONAL</p>
                <h2>Pantau dan tugaskan driver</h2>
                <p>Driver yang online muncul otomatis di sini. Pilih driver untuk tiap pesanan, lalu tunggu konfirmasi dari driver.</p>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card"><div class="stat-heading"><span>Driver Online</span></div><strong class="stat-value">{{ $online }}</strong><p>Dari {{ $collection->count() }} driver terdaftar</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Sedang Bertugas</span></div><strong class="stat-value">{{ $bertugas }}</strong><p>Pickup atau pengantaran berjalan</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Tersedia</span></div><strong class="stat-value">{{ $tersedia }}</strong><p>Siap menerima tugas baru</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Menunggu Konfirmasi</span></div><strong class="stat-value">{{ count($pending) }}</strong><p>Penugasan belum dijawab driver</p></article>
        </section>

        <section class="drv-grid">
            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">AREA OPERASIONAL</span><h3>Peta Lokasi Driver</h3></div></div>
                <div id="adminLiveMap" class="drv-map" data-url="{{ route('admin.drivers.locations') }}"></div>
                <p class="demo-note" id="adminLiveNote">Menunggu driver membagikan lokasi...</p>
            </article>

            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">DAFTAR DRIVER</span><h3>Status Driver</h3></div><span class="driver-count">{{ $online }} online</span></div>
                <div class="drv-list">
                    @foreach ($collection->sortBy(fn ($d) => $d['state'] === 'offline' ? 1 : 0) as $d)
                        @php $s = $stateMap[$d['state']]; @endphp
                        <div class="drv-item {{ $d['state'] === 'offline' ? 'is-off' : '' }}">
                            <div class="drv-avatar">{{ $inisial($d['name']) }}</div>
                            <div class="drv-info">
                                <strong>{{ $d['name'] }}</strong>
                                <small>{{ $d['code'] }} · {{ $d['vehicle'] }} · {{ $d['note'] }}</small>
                            </div>
                            <div class="drv-side">
                                <span class="st-chip {{ $s['class'] }}">{{ $s['label'] }}</span>
                                @if ($d['eta'])
                                    <span class="drv-eta">{{ $d['eta'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="demo-note">Nama, status, dan estimasi di atas masih data contoh.</p>
            </article>
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div><span class="panel-eyebrow">PENUGASAN</span><h3>Tugaskan driver ke pesanan</h3></div>
                <span class="driver-count">{{ count($assignOrders) }} menunggu driver</span>
            </div>

            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            @forelse ($assignOrders as $o)
                <form method="POST" action="{{ route('admin.drivers.assign') }}" class="assign-card">
                    @csrf
                    <input type="hidden" name="order" value="{{ $o['code'] }}">
                    <div class="assign-info">
                        <strong>{{ $o['code'] }} · {{ $o['customer'] }}</strong>
                        <small>{{ $o['type'] }} pukul {{ $o['when'] }} · {{ $o['address'] }}</small>
                    </div>
                    <select name="driver" class="assign-select" required aria-label="Pilih driver untuk {{ $o['code'] }}">
                        <option value="" disabled selected>Pilih driver online</option>
                        @foreach ($onlineList as $d)
                            <option value="{{ $d['code'] }}">{{ $d['name'] }} ({{ $d['state'] === 'bertugas' ? 'sedang bertugas' : 'tersedia' }})</option>
                        @endforeach
                    </select>
                    <button class="assign-btn">Tugaskan</button>
                </form>
            @empty
                <p class="demo-note">Semua pesanan sudah punya driver.</p>
            @endforelse

            <p class="sub-title">MENUNGGU KONFIRMASI DRIVER</p>
            @forelse ($pending as $p)
                <div class="pending-row">
                    <div class="assign-info">
                        <strong>{{ $p['order'] }} · {{ $p['customer'] }}</strong>
                        <small>Ditugaskan ke {{ $p['driver'] }} · {{ $p['sent'] }}</small>
                    </div>
                    <span class="st-chip st-wait">Menunggu driver</span>
                </div>
            @empty
                <p class="demo-note">Tidak ada penugasan yang menunggu jawaban driver.</p>
            @endforelse

            <p class="demo-note">Data contoh. Penugasan akan tersimpan ke database setelah kolom driver_id pada pesanan tersedia.</p>
        </section>

        <section class="panel orders-panel" style="margin-top:1.25rem">
            <div class="panel-heading orders-heading">
                <div><span class="panel-eyebrow">TUGAS DRIVER</span><h3>Daftar Pickup dan Pengantaran</h3></div>
                <input type="search" class="drv-search" placeholder="Cari driver atau pesanan" aria-label="Cari tugas driver" oninput="filterDriverTasks(this.value)">
            </div>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead><tr><th>Driver</th><th>ID Pesanan</th><th>Jenis Tugas</th><th>Tujuan</th><th>Estimasi</th><th>Status</th></tr></thead>
                    <tbody id="driverTaskRows">
                        <tr><td><div class="customer-cell"><span class="customer-avatar">BP</span><span>Bagas Pratama</span></div></td><td class="order-id">SW-00128</td><td>Pickup</td><td>Jl. Melati No. 12</td><td>8 menit</td><td><span class="order-status status-processing">Dalam perjalanan</span></td></tr>
                        <tr><td><div class="customer-cell"><span class="customer-avatar">RM</span><span>Rizky Maulana</span></div></td><td class="order-id">SW-00127</td><td>Pengantaran</td><td>Jl. Kenanga No. 8</td><td>12 menit</td><td><span class="order-status status-payment">Mengantar</span></td></tr>
                        <tr><td><div class="customer-cell"><span class="customer-avatar">DS</span><span>Deni Saputra</span></div></td><td class="order-id">SW-00124</td><td>Pickup</td><td>Jl. Mawar No. 4</td><td>5 menit</td><td><span class="order-status status-processing">Dalam perjalanan</span></td></tr>
                        <tr><td><div class="customer-cell"><span class="customer-avatar">BP</span><span>Bagas Pratama</span></div></td><td class="order-id">SW-00121</td><td>Pengantaran</td><td>Jl. Anggrek No. 5</td><td>—</td><td><span class="order-status status-complete">Selesai</span></td></tr>
                    </tbody>
                </table>
            </div>
            <p class="demo-note">Data tugas pada tabel ini masih contoh. Posisi driver di peta berasal dari lokasi yang dibagikan driver.</p>
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
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var el = document.getElementById('adminLiveMap');
    var note = document.getElementById('adminLiveNote');
    var map = L.map(el).setView([-7.29, 112.734], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
    var markers = {}, fitted = false;

    function refresh() {
        fetch(el.dataset.url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (list) {
                Object.keys(markers).forEach(function (k) {
                    if (!list.some(function (d) { return d.code === k; })) { map.removeLayer(markers[k]); delete markers[k]; }
                });
                list.forEach(function (d) {
                    var ll = [d.lat, d.lng];
                    if (markers[d.code]) { markers[d.code].setLatLng(ll); }
                    else { markers[d.code] = L.marker(ll).addTo(map).bindPopup(d.name + ' (' + d.code + ')'); }
                });
                if (list.length && !fitted) { map.setView([list[0].lat, list[0].lng], 15); fitted = true; }
                note.textContent = list.length
                    ? list.length + ' driver membagikan lokasi. Diperbarui ' + new Date().toLocaleTimeString('id-ID') + '.'
                    : 'Belum ada driver yang membagikan lokasi.';
            })
            .catch(function () { note.textContent = 'Gagal memuat lokasi driver.'; });
    }
    refresh();
    setInterval(refresh, 5000);
})();
</script>
</body>
</html>