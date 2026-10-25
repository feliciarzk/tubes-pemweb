@php
    $rp = fn ($n) => 'Rp' . number_format($n, 0, ',', '.');
    $rpRingkas = fn ($n) => $n >= 1000000 ? 'Rp' . number_format($n / 1000000, 1, ',', '.') . ' jt' : $rp($n);

    $statusMap = [
        'paid'    => ['label' => 'Lunas',            'class' => 'status-complete'],
        'review'  => ['label' => 'Perlu Verifikasi', 'class' => 'status-payment'],
        'waiting' => ['label' => 'Menunggu',         'class' => 'status-waiting'],
    ];

    $transaksi = [
        ['trx' => 'TRX-0099', 'order' => 'SW-00129', 'pelanggan' => 'Tono W.',  'metode' => 'Transfer Bank', 'total' => 20000, 'tanggal' => '16 Okt 2026', 'status' => 'review',  'bukti' => 'bukti/TRX-0099.jpg'],
        ['trx' => 'TRX-0098', 'order' => 'SW-00128', 'pelanggan' => 'Nadia A.', 'metode' => 'Transfer Bank', 'total' => 35000, 'tanggal' => '16 Okt 2026', 'status' => 'paid',    'bukti' => 'bukti/TRX-0098.jpg'],
        ['trx' => 'TRX-0097', 'order' => 'SW-00127', 'pelanggan' => 'Rafi F.',  'metode' => 'QRIS',          'total' => 20000, 'tanggal' => '16 Okt 2026', 'status' => 'waiting', 'bukti' => null],
        ['trx' => 'TRX-0096', 'order' => 'SW-00126', 'pelanggan' => 'Dina P.',  'metode' => 'Tunai',         'total' => 45000, 'tanggal' => '15 Okt 2026', 'status' => 'paid',    'bukti' => null],
        ['trx' => 'TRX-0095', 'order' => 'SW-00125', 'pelanggan' => 'Andi S.',  'metode' => 'QRIS',          'total' => 30000, 'tanggal' => '15 Okt 2026', 'status' => 'review',  'bukti' => 'bukti/TRX-0095.jpg'],
    ];

    $semua = collect($transaksi);
    $jumlahLunas = $semua->where('status', 'paid')->count();
    $jumlahBelum = $semua->count() - $jumlahLunas;
    $pendapatan  = $semua->where('status', 'paid')->sum('total');
    $notif       = 3;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .pay-search { width:300px; max-width:100%; padding:.65rem 1rem; border:1px solid #d5e2e5; border-radius:12px; background:#f7fafb; font:inherit; font-size:.92rem; color:#16323f; outline:none; transition:border-color .15s, box-shadow .15s, background .15s; }
        .pay-search::placeholder { color:#8aa0ab; }
        .pay-search:hover { border-color:#b9ccd1; }
        .pay-search:focus { background:#fff; border-color:#0a9e9a; box-shadow:0 0 0 3px rgba(10,158,154,.15); }
        .pay-flash { background:#e6f6f5; color:#087f7c; border-radius:12px; padding:.7rem 1rem; margin-bottom:1rem; font-weight:600; display:none; }
        .pay-flash.show { display:block; }
        .pay-btn { padding:.45rem .95rem; border:0; border-radius:10px; font:inherit; font-weight:700; font-size:.85rem; cursor:pointer; background:#0a9e9a; color:#fff; }
        .pay-btn:hover { background:#087f7c; }
        .pay-btn.ghost { background:#fff; color:#087f7c; border:1px solid #cfdde0; }
        .pay-btn.ghost:hover { background:#f2f8f8; }
        .pay-muted { color:#6b8290; font-size:.85rem; }
        .pay-modal { position:fixed; inset:0; background:rgba(15,30,40,.55); display:none; align-items:center; justify-content:center; padding:1rem; z-index:50; }
        .pay-modal.open { display:flex; }
        .pay-dialog { background:#fff; border-radius:18px; width:100%; max-width:460px; max-height:92vh; overflow-y:auto; padding:1.4rem; }
        .pay-dialog h3 { margin:0 0 .2rem; }
        .pay-detail { display:grid; grid-template-columns:auto 1fr; gap:.35rem 1rem; margin:1rem 0; font-size:.92rem; }
        .pay-detail dt { color:#6b8290; }
        .pay-detail dd { margin:0; font-weight:600; text-align:right; }
        .pay-proof { border:1px dashed #cfdde0; border-radius:14px; min-height:220px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#f7fafb; margin-bottom:1rem; }
        .pay-proof img { width:100%; height:auto; display:block; }
        .pay-proof span { color:#6b8290; font-size:.9rem; padding:1rem; text-align:center; }
        .pay-actions { display:flex; gap:.6rem; justify-content:flex-end; }
    </style>
</head>
<body>
<div class="admin-layout">
    @include('admin.sidebar', ['active' => 'pembayaran'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Pembayaran</h1></div>
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
                <p class="welcome-label">TRANSAKSI</p>
                <h2>Data Pembayaran</h2>
                <p>Cek bukti pembayaran tiap pesanan, lalu verifikasi satu per satu.</p>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card"><div class="stat-heading"><span>Total Transaksi</span></div><strong class="stat-value" id="statTotal">{{ $semua->count() }}</strong><p>Transaksi tercatat</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Sudah Lunas</span></div><strong class="stat-value" id="statLunas">{{ $jumlahLunas }}</strong><p>Pembayaran terverifikasi</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Belum Lunas</span></div><strong class="stat-value" id="statBelum">{{ $jumlahBelum }}</strong><p>Menunggu bukti atau verifikasi</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pendapatan Lunas</span></div><strong class="stat-value stat-money" id="statUang">{{ $rpRingkas($pendapatan) }}</strong><p>Dari pembayaran terverifikasi</p></article>
        </section>

        <section class="panel orders-panel">
            <div class="panel-heading orders-heading">
                <div><span class="panel-eyebrow">RIWAYAT TRANSAKSI</span><h3>Daftar Pembayaran</h3></div>
                <input type="search" class="pay-search" placeholder="Cari pesanan, pelanggan, atau status" aria-label="Cari pembayaran" oninput="filterRows(this.value, 'paymentRows')">
            </div>

            <div class="pay-flash" id="payFlash"></div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr><th>ID Transaksi</th><th>ID Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Total</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody id="paymentRows">
                        @foreach ($transaksi as $t)
                            @php $s = $statusMap[$t['status']]; @endphp
                            <tr data-trx="{{ $t['trx'] }}" data-order="{{ $t['order'] }}" data-pelanggan="{{ $t['pelanggan'] }}" data-metode="{{ $t['metode'] }}" data-total="{{ $t['total'] }}" data-tanggal="{{ $t['tanggal'] }}" data-status="{{ $t['status'] }}" data-bukti="{{ $t['bukti'] ? asset($t['bukti']) : '' }}">
                                <td>{{ $t['trx'] }}</td>
                                <td class="order-id">{{ $t['order'] }}</td>
                                <td>{{ $t['pelanggan'] }}</td>
                                <td>{{ $t['metode'] }}</td>
                                <td>{{ $rp($t['total']) }}</td>
                                <td>{{ $t['tanggal'] }}</td>
                                <td class="cell-status"><span class="order-status {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                                <td class="cell-aksi"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="demo-note">Data pembayaran masih contoh dan belum tersimpan di database.</p>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>

<div class="pay-modal" id="payModal" aria-hidden="true">
    <div class="pay-dialog" role="dialog" aria-modal="true" aria-labelledby="payTitle">
        <h3 id="payTitle">Bukti Pembayaran</h3>
        <span class="pay-muted" id="payOrder"></span>
        <dl class="pay-detail">
            <dt>Pelanggan</dt><dd id="payPelanggan"></dd>
            <dt>Metode</dt><dd id="payMetode"></dd>
            <dt>Total</dt><dd id="payTotal"></dd>
            <dt>Tanggal</dt><dd id="payTanggal"></dd>
            <dt>Status</dt><dd id="payStatus"></dd>
        </dl>
        <div class="pay-proof" id="payProof"></div>
        <div class="pay-actions">
            <button type="button" class="pay-btn ghost" onclick="tutupModal()">Tutup</button>
            <button type="button" class="pay-btn" id="payVerifikasi" onclick="verifikasi()">Verifikasi</button>
        </div>
    </div>
</div>

<script>
var statusMap = {
    paid:    { label: 'Lunas',            cls: 'status-complete' },
    review:  { label: 'Perlu Verifikasi', cls: 'status-payment' },
    waiting: { label: 'Menunggu',         cls: 'status-waiting' }
};
var aktif = null;

function rupiah(n) {
    return 'Rp' + Number(n).toLocaleString('id-ID');
}

function rupiahRingkas(n) {
    if (n >= 1000000) {
        return 'Rp' + (n / 1000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' jt';
    }
    return rupiah(n);
}

function filterRows(q, id) {
    document.querySelectorAll('#' + id + ' tr').forEach(function (r) {
        r.style.display = r.innerText.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}

function renderRow(row) {
    var st = row.dataset.status;
    var info = statusMap[st];
    row.querySelector('.cell-status').innerHTML = '<span class="order-status ' + info.cls + '">' + info.label + '</span>';

    var aksi = row.querySelector('.cell-aksi');
    aksi.innerHTML = '';
    var tombol = document.createElement('button');
    tombol.type = 'button';

    if (st === 'review') {
        tombol.className = 'pay-btn';
        tombol.textContent = 'Cek Bukti';
        tombol.onclick = function () { bukaModal(row); };
        aksi.appendChild(tombol);
    } else if (st === 'paid' && row.dataset.bukti) {
        tombol.className = 'pay-btn ghost';
        tombol.textContent = 'Lihat Bukti';
        tombol.onclick = function () { bukaModal(row); };
        aksi.appendChild(tombol);
    } else if (st === 'waiting') {
        aksi.innerHTML = '<span class="pay-muted">Belum ada bukti</span>';
    } else {
        aksi.innerHTML = '<span class="pay-muted">—</span>';
    }
}

function hitungStatistik() {
    var rows = document.querySelectorAll('#paymentRows tr');
    var lunas = 0, uang = 0;
    rows.forEach(function (r) {
        if (r.dataset.status === 'paid') {
            lunas++;
            uang += Number(r.dataset.total);
        }
    });
    document.getElementById('statTotal').textContent = rows.length;
    document.getElementById('statLunas').textContent = lunas;
    document.getElementById('statBelum').textContent = rows.length - lunas;
    document.getElementById('statUang').textContent = rupiahRingkas(uang);
}

function bukaModal(row) {
    aktif = row;
    var d = row.dataset;
    document.getElementById('payOrder').textContent = d.order + ' · ' + d.trx;
    document.getElementById('payPelanggan').textContent = d.pelanggan;
    document.getElementById('payMetode').textContent = d.metode;
    document.getElementById('payTotal').textContent = rupiah(d.total);
    document.getElementById('payTanggal').textContent = d.tanggal;
    document.getElementById('payStatus').textContent = statusMap[d.status].label;

    var proof = document.getElementById('payProof');
    proof.innerHTML = '';
    if (d.bukti) {
        var img = document.createElement('img');
        img.alt = 'Bukti pembayaran ' + d.order;
        img.onerror = function () {
            proof.innerHTML = '<span>File bukti belum tersedia. Gambar akan tampil setelah upload bukti terhubung ke backend.</span>';
        };
        img.src = d.bukti;
        proof.appendChild(img);
    } else {
        proof.innerHTML = '<span>Tidak ada bukti yang diunggah.</span>';
    }

    document.getElementById('payVerifikasi').style.display = d.status === 'review' ? '' : 'none';
    var modal = document.getElementById('payModal');
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
}

function tutupModal() {
    var modal = document.getElementById('payModal');
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    aktif = null;
}

function verifikasi() {
    if (!aktif || aktif.dataset.status !== 'review') return;
    var order = aktif.dataset.order;
    aktif.dataset.status = 'paid';
    renderRow(aktif);
    hitungStatistik();
    tutupModal();
    var flash = document.getElementById('payFlash');
    flash.textContent = 'Pembayaran pesanan ' + order + ' berhasil diverifikasi.';
    flash.classList.add('show');
    setTimeout(function () { flash.classList.remove('show'); }, 4000);
}

document.getElementById('payModal').addEventListener('click', function (e) {
    if (e.target === this) tutupModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') tutupModal();
});

document.querySelectorAll('#paymentRows tr').forEach(renderRow);
</script>
</body>
</html>