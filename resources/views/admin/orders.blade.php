
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

    $langkah = [
        'waiting_acc'=>'Konfirmasi',
        'pickup'=>'Pickup',
        'weighed'=>'Timbang',
        'payment'=>'Bayar',
        'processing'=>'Proses',
        'delivery'=>'Antar',
        'complete'=>'Selesai',
    ];

    $tarif = [
        'Cuci & Setrika'=>10000,
        'Cuci Kering'=>8000,
        'Setrika Saja'=>6000,
    ];

    $driverList = ['Bagas','Rizky','Deni','Sari'];

    $dataPesanan = [
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

    $pesanan = collect($dataPesanan)->map(function($p) {
        $p['tanggal'] = now()->addDays($p['hari'])->locale('id')->translatedFormat('d M Y');
        return $p;
    })->all();

    $perluTindakan = collect($pesanan)
        ->filter(fn($p) => $statusMap[$p['status']]['aksi'] !== null)
        ->values();

    $notif = $perluTindakan->count();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan | SWASH Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
<div class="admin-layout">

    @include('admin.sidebar', ['active'=>'pesanan'])

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <span class="topbar-label">SWASH / ADMIN</span>
                <h1>Pesanan</h1>
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
                              @if($notif === 0) hidden @endif>
                            {{ $notif }}
                        </span>
                    </summary>

                    <div class="notification-dropdown">
                        <div class="notification-heading">
                            <strong>Notifikasi</strong>
                            <span><span data-notification-label>{{ $notif }}</span> perlu tindakan</span>
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
                <p class="welcome-label">MANAJEMEN PESANAN</p>
                <h2>Semua Pesanan</h2>
                <p>Konfirmasi pesanan baru, input berat cucian, dan pantau status tiap pesanan.</p>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-heading"><span>Total Pesanan</span></div>
                <strong class="stat-value" id="stTotal">0</strong>
                <p>Semua pesanan tercatat</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Perlu Tindakan</span></div>
                <strong class="stat-value" id="stTindakan">0</strong>
                <p>Konfirmasi atau input berat</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Sedang Berjalan</span></div>
                <strong class="stat-value" id="stBerjalan">0</strong>
                <p>Pickup, bayar, proses, dan antar</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading"><span>Selesai</span></div>
                <strong class="stat-value" id="stSelesai">0</strong>
                <p>Pesanan sudah diterima</p>
            </article>
        </section>

        <section class="panel orders-panel">
            <div class="panel-heading">
                <div>
                    <span class="panel-eyebrow">DAFTAR PESANAN</span>
                    <h3>Pesanan Pelanggan</h3>
                </div>
            </div>

            <div class="od-flash" id="odFlash"></div>

            <div class="od-tools">
                <div class="od-seg" role="group" aria-label="Filter status pesanan">
                    <button type="button" class="active" data-grup="semua">Semua<span class="od-count" id="cSemua"></span></button>
                    <button type="button" data-grup="tindakan">Perlu Tindakan<span class="od-count" id="cTindakan"></span></button>
                    <button type="button" data-grup="berjalan">Berjalan<span class="od-count" id="cBerjalan"></span></button>
                    <button type="button" data-grup="selesai">Selesai<span class="od-count" id="cSelesai"></span></button>
                </div>

                <input type="search" class="od-search" id="odSearch"
                       placeholder="Cari ID pesanan, pelanggan, atau layanan"
                       aria-label="Cari pesanan">
            </div>

            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="odRows"></tbody>
                </table>
            </div>

            <p class="demo-note">
                Data pesanan masih contoh. Perubahan status belum tersimpan ke database.
            </p>
        </section>

        <footer class="admin-footer">
            <span>SWASH Laundry Service</span>
            <span>Panel Administrasi</span>
        </footer>

    </main>
</div>

<div class="od-modal" id="odModal" aria-hidden="true">
    <div class="od-dialog" role="dialog" aria-modal="true"
         aria-labelledby="odTitle" id="odDialog"></div>
</div>

<script>
var pesanan = @json($pesanan);
var statusMap = @json($statusMap);
var langkah = @json($langkah);
var tarif = @json($tarif);
var driverList = @json($driverList);

var urutLangkah = Object.keys(langkah);
var filterGrup = 'semua';
var kataKunci = '';
var aktif = null;

function esc(t) {
    var d = document.createElement('div');
    d.textContent = t == null ? '' : t;
    return d.innerHTML;
}

function rupiah(n) {
    return 'Rp' + Number(n).toLocaleString('id-ID');
}

function cari(id) {
    return pesanan.find(function(p) {
        return p.id === id;
    });
}

function updateNotifications() {
    var notifications = pesanan.filter(function(p) {
        return statusMap[p.status] && statusMap[p.status].aksi;
    });

    var badge = document.querySelector('[data-notification-count]');
    var label = document.querySelector('[data-notification-label]');
    var list = document.querySelector('[data-notification-items]');

    if (badge) {
        badge.textContent = notifications.length;
        badge.hidden = notifications.length === 0;
    }

    if (label) {
        label.textContent = notifications.length;
    }

    if (list) {
        if (!notifications.length) {
            list.innerHTML =
                '<p class="notification-empty">' +
                'Tidak ada pesanan yang perlu ditindaklanjuti.</p>';
        } else {
            list.innerHTML = notifications.map(function(p) {
                var s = statusMap[p.status];

                return '<a class="notification-item" href="/admin/pesanan">' +
                    '<span class="notification-dot"></span>' +
                    '<span><strong>' + esc(p.id) + ' · ' + esc(p.pelanggan) +
                    '</strong><small>' + esc(s.label) + ' — ' +
                    esc(s.aksi) + '</small></span></a>';
            }).join('');
        }
    }
}

function hitung() {
    var h = {
        semua: pesanan.length,
        tindakan: 0,
        berjalan: 0,
        selesai: 0
    };

    pesanan.forEach(function(p) {
        h[statusMap[p.status].grup]++;
    });

    document.getElementById('stTotal').textContent = h.semua;
    document.getElementById('stTindakan').textContent = h.tindakan;
    document.getElementById('stBerjalan').textContent = h.berjalan;
    document.getElementById('stSelesai').textContent = h.selesai;

    document.getElementById('cSemua').textContent = h.semua;
    document.getElementById('cTindakan').textContent = h.tindakan;
    document.getElementById('cBerjalan').textContent = h.berjalan;
    document.getElementById('cSelesai').textContent = h.selesai;
}

function render() {
    var q = kataKunci.toLowerCase();

    var tampil = pesanan.filter(function(p) {
        if (filterGrup !== 'semua' &&
            statusMap[p.status].grup !== filterGrup) {
            return false;
        }

        if (!q) return true;

        return (
            p.id + ' ' +
            p.pelanggan + ' ' +
            p.layanan + ' ' +
            statusMap[p.status].label
        ).toLowerCase().indexOf(q) !== -1;
    });

    var tbody = document.getElementById('odRows');

    if (!tampil.length) {
        tbody.innerHTML =
            '<tr><td colspan="7" class="od-empty">' +
            'Tidak ada pesanan yang cocok.</td></tr>';
        return;
    }

    tbody.innerHTML = tampil.map(function(p) {
        var s = statusMap[p.status];

        var aksi = s.aksi
            ? '<button type="button" class="od-btn" data-aksi="' +
              p.status + '" data-id="' + esc(p.id) + '">' +
              esc(s.aksi) + '</button>'
            : '';

        return '<tr>' +
            '<td class="order-id">' + esc(p.id) + '</td>' +
            '<td>' + esc(p.pelanggan) +
            '<span class="od-type">' + esc(p.jenis) + '</span></td>' +
            '<td>' + esc(p.layanan) + '</td>' +
            '<td>' + esc(p.tanggal) + '</td>' +
            '<td>' + (p.total ? rupiah(p.total) : '—') + '</td>' +
            '<td><span class="order-status ' + s.class + '">' +
            esc(s.label) + '</span></td>' +
            '<td><div class="od-actions">' + aksi +
            '<button type="button" class="od-btn ghost" data-detail="' +
            esc(p.id) + '">Detail</button></div></td>' +
            '</tr>';
    }).join('');
}

function tampilkanModal(html) {
    document.getElementById('odDialog').innerHTML = html;

    var m = document.getElementById('odModal');
    m.classList.add('open');
    m.setAttribute('aria-hidden', 'false');
}

function tutupModal() {
    var m = document.getElementById('odModal');
    m.classList.remove('open');
    m.setAttribute('aria-hidden', 'true');
    aktif = null;
}

function flash(teks) {
    var f = document.getElementById('odFlash');
    f.textContent = teks;
    f.classList.add('show');

    setTimeout(function() {
        f.classList.remove('show');
    }, 4000);
}

function bukaDetail(id) {
    var p = cari(id);
    if (!p) return;

    aktif = p;

    var s = statusMap[p.status];
    var idx = urutLangkah.indexOf(p.status);

    var steps = urutLangkah.map(function(k, i) {
        var cls = i < idx ? 'done' : (i === idx ? 'now' : '');
        return '<div class="od-step ' + cls + '"><i></i>' +
            esc(langkah[k]) + '</div>';
    }).join('');

    tampilkanModal(
        '<h3 id="odTitle">' + esc(p.id) + '</h3>' +
        '<span class="od-sub">' + esc(p.pelanggan) +
        ' · ' + esc(p.jenis) + '</span>' +
        '<div class="od-steps">' + steps + '</div>' +
        '<dl class="od-detail">' +
        '<dt>Layanan</dt><dd>' + esc(p.layanan) + '</dd>' +
        '<dt>Tanggal</dt><dd>' + esc(p.tanggal) + '</dd>' +
        '<dt>Jadwal</dt><dd>' + (p.jadwal ? esc(p.jadwal) : '—') + '</dd>' +
        '<dt>Driver</dt><dd>' + (p.driver ? esc(p.driver) : '—') + '</dd>' +
        '<dt>Berat</dt><dd>' + (p.berat ? esc(p.berat) + ' kg' : '—') + '</dd>' +
        '<dt>Total</dt><dd>' + (p.total ? rupiah(p.total) : '—') + '</dd>' +
        '<dt>Status</dt><dd>' + esc(s.label) + '</dd>' +
        '</dl>' +
        '<div class="od-foot">' +
        '<button type="button" class="od-btn ghost lg" data-tutup="1">Tutup</button>' +
        '</div>'
    );
}

function bukaAcc(id) {
    var p = cari(id);
    if (!p) return;

    aktif = p;

    var opsi = driverList.map(function(d) {
        return '<option value="' + esc(d) + '">' + esc(d) + '</option>';
    }).join('');

    tampilkanModal(
        '<h3 id="odTitle">ACC &amp; Atur Jam</h3>' +
        '<span class="od-sub">' + esc(p.id) + ' · ' +
        esc(p.pelanggan) + ' · ' + esc(p.layanan) + '</span>' +
        '<div class="od-row2 od-space-top">' +
        '<div class="od-field"><label for="mJam">Jam pickup</label>' +
        '<input id="mJam" type="time" value="09:00"></div>' +
        '<div class="od-field"><label for="mDriver">Driver</label>' +
        '<select id="mDriver">' + opsi + '</select></div>' +
        '</div>' +
        '<div class="od-foot">' +
        '<button type="button" class="od-btn ghost lg" data-tutup="1">Batal</button>' +
        '<button type="button" class="od-btn lg" id="btnAcc">ACC Pesanan</button>' +
        '</div>'
    );
}

function bukaBerat(id) {
    var p = cari(id);
    if (!p) return;

    aktif = p;

    tampilkanModal(
        '<h3 id="odTitle">Input Berat</h3>' +
        '<span class="od-sub">' + esc(p.id) + ' · ' +
        esc(p.pelanggan) + ' · ' + esc(p.layanan) + '</span>' +
        '<div class="od-field od-space-top">' +
        '<label for="mBerat">Berat cucian (kg)</label>' +
        '<input id="mBerat" type="number" min="0.5" step="0.5" placeholder="0">' +
        '</div>' +
        '<div class="od-total"><span>Tarif ' +
        rupiah(tarif[p.layanan]) + ' / kg</span>' +
        '<strong id="mTotal">Rp0</strong></div>' +
        '<div class="od-foot">' +
        '<button type="button" class="od-btn ghost lg" data-tutup="1">Batal</button>' +
        '<button type="button" class="od-btn lg" id="btnBerat">Simpan Berat</button>' +
        '</div>'
    );

    var input = document.getElementById('mBerat');

    input.addEventListener('input', function() {
        var kg = parseFloat(input.value) || 0;
        document.getElementById('mTotal').textContent =
            rupiah(Math.round(kg * tarif[p.layanan]));
    });

    input.focus();
}

function simpanAcc() {
    var jam = document.getElementById('mJam').value;

    if (!jam) {
        alert('Isi jam pickup terlebih dahulu.');
        return;
    }

    aktif.jadwal = jam.replace(':', '.');
    aktif.driver = document.getElementById('mDriver').value;
    aktif.status = 'pickup';

    var id = aktif.id;

    tutupModal();
    hitung();
    render();
    updateNotifications();

    flash('Pesanan ' + id + ' dikonfirmasi dan dijadwalkan.');
}

function simpanBerat() {
    var kg = parseFloat(document.getElementById('mBerat').value);

    if (!kg || kg <= 0) {
        alert('Isi berat cucian dengan angka yang benar.');
        return;
    }

    aktif.berat = kg;
    aktif.total = Math.round(kg * tarif[aktif.layanan]);
    aktif.status = 'payment';

    var id = aktif.id;

    tutupModal();
    hitung();
    render();
    updateNotifications();

    flash('Berat pesanan ' + id +
        ' tersimpan. Pelanggan diminta melakukan pembayaran.');
}

document.getElementById('odRows').addEventListener('click', function(e) {
    var b = e.target.closest('button');
    if (!b) return;

    if (b.dataset.detail) bukaDetail(b.dataset.detail);
    else if (b.dataset.aksi === 'waiting_acc') bukaAcc(b.dataset.id);
    else if (b.dataset.aksi === 'weighed') bukaBerat(b.dataset.id);
});

document.getElementById('odModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.dataset.tutup) {
        tutupModal();
    } else if (e.target.id === 'btnAcc') {
        simpanAcc();
    } else if (e.target.id === 'btnBerat') {
        simpanBerat();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});

document.querySelectorAll('.od-seg button').forEach(function(b) {
    b.addEventListener('click', function() {
        filterGrup = b.dataset.grup;

        document.querySelectorAll('.od-seg button').forEach(function(x) {
            x.classList.toggle('active', x === b);
        });

        render();
    });
});

document.getElementById('odSearch').addEventListener('input', function() {
    kataKunci = this.value;
    render();
});

hitung();
render();
updateNotifications();
</script>

<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
