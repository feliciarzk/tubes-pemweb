```php
@php
    $jadwal = [
        ['jam' => '09.00', 'order' => 'SW-00131', 'pelanggan' => 'Nadia A.', 'alamat' => 'Jl. Melati No. 12', 'jenis' => 'Pickup', 'driver' => 'Bagas', 'status' => 'Menunggu'],
        ['jam' => '10.30', 'order' => 'SW-00130', 'pelanggan' => 'Rafi F.', 'alamat' => 'Jl. Kenanga No. 8', 'jenis' => 'Pickup', 'driver' => 'Rizky', 'status' => 'Dijadwalkan'],
        ['jam' => '11.00', 'order' => 'SW-00129', 'pelanggan' => 'Dina P.', 'alamat' => 'Jl. Mawar No. 4', 'jenis' => 'Antar', 'driver' => 'Sari', 'status' => 'Dijadwalkan'],
        ['jam' => '13.00', 'order' => 'SW-00126', 'pelanggan' => 'Sari L.', 'alamat' => 'Jl. Dahlia No. 21', 'jenis' => 'Antar', 'driver' => 'Bagas', 'status' => 'Selesai'],
        ['jam' => '14.00', 'order' => 'SW-00128', 'pelanggan' => 'Tono W.', 'alamat' => 'Jl. Cempaka No. 9', 'jenis' => 'Pickup', 'driver' => 'Deni', 'status' => 'Dijadwalkan'],
        ['jam' => '15.30', 'order' => 'SW-00125', 'pelanggan' => 'Andi S.', 'alamat' => 'Jl. Anggrek No. 5', 'jenis' => 'Antar', 'driver' => 'Rizky', 'status' => 'Perlu Konfirmasi'],
        ['jam' => '08.00', 'order' => 'SW-00124', 'pelanggan' => 'Budi H.', 'alamat' => 'Jl. Kamboja No. 17', 'jenis' => 'Pickup', 'driver' => 'Deni', 'status' => 'Selesai'],
    ];

    $driverList = ['Bagas', 'Rizky', 'Deni', 'Sari'];

    $tanggalHariIni = now()->locale('id')->translatedFormat('l, d F Y');

    $notif = collect($jadwal)
        ->whereIn('status', ['Menunggu', 'Perlu Konfirmasi'])
        ->count();
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
</head>

<body>

<div class="admin-layout">

    @include('admin.partials.sidebar', ['active' => 'jadwal'])

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <span class="topbar-label">SWASH / ADMIN</span>
                <h1>Jadwal Pickup</h1>
            </div>

            <div class="topbar-actions">
                @include('admin.partials.notifications', ['count' => $notif])
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">PENJEMPUTAN &amp; PENGANTARAN</p>
                <h2>Jadwal Hari Ini</h2>
                <p>
                    {{ $tanggalHariIni }}.
                    Atur jadwal pengambilan dan pengantaran pakaian pelanggan.
                </p>
            </div>

            <button type="button" class="primary-button" id="btnTambah">
                Tambah Jadwal
            </button>
        </section>

        <section class="stats-grid">

            <article class="stat-card">
                <div class="stat-heading">
                    <span>Total Jadwal</span>
                </div>
                <strong class="stat-value" id="stTotal">0</strong>
                <p>Pickup dan pengantaran hari ini</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading">
                    <span>Pickup Belum Selesai</span>
                </div>
                <strong class="stat-value" id="stPickup">0</strong>
                <p>Perlu dijemput</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading">
                    <span>Pengantaran Belum Selesai</span>
                </div>
                <strong class="stat-value" id="stAntar">0</strong>
                <p>Pesanan siap dikirim</p>
            </article>

            <article class="stat-card">
                <div class="stat-heading">
                    <span>Selesai</span>
                </div>
                <strong class="stat-value" id="stSelesai">0</strong>
                <p>Jadwal terselesaikan</p>
            </article>

        </section>

        <section class="panel orders-panel">

            <div class="panel-heading">
                <div>
                    <span class="panel-eyebrow">AGENDA OPERASIONAL</span>
                    <h3>Jadwal Terdekat</h3>
                </div>
            </div>

            <div class="jd-flash" id="jdFlash" role="status" aria-live="polite"></div>

            <div class="jd-tools">

                <div class="jd-seg" role="group" aria-label="Filter jenis jadwal">
                    <button type="button" class="active" data-jenis="Semua">Semua</button>
                    <button type="button" data-jenis="Pickup">Pickup</button>
                    <button type="button" data-jenis="Antar">Antar</button>
                </div>

                <input
                    type="search"
                    class="jd-search"
                    id="jdSearch"
                    placeholder="Cari pesanan, pelanggan, atau driver"
                    aria-label="Cari jadwal"
                >

            </div>

            <div class="table-responsive">

                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Alamat</th>
                            <th>Jenis</th>
                            <th>Driver</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="jdRows"></tbody>
                </table>

            </div>

            <p class="demo-note">
                Alamat dan jadwal di atas merupakan data ilustrasi dan belum tersimpan di database.
            </p>

        </section>

        <footer class="admin-footer">
            <span>SWASH Laundry Service</span>
            <span>Panel Administrasi</span>
        </footer>

    </main>

</div>


{{-- MODAL TAMBAH JADWAL --}}

<div class="jd-modal" id="jdModal" aria-hidden="true">

    <div class="jd-dialog" role="dialog" aria-modal="true" aria-labelledby="jdTitle">

        <h3 id="jdTitle">Tambah Jadwal</h3>

        <div class="jd-row2">

            <div class="jd-field">
                <label for="fOrder">ID Pesanan</label>
                <input id="fOrder" type="text" placeholder="SW-00132">
            </div>

            <div class="jd-field">
                <label for="fPelanggan">Pelanggan</label>
                <input id="fPelanggan" type="text" placeholder="Nama pelanggan">
            </div>

        </div>

        <div class="jd-field">
            <label for="fAlamat">Alamat</label>
            <input id="fAlamat" type="text" placeholder="Alamat penjemputan atau pengantaran">
        </div>

        <div class="jd-row2">

            <div class="jd-field">
                <label for="fJenis">Jenis</label>

                <select id="fJenis">
                    <option value="Pickup">Pickup</option>
                    <option value="Antar">Antar</option>
                </select>
            </div>

            <div class="jd-field">
                <label for="fJam">Jam</label>
                <input id="fJam" type="time" value="09:00">
            </div>

        </div>

        <div class="jd-field">
            <label for="fDriver">Driver</label>

            <select id="fDriver">
                @foreach ($driverList as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                @endforeach
            </select>
        </div>

        <div class="jd-actions">
            <button type="button" class="jd-btn ghost" id="btnBatal">
                Batal
            </button>

            <button type="button" class="jd-btn" id="btnSimpan">
                Simpan
            </button>
        </div>

    </div>

</div>


<script>
(function () {
    'use strict';

    let jadwal = @json($jadwal);

    const statusClass = {
        'Menunggu': 'status-waiting',
        'Dijadwalkan': 'status-processing',
        'Selesai': 'status-complete',
        'Perlu Konfirmasi': 'status-payment'
    };

    let filterJenis = 'Semua';
    let kataKunci = '';
    let flashTimer = null;

    const el = id => document.getElementById(id);

    function esc(value) {
        const div = document.createElement('div');
        div.textContent = String(value ?? '');
        return div.innerHTML;
    }

    function urutJam(a, b) {
        return a.jam.localeCompare(b.jam);
    }

    function hitungStatistik() {
        const pickup = jadwal.filter(j =>
            j.jenis === 'Pickup' && j.status !== 'Selesai'
        ).length;

        const antar = jadwal.filter(j =>
            j.jenis === 'Antar' && j.status !== 'Selesai'
        ).length;

        const selesai = jadwal.filter(j =>
            j.status === 'Selesai'
        ).length;

        el('stTotal').textContent = jadwal.length;
        el('stPickup').textContent = pickup;
        el('stAntar').textContent = antar;
        el('stSelesai').textContent = selesai;
    }

    function render() {
        const q = kataKunci.toLowerCase().trim();

        const tampil = jadwal
            .slice()
            .sort(urutJam)
            .filter(j => {
                if (filterJenis !== 'Semua' && j.jenis !== filterJenis) {
                    return false;
                }

                const data = [
                    j.order,
                    j.pelanggan,
                    j.driver,
                    j.alamat,
                    j.status
                ].join(' ').toLowerCase();

                return data.includes(q);
            });

        const tbody = el('jdRows');

        if (tampil.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="jd-empty">
                        Tidak ada jadwal yang cocok.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = tampil.map(j => {
            const jenisClass = j.jenis === 'Pickup' ? 'pickup' : 'antar';

            return `
                <tr>
                    <td class="jd-time">${esc(j.jam)}</td>
                    <td class="order-id">${esc(j.order)}</td>
                    <td>${esc(j.pelanggan)}</td>
                    <td>${esc(j.alamat)}</td>
                    <td>
                        <span class="jd-type ${jenisClass}">
                            ${esc(j.jenis)}
                        </span>
                    </td>
                    <td>${esc(j.driver)}</td>
                    <td>
                        <span class="order-status ${statusClass[j.status] || 'status-waiting'}">
                            ${esc(j.status)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function bukaModal() {
        const modal = el('jdModal');

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');

        el('fOrder').focus();
    }

    function tutupModal() {
        const modal = el('jdModal');

        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');

        el('btnTambah').focus();
    }

    function tampilkanPesan(pesan) {
        const flash = el('jdFlash');

        flash.textContent = pesan;
        flash.classList.add('show');

        clearTimeout(flashTimer);

        flashTimer = setTimeout(() => {
            flash.classList.remove('show');
        }, 4000);
    }

    function resetForm() {
        el('fOrder').value = '';
        el('fPelanggan').value = '';
        el('fAlamat').value = '';
        el('fJenis').value = 'Pickup';
        el('fJam').value = '09:00';
    }

    function simpan() {
        const order = el('fOrder').value.trim();
        const pelanggan = el('fPelanggan').value.trim();
        const alamat = el('fAlamat').value.trim();
        const jam = el('fJam').value;

        if (!order || !pelanggan || !alamat || !jam) {
            alert('Lengkapi seluruh data jadwal terlebih dahulu.');
            return;
        }

        const duplikat = jadwal.some(j =>
            j.order.toLowerCase() === order.toLowerCase()
        );

        if (duplikat) {
            alert('ID pesanan sudah terdaftar dalam jadwal.');
            return;
        }

        jadwal.push({
            jam: jam.replace(':', '.'),
            order: order,
            pelanggan: pelanggan,
            alamat: alamat,
            jenis: el('fJenis').value,
            driver: el('fDriver').value,
            status: 'Dijadwalkan'
        });

        resetForm();
        tutupModal();

        hitungStatistik();
        render();

        tampilkanPesan(
            'Jadwal untuk pesanan ' + order + ' berhasil ditambahkan.'
        );
    }

    // FILTER JENIS JADWAL

    document.querySelectorAll('.jd-seg button').forEach(button => {
        button.addEventListener('click', function () {
            filterJenis = this.dataset.jenis;

            document.querySelectorAll('.jd-seg button').forEach(item => {
                item.classList.toggle('active', item === this);
            });

            render();
        });
    });

    // PENCARIAN

    el('jdSearch').addEventListener('input', function () {
        kataKunci = this.value;
        render();
    });

    // MODAL

    el('btnTambah').addEventListener('click', bukaModal);
    el('btnBatal').addEventListener('click', tutupModal);
    el('btnSimpan').addEventListener('click', simpan);

    el('jdModal').addEventListener('click', function (event) {
        if (event.target === this) {
            tutupModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            el('jdModal').classList.contains('open')
        ) {
            tutupModal();
        }
    });

    // INISIALISASI

    hitungStatistik();
    render();

})();
</script>

<script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>
```