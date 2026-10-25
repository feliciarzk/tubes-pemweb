@php
    $jadwal = [
        ['jam' => '09.00', 'order' => 'SW-00131', 'pelanggan' => 'Nadia A.', 'alamat' => 'Jl. Melati No. 12',  'jenis' => 'Pickup', 'driver' => 'Bagas',  'status' => 'Menunggu'],
        ['jam' => '10.30', 'order' => 'SW-00130', 'pelanggan' => 'Rafi F.',  'alamat' => 'Jl. Kenanga No. 8',  'jenis' => 'Pickup', 'driver' => 'Rizky',  'status' => 'Dijadwalkan'],
        ['jam' => '11.00', 'order' => 'SW-00129', 'pelanggan' => 'Dina P.',  'alamat' => 'Jl. Mawar No. 4',    'jenis' => 'Antar',  'driver' => 'Sari',   'status' => 'Dijadwalkan'],
        ['jam' => '13.00', 'order' => 'SW-00126', 'pelanggan' => 'Sari L.',  'alamat' => 'Jl. Dahlia No. 21',  'jenis' => 'Antar',  'driver' => 'Bagas',  'status' => 'Selesai'],
        ['jam' => '14.00', 'order' => 'SW-00128', 'pelanggan' => 'Tono W.',  'alamat' => 'Jl. Cempaka No. 9',  'jenis' => 'Pickup', 'driver' => 'Deni',   'status' => 'Dijadwalkan'],
        ['jam' => '15.30', 'order' => 'SW-00125', 'pelanggan' => 'Andi S.',  'alamat' => 'Jl. Anggrek No. 5',  'jenis' => 'Antar',  'driver' => 'Rizky',  'status' => 'Perlu Konfirmasi'],
        ['jam' => '08.00', 'order' => 'SW-00124', 'pelanggan' => 'Budi H.',  'alamat' => 'Jl. Kamboja No. 17', 'jenis' => 'Pickup', 'driver' => 'Deni',   'status' => 'Selesai'],
    ];
    $driverList = ['Bagas', 'Rizky', 'Deni', 'Sari'];
    $tanggalHariIni = now()->locale('id')->translatedFormat('l, d F Y');
    $notif = 3;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pickup | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .jd-tools { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
        .jd-seg { display:flex; width:fit-content; gap:.25rem; padding:.3rem; background:#eef4f5; border-radius:12px; }
        .jd-seg button { padding:.5rem 1.2rem; border:0; border-radius:9px; background:transparent; font:inherit; font-weight:600; color:#5b7482; cursor:pointer; }
        .jd-seg button:hover { color:#087f7c; }
        .jd-seg button.active { background:#fff; color:#087f7c; box-shadow:0 1px 4px rgba(15,30,40,.12); }
        .jd-search { width:300px; max-width:100%; padding:.65rem 1rem; border:1px solid #d5e2e5; border-radius:12px; background:#f7fafb; font:inherit; font-size:.92rem; color:#16323f; outline:none; transition:border-color .15s, box-shadow .15s, background .15s; }
        .jd-search::placeholder { color:#8aa0ab; }
        .jd-search:focus { background:#fff; border-color:#0a9e9a; box-shadow:0 0 0 3px rgba(10,158,154,.15); }
        .jd-flash { background:#e6f6f5; color:#087f7c; border-radius:12px; padding:.7rem 1rem; margin-bottom:1rem; font-weight:600; display:none; }
        .jd-flash.show { display:block; }
        .jd-time { font-weight:700; color:#16323f; white-space:nowrap; }
        .jd-type { display:inline-block; padding:.2rem .7rem; border-radius:999px; font-size:.76rem; font-weight:700; }
        .jd-type.pickup { background:#e6f6f5; color:#087f7c; }
        .jd-type.antar { background:#e6f0ff; color:#1f4fa3; }
        .jd-empty { text-align:center; color:#6b8290; padding:1.5rem 0; }
        .jd-btn { padding:.6rem 1.2rem; border:0; border-radius:11px; background:#0a9e9a; color:#fff; font:inherit; font-weight:700; cursor:pointer; }
        .jd-btn:hover { background:#087f7c; }
        .jd-btn.ghost { background:#fff; color:#087f7c; border:1px solid #cfdde0; }
        .jd-btn.ghost:hover { background:#f2f8f8; }
        .jd-modal { position:fixed; inset:0; background:rgba(15,30,40,.55); display:none; align-items:center; justify-content:center; padding:1rem; z-index:50; }
        .jd-modal.open { display:flex; }
        .jd-dialog { background:#fff; border-radius:18px; width:100%; max-width:480px; max-height:92vh; overflow-y:auto; padding:1.4rem; }
        .jd-dialog h3 { margin:0 0 1rem; }
        .jd-field { display:flex; flex-direction:column; gap:.3rem; margin-bottom:.85rem; }
        .jd-field label { font-size:.82rem; font-weight:600; color:#5b7482; }
        .jd-field input, .jd-field select { padding:.62rem .85rem; border:1px solid #d5e2e5; border-radius:11px; background:#fff; font:inherit; color:#16323f; }
        .jd-field input:focus, .jd-field select:focus { outline:none; border-color:#0a9e9a; box-shadow:0 0 0 3px rgba(10,158,154,.15); }
        .jd-row2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .jd-actions { display:flex; gap:.6rem; justify-content:flex-end; margin-top:1.1rem; }
    </style>
</head>
<body>
<div class="admin-layout">
    @include('admin.sidebar', ['active' => 'jadwal'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Jadwal Pickup</h1></div>
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
                <p class="welcome-label">PENJEMPUTAN &amp; PENGANTARAN</p>
                <h2>Jadwal Hari Ini</h2>
                <p>{{ $tanggalHariIni }}. Atur jadwal pengambilan dan pengantaran pakaian pelanggan.</p>
            </div>
            <button type="button" class="primary-button" id="btnTambah">Tambah Jadwal</button>
        </section>

        <section class="stats-grid">
            <article class="stat-card"><div class="stat-heading"><span>Total Jadwal</span></div><strong class="stat-value" id="stTotal">0</strong><p>Pickup dan pengantaran hari ini</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pickup Belum Selesai</span></div><strong class="stat-value" id="stPickup">0</strong><p>Perlu dijemput</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pengantaran Belum Selesai</span></div><strong class="stat-value" id="stAntar">0</strong><p>Pesanan siap dikirim</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Selesai</span></div><strong class="stat-value" id="stSelesai">0</strong><p>Jadwal terselesaikan</p></article>
        </section>

        <section class="panel orders-panel">
            <div class="panel-heading"><div><span class="panel-eyebrow">AGENDA OPERASIONAL</span><h3>Jadwal Terdekat</h3></div></div>

            <div class="jd-flash" id="jdFlash"></div>

            <div class="jd-tools">
                <div class="jd-seg" role="group" aria-label="Filter jenis jadwal">
                    <button type="button" class="active" data-jenis="Semua">Semua</button>
                    <button type="button" data-jenis="Pickup">Pickup</button>
                    <button type="button" data-jenis="Antar">Antar</button>
                </div>
                <input type="search" class="jd-search" id="jdSearch" placeholder="Cari pesanan, pelanggan, atau driver" aria-label="Cari jadwal">
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr><th>Waktu</th><th>ID Pesanan</th><th>Pelanggan</th><th>Alamat</th><th>Jenis</th><th>Driver</th><th>Status</th></tr>
                    </thead>
                    <tbody id="jdRows"></tbody>
                </table>
            </div>
            <p class="demo-note">Alamat dan jadwal di atas hanya data ilustrasi dan belum tersimpan di database.</p>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>

<div class="jd-modal" id="jdModal" aria-hidden="true">
    <div class="jd-dialog" role="dialog" aria-modal="true" aria-labelledby="jdTitle">
        <h3 id="jdTitle">Tambah Jadwal</h3>
        <div class="jd-row2">
            <div class="jd-field"><label for="fOrder">ID Pesanan</label><input id="fOrder" type="text" placeholder="SW-00132"></div>
            <div class="jd-field"><label for="fPelanggan">Pelanggan</label><input id="fPelanggan" type="text" placeholder="Nama pelanggan"></div>
        </div>
        <div class="jd-field"><label for="fAlamat">Alamat</label><input id="fAlamat" type="text" placeholder="Alamat penjemputan atau pengantaran"></div>
        <div class="jd-row2">
            <div class="jd-field"><label for="fJenis">Jenis</label>
                <select id="fJenis"><option value="Pickup">Pickup</option><option value="Antar">Antar</option></select>
            </div>
            <div class="jd-field"><label for="fJam">Jam</label><input id="fJam" type="time" value="09:00"></div>
        </div>
        <div class="jd-field"><label for="fDriver">Driver</label>
            <select id="fDriver">
                @foreach ($driverList as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="jd-actions">
            <button type="button" class="jd-btn ghost" id="btnBatal">Batal</button>
            <button type="button" class="jd-btn" id="btnSimpan">Simpan</button>
        </div>
    </div>
</div>

<script>
var jadwal = @json($jadwal);
var statusClass = {
    'Menunggu': 'status-waiting',
    'Dijadwalkan': 'status-processing',
    'Selesai': 'status-complete',
    'Perlu Konfirmasi': 'status-payment'
};
var filterJenis = 'Semua';
var kataKunci = '';

function esc(t) {
    var d = document.createElement('div');
    d.textContent = t;
    return d.innerHTML;
}

function urutJam(a, b) {
    return a.jam.localeCompare(b.jam);
}

function hitungStatistik() {
    var pickup = 0, antar = 0, selesai = 0;
    jadwal.forEach(function (j) {
        if (j.status === 'Selesai') selesai++;
        else if (j.jenis === 'Pickup') pickup++;
        else antar++;
    });
    document.getElementById('stTotal').textContent = jadwal.length;
    document.getElementById('stPickup').textContent = pickup;
    document.getElementById('stAntar').textContent = antar;
    document.getElementById('stSelesai').textContent = selesai;
}

function render() {
    var q = kataKunci.toLowerCase();
    var tampil = jadwal.slice().sort(urutJam).filter(function (j) {
        if (filterJenis !== 'Semua' && j.jenis !== filterJenis) return false;
        if (!q) return true;
        return (j.order + ' ' + j.pelanggan + ' ' + j.driver + ' ' + j.alamat).toLowerCase().indexOf(q) !== -1;
    });
    var tbody = document.getElementById('jdRows');
    if (!tampil.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="jd-empty">Tidak ada jadwal yang cocok.</td></tr>';
        return;
    }
    tbody.innerHTML = tampil.map(function (j) {
        return '<tr>'
            + '<td class="jd-time">' + esc(j.jam) + '</td>'
            + '<td class="order-id">' + esc(j.order) + '</td>'
            + '<td>' + esc(j.pelanggan) + '</td>'
            + '<td>' + esc(j.alamat) + '</td>'
            + '<td><span class="jd-type ' + j.jenis.toLowerCase() + '">' + esc(j.jenis) + '</span></td>'
            + '<td>' + esc(j.driver) + '</td>'
            + '<td><span class="order-status ' + (statusClass[j.status] || 'status-waiting') + '">' + esc(j.status) + '</span></td>'
            + '</tr>';
    }).join('');
}

function bukaModal() {
    var m = document.getElementById('jdModal');
    m.classList.add('open');
    m.setAttribute('aria-hidden', 'false');
    document.getElementById('fOrder').focus();
}

function tutupModal() {
    var m = document.getElementById('jdModal');
    m.classList.remove('open');
    m.setAttribute('aria-hidden', 'true');
}

function simpan() {
    var order = document.getElementById('fOrder').value.trim();
    var pelanggan = document.getElementById('fPelanggan').value.trim();
    var alamat = document.getElementById('fAlamat').value.trim();
    var jam = document.getElementById('fJam').value;
    if (!order || !pelanggan || !alamat || !jam) {
        alert('Lengkapi ID pesanan, pelanggan, alamat, dan jam terlebih dahulu.');
        return;
    }
    jadwal.push({
        jam: jam.replace(':', '.'),
        order: order,
        pelanggan: pelanggan,
        alamat: alamat,
        jenis: document.getElementById('fJenis').value,
        driver: document.getElementById('fDriver').value,
        status: 'Dijadwalkan'
    });
    ['fOrder', 'fPelanggan', 'fAlamat'].forEach(function (id) { document.getElementById(id).value = ''; });
    tutupModal();
    hitungStatistik();
    render();
    var flash = document.getElementById('jdFlash');
    flash.textContent = 'Jadwal untuk pesanan ' + order + ' berhasil ditambahkan.';
    flash.classList.add('show');
    setTimeout(function () { flash.classList.remove('show'); }, 4000);
}

document.querySelectorAll('.jd-seg button').forEach(function (b) {
    b.addEventListener('click', function () {
        filterJenis = b.dataset.jenis;
        document.querySelectorAll('.jd-seg button').forEach(function (x) { x.classList.toggle('active', x === b); });
        render();
    });
});

document.getElementById('jdSearch').addEventListener('input', function () {
    kataKunci = this.value;
    render();
});
document.getElementById('btnTambah').addEventListener('click', bukaModal);
document.getElementById('btnBatal').addEventListener('click', tutupModal);
document.getElementById('btnSimpan').addEventListener('click', simpan);
document.getElementById('jdModal').addEventListener('click', function (e) {
    if (e.target === this) tutupModal();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') tutupModal();
});

hitungStatistik();
render();
</script>
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>