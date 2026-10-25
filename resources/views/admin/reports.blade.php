@php
    $laporan = [
        'minggu' => [
            'periode'  => '19 – 25 Okt 2026',
            'mulai'    => '2026-10-19',
            'akhir'    => '2026-10-25',
            'judul'    => 'Minggu Ini',
            'masuk'    => 32,
            'selesai'  => 27,
            'proses'   => 3,
            'menunggu' => 2,
            'batal'    => 0,
            'uang'     => 1200000,
            'grafik'   => [
                ['label' => 'Sen', 'nilai' => 4],
                ['label' => 'Sel', 'nilai' => 5],
                ['label' => 'Rab', 'nilai' => 6],
                ['label' => 'Kam', 'nilai' => 3],
                ['label' => 'Jum', 'nilai' => 6],
                ['label' => 'Sab', 'nilai' => 5],
                ['label' => 'Min', 'nilai' => 3],
            ],
            'grafikJudul' => 'Pesanan per Hari',
        ],
        'bulan' => [
            'periode'  => 'Oktober 2026',
            'mulai'    => '2026-10-01',
            'akhir'    => '2026-10-25',
            'judul'    => 'Bulan Ini',
            'masuk'    => 128,
            'selesai'  => 107,
            'proses'   => 9,
            'menunggu' => 7,
            'batal'    => 5,
            'uang'     => 4800000,
            'grafik'   => [
                ['label' => 'Mgg 1', 'nilai' => 31],
                ['label' => 'Mgg 2', 'nilai' => 34],
                ['label' => 'Mgg 3', 'nilai' => 33],
                ['label' => 'Mgg 4', 'nilai' => 30],
            ],
            'grafikJudul' => 'Pesanan per Minggu',
        ],
        'tahun' => [
            'periode'  => 'Tahun 2026',
            'mulai'    => '2026-01-01',
            'akhir'    => '2026-10-25',
            'judul'    => 'Tahun Ini',
            'masuk'    => 1240,
            'selesai'  => 1061,
            'proses'   => 78,
            'menunggu' => 61,
            'batal'    => 40,
            'uang'     => 46500000,
            'grafik'   => [
                ['label' => 'Jan', 'nilai' => 108],
                ['label' => 'Feb', 'nilai' => 112],
                ['label' => 'Mar', 'nilai' => 118],
                ['label' => 'Apr', 'nilai' => 121],
                ['label' => 'Mei', 'nilai' => 125],
                ['label' => 'Jun', 'nilai' => 130],
                ['label' => 'Jul', 'nilai' => 128],
                ['label' => 'Agu', 'nilai' => 135],
                ['label' => 'Sep', 'nilai' => 135],
                ['label' => 'Okt', 'nilai' => 128],
            ],
            'grafikJudul' => 'Pesanan per Bulan',
        ],
    ];
    $notif = 3;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan | SWASH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .periode-bar { display:flex; width:fit-content; gap:.25rem; padding:.3rem; background:#eef4f5; border-radius:12px; margin:0 0 1.25rem; }
        .report-lines { display:flex; flex-direction:column; margin-top:.5rem; }
        .report-lines > div { display:flex; justify-content:space-between; align-items:center; gap:1rem; padding:.85rem 0; border-bottom:1px solid #e8eef0; }
        .report-lines > div:last-child { border-bottom:0; }
        .report-lines span { color:#5b7482; }
        .report-lines strong { color:#16323f; font-size:1.02rem; }
        .filter-btn { padding:.5rem 1.2rem; border:0; border-radius:9px; background:transparent; font:inherit; font-weight:600; color:#5b7482; cursor:pointer; }
        .filter-btn:hover { color:#087f7c; }
        .filter-btn.active { background:#fff; color:#087f7c; box-shadow:0 1px 4px rgba(15,30,40,.12); }
        .bar-chart { display:flex; align-items:flex-end; gap:.6rem; height:300px; padding-top:1rem; }
        .bar-col { flex:1; min-width:0; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; gap:.35rem; }
        .bar-value { font-size:.8rem; font-weight:700; color:#33505e; }
        .bar-fill { width:100%; max-width:42px; background:#0a9e9a; border-radius:8px 8px 0 0; min-height:4px; transition:height .3s ease; }
        .bar-label { font-size:.78rem; color:#6b8290; white-space:nowrap; }
    </style>
</head>
<body>
<div class="admin-layout">
    @include('admin.sidebar', ['active' => 'laporan'])

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / ADMIN</span><h1>Laporan</h1></div>
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
                <p class="welcome-label">ANALISIS USAHA</p>
                <h2>Laporan Laundry</h2>
                <p>Lihat ringkasan pesanan dan pendapatan untuk membantu evaluasi operasional.</p>
            </div>
            <button type="button" class="primary-button" id="btnExcel">Unduh Excel</button>
        </section>

        <div class="periode-bar" role="group" aria-label="Filter periode laporan">
            <button type="button" class="filter-btn" data-periode="minggu" aria-pressed="false">Minggu</button>
            <button type="button" class="filter-btn active" data-periode="bulan" aria-pressed="true">Bulan</button>
            <button type="button" class="filter-btn" data-periode="tahun" aria-pressed="false">Tahun</button>
        </div>

        <section class="stats-grid">
            <article class="stat-card"><div class="stat-heading"><span id="lblMasuk">Pesanan Bulan Ini</span></div><strong class="stat-value" id="statMasuk"></strong><p>Jumlah pesanan</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pendapatan</span></div><strong class="stat-value stat-money" id="statUang"></strong><p>Total transaksi lunas</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Rata-rata Transaksi</span></div><strong class="stat-value" id="statRata"></strong><p>Per pesanan</p></article>
            <article class="stat-card"><div class="stat-heading"><span>Pesanan Selesai</span></div><strong class="stat-value" id="statSelesai"></strong><p>Pesanan selesai diproses</p></article>
        </section>

        <section class="dashboard-grid">
            <article class="panel">
                <div class="panel-heading">
                    <div><span class="panel-eyebrow">PERIODE</span><h3>Ringkasan</h3></div>
                    <span class="panel-period" id="periodeLabel"></span>
                </div>
                <div class="report-lines">
                    <div><span>Jumlah pesanan masuk</span><strong id="lineMasuk"></strong></div>
                    <div><span>Pesanan selesai</span><strong id="lineSelesai"></strong></div>
                    <div><span>Pesanan dalam proses</span><strong id="lineProses"></strong></div>
                    <div><span>Menunggu pembayaran</span><strong id="lineMenunggu"></strong></div>
                    <div><span>Pesanan dibatalkan</span><strong id="lineBatal"></strong></div>
                    <div><span>Total pendapatan lunas</span><strong id="lineUang"></strong></div>
                </div>
                <p class="demo-note">Angka pada halaman ini masih data contoh. Setelah database dan transaksi selesai dibuat, laporan dihitung dari data pesanan dan pembayaran yang sebenarnya.</p>
            </article>

            <article class="panel">
                <div class="panel-heading">
                    <div><span class="panel-eyebrow">TREN</span><h3 id="grafikJudul"></h3></div>
                </div>
                <div class="bar-chart" id="barChart"></div>
            </article>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Panel Administrasi</span></footer>
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
var laporan = @json($laporan);
var periodeAktif = 'bulan';

function rupiah(n) {
    return 'Rp' + Number(n).toLocaleString('id-ID');
}

function rupiahRingkas(n) {
    if (n >= 1000000) {
        return 'Rp' + (n / 1000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' jt';
    }
    if (n >= 1000) {
        return 'Rp' + (n / 1000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' rb';
    }
    return rupiah(n);
}

function setText(id, teks) {
    document.getElementById(id).textContent = teks;
}

function tampilkan(kunci) {
    periodeAktif = kunci;
    var d = laporan[kunci];
    var rata = d.masuk > 0 ? d.uang / d.masuk : 0;

    setText('lblMasuk', 'Pesanan ' + d.judul);
    setText('statMasuk', d.masuk.toLocaleString('id-ID'));
    setText('statUang', rupiahRingkas(d.uang));
    setText('statRata', rupiahRingkas(rata));
    setText('statSelesai', d.selesai.toLocaleString('id-ID'));

    setText('periodeLabel', d.periode);
    setText('lineMasuk', d.masuk.toLocaleString('id-ID'));
    setText('lineSelesai', d.selesai.toLocaleString('id-ID'));
    setText('lineProses', d.proses.toLocaleString('id-ID'));
    setText('lineMenunggu', d.menunggu.toLocaleString('id-ID'));
    setText('lineBatal', d.batal.toLocaleString('id-ID'));
    setText('lineUang', rupiah(d.uang));

    setText('grafikJudul', d.grafikJudul);
    var maks = Math.max.apply(null, d.grafik.map(function (g) { return g.nilai; }));
    var chart = document.getElementById('barChart');
    chart.innerHTML = '';
    d.grafik.forEach(function (g) {
        var kolom = document.createElement('div');
        kolom.className = 'bar-col';
        var tinggi = maks > 0 ? Math.round(g.nilai / maks * 220) : 0;
        kolom.innerHTML = '<span class="bar-value">' + g.nilai + '</span><div class="bar-fill" style="height:' + tinggi + 'px"></div><span class="bar-label">' + g.label + '</span>';
        chart.appendChild(kolom);
    });

    document.querySelectorAll('.filter-btn').forEach(function (b) {
        var aktif = b.dataset.periode === kunci;
        b.classList.toggle('active', aktif);
        b.setAttribute('aria-pressed', aktif ? 'true' : 'false');
    });
}

document.querySelectorAll('.filter-btn').forEach(function (b) {
    b.addEventListener('click', function () { tampilkan(b.dataset.periode); });
});

var namaPelanggan = [
    'Nadia Anggraini', 'Rafi Firmansyah', 'Dina Puspita', 'Tono Wijaya', 'Andi Saputra',
    'Sari Lestari', 'Budi Hartono', 'Maya Kusuma', 'Joko Prasetyo', 'Lina Rahayu',
    'Putri Ayu', 'Bagus Setiawan', 'Citra Dewi', 'Eko Nugroho', 'Fitri Handayani',
    'Gilang Ramadhan', 'Hana Salsabila', 'Irfan Maulana', 'Jihan Aulia', 'Kevin Pratama'
];
var layananList = ['Cuci & Setrika', 'Cuci Kering', 'Setrika Saja'];
var metodeList = ['Transfer Bank', 'QRIS', 'Tunai'];
var selisih = [-15000, -10000, -5000, 0, 5000, 10000, 15000, -5000, 5000, 0];

function pad(n, panjang) {
    var s = String(n);
    while (s.length < panjang) s = '0' + s;
    return s;
}

function susunStatus(d) {
    var status = [];
    for (var i = 0; i < d.masuk; i++) status.push('Selesai');
    var tertunda = d.proses + d.menunggu;
    var batasAwal = d.masuk - tertunda;
    for (var k = 0; k < d.batal; k++) {
        status[Math.floor((k + 1) * batasAwal / (d.batal + 1))] = 'Dibatalkan';
    }
    for (var m = 0; m < tertunda; m++) {
        status[batasAwal + m] = m < d.menunggu ? 'Menunggu Pembayaran' : 'Diproses';
    }
    return status;
}

function susunPesanan(kunci) {
    var d = laporan[kunci];
    var status = susunStatus(d);
    var rata = d.selesai > 0 ? d.uang / d.selesai : 0;
    var awal = new Date(d.mulai + 'T00:00:00');
    var akhir = new Date(d.akhir + 'T00:00:00');
    var jumlahHari = Math.round((akhir - awal) / 86400000) + 1;
    var baris = [];
    var indeksSelesai = [];

    for (var i = 0; i < d.masuk; i++) {
        var st = status[i];
        var off = selisih[i % selisih.length];
        var total = 0;
        if (st === 'Selesai') total = Math.max(15000, Math.round((rata + off) / 500) * 500);
        else if (st !== 'Dibatalkan') total = 40000 + off;

        var tgl = new Date(awal);
        tgl.setDate(awal.getDate() + Math.floor(i * jumlahHari / d.masuk));

        baris.push({
            id: 'SW-' + pad(1240 - d.masuk + i + 1, 5),
            tanggal: tgl.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
            pelanggan: namaPelanggan[(i * 7 + 3) % namaPelanggan.length],
            jenis: i % 3 === 2 ? 'Antar Saja' : 'Antar Jemput',
            layanan: layananList[(i * 5 + 1) % layananList.length],
            metode: st === 'Selesai' ? metodeList[(i * 2 + 1) % metodeList.length] : '-',
            total: total,
            status: st,
            bayar: st === 'Selesai' ? 'Lunas' : (st === 'Dibatalkan' ? '-' : 'Belum Lunas')
        });
        if (st === 'Selesai') indeksSelesai.push(i);
    }

    var sisa = d.uang - indeksSelesai.reduce(function (s, idx) { return s + baris[idx].total; }, 0);
    var k = 0;
    while (sisa !== 0 && indeksSelesai.length && k < 100000) {
        var b = baris[indeksSelesai[k % indeksSelesai.length]];
        var langkah = sisa > 0 ? 500 : -500;
        if (b.total + langkah >= 15000) {
            b.total += langkah;
            sisa -= langkah;
        }
        k++;
    }
    return baris;
}

function unduhExcel() {
    if (typeof XLSX === 'undefined') {
        alert('Library Excel gagal dimuat. Periksa koneksi internet lalu coba lagi.');
        return;
    }
    var d = laporan[periodeAktif];
    var pesanan = susunPesanan(periodeAktif);
    var rata = d.masuk > 0 ? d.uang / d.masuk : 0;
    var sekarang = new Date();
    var dibuat = sekarang.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

    var ringkasan = [
        ['LAPORAN LAUNDRY SWASH'],
        ['Periode', d.judul + ' (' + d.periode + ')'],
        ['Dibuat pada', dibuat],
        [],
        ['Keterangan', 'Nilai'],
        ['Jumlah pesanan masuk', d.masuk],
        ['Pesanan selesai', d.selesai],
        ['Pesanan dalam proses', d.proses],
        ['Menunggu pembayaran', d.menunggu],
        ['Pesanan dibatalkan', d.batal],
        ['Total pendapatan lunas (Rp)', d.uang],
        ['Rata-rata transaksi per pesanan (Rp)', Math.round(rata)]
    ];

    var detail = [['No', 'ID Pesanan', 'Tanggal', 'Pelanggan', 'Jenis', 'Layanan', 'Metode Bayar', 'Total (Rp)', 'Status Pesanan', 'Status Bayar']];
    pesanan.forEach(function (p, i) {
        detail.push([i + 1, p.id, p.tanggal, p.pelanggan, p.jenis, p.layanan, p.metode, p.total, p.status, p.bayar]);
    });

    var peta = {};
    pesanan.forEach(function (p) {
        if (!peta[p.pelanggan]) peta[p.pelanggan] = { nama: p.pelanggan, pesanan: 0, selesai: 0, batal: 0, belanja: 0 };
        var r = peta[p.pelanggan];
        r.pesanan++;
        if (p.status === 'Selesai') {
            r.selesai++;
            r.belanja += p.total;
        }
        if (p.status === 'Dibatalkan') r.batal++;
    });
    var rekap = Object.keys(peta).map(function (n) { return peta[n]; }).sort(function (a, b) { return b.belanja - a.belanja; });

    var rekapSheet = [['No', 'Pelanggan', 'Jumlah Pesanan', 'Selesai', 'Dibatalkan', 'Total Belanja Lunas (Rp)']];
    var totalBelanja = 0;
    rekap.forEach(function (r, i) {
        totalBelanja += r.belanja;
        rekapSheet.push([i + 1, r.nama, r.pesanan, r.selesai, r.batal, r.belanja]);
    });
    rekapSheet.push(['', 'TOTAL', d.masuk, d.selesai, d.batal, totalBelanja]);

    var wb = XLSX.utils.book_new();

    var s1 = XLSX.utils.aoa_to_sheet(ringkasan);
    s1['!cols'] = [{ wch: 38 }, { wch: 30 }];
    XLSX.utils.book_append_sheet(wb, s1, 'Ringkasan');

    var s2 = XLSX.utils.aoa_to_sheet(detail);
    s2['!cols'] = [{ wch: 6 }, { wch: 12 }, { wch: 14 }, { wch: 22 }, { wch: 14 }, { wch: 16 }, { wch: 15 }, { wch: 12 }, { wch: 22 }, { wch: 14 }];
    XLSX.utils.book_append_sheet(wb, s2, 'Detail Pesanan');

    var s3 = XLSX.utils.aoa_to_sheet(rekapSheet);
    s3['!cols'] = [{ wch: 6 }, { wch: 24 }, { wch: 16 }, { wch: 10 }, { wch: 13 }, { wch: 26 }];
    XLSX.utils.book_append_sheet(wb, s3, 'Rekap Pelanggan');

    var iso = sekarang.getFullYear() + '-' + pad(sekarang.getMonth() + 1, 2) + '-' + pad(sekarang.getDate(), 2);
    XLSX.writeFile(wb, 'Laporan-SWASH-' + periodeAktif + '-' + iso + '.xlsx');
}

document.getElementById('btnExcel').addEventListener('click', unduhExcel);

tampilkan('bulan');
</script>
</body>
</html>