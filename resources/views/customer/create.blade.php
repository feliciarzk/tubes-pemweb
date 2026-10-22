@php
    $name = auth()->user()->name ?? 'Pelanggan';
    $initial = strtoupper(substr($name, 0, 1));

    $tipe = old('tipe', 'antar_jemput');
    $layanan = old('layanan', 'cuci_setrika');
    $waktu = old('waktu', '');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <a href="{{ url('/') }}" class="admin-brand" aria-label="SWASH">
            <svg class="brand-logo" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/>
                <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                <path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <span class="brand-copy"><strong>SWASH</strong><small>Laundry Service</small></span>
        </a>

        <p class="sidebar-label">MENU</p>
        <nav class="sidebar-nav">
            <a href="{{ url('/dashboard') }}" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ url('/pesanan/baru') }}" class="sidebar-link active">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
                Buat Pesanan
            </a>
            {{-- Halaman di bawah ini belum dibuat, jadi href masih '#' --}}
            <a href="#" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
                Pesanan Saya
            </a>
            <a href="#" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></svg>
                Pembayaran
            </a>
            <a href="#" class="sidebar-link">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                Profil
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-profile">
                <div class="profile-avatar">{{ $initial }}</div>
                <div><strong>{{ $name }}</strong><small>Pelanggan</small></div>
            </div>
            <a class="back-home" href="{{ url('/') }}">Kembali ke halaman utama</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div><span class="topbar-label">SWASH / PELANGGAN</span><h1>Buat Pesanan</h1></div>
            <div class="topbar-date"><span class="status-dot"></span>Isi data pesanan</div>
        </header>

        <section class="dashboard-grid">
            <article class="panel">
                <form method="POST" action="{{ url('/pesanan') }}" class="form">
                    @csrf

                    <div>
                        <p class="group-title">Jenis layanan antar</p>
                        <div class="option-grid">
                            <label class="option">
                                <input type="radio" name="tipe" value="antar_jemput" {{ $tipe === 'antar_jemput' ? 'checked' : '' }}>
                                <span class="option-body"><strong>Antar Jemput</strong><span>Driver menjemput cucian ke tempatmu, lalu mengantar kembali setelah selesai.</span></span>
                            </label>
                            <label class="option">
                                <input type="radio" name="tipe" value="antar_saja" {{ $tipe === 'antar_saja' ? 'checked' : '' }}>
                                <span class="option-body"><strong>Antar Saja</strong><span>Kamu membawa cucian sendiri, hasilnya diantar driver ke tempatmu.</span></span>
                            </label>
                        </div>
                        @error('tipe') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <p class="group-title">Pilih layanan</p>
                        <div class="option-grid">
                            <label class="option">
                                <input type="radio" name="layanan" value="cuci_setrika" {{ $layanan === 'cuci_setrika' ? 'checked' : '' }}>
                                <span class="option-body"><strong>Cuci &amp; Setrika</strong><span>Dicuci bersih lalu disetrika rapi.</span></span>
                            </label>
                            <label class="option">
                                <input type="radio" name="layanan" value="cuci_kering" {{ $layanan === 'cuci_kering' ? 'checked' : '' }}>
                                <span class="option-body"><strong>Cuci Kering</strong><span>Dicuci dan dikeringkan, tanpa setrika.</span></span>
                            </label>
                            <label class="option">
                                <input type="radio" name="layanan" value="setrika_saja" {{ $layanan === 'setrika_saja' ? 'checked' : '' }}>
                                <span class="option-body"><strong>Setrika Saja</strong><span>Untuk baju bersih yang perlu dirapikan.</span></span>
                            </label>
                        </div>
                        @error('layanan') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label for="tanggal" id="label-tanggal">Tanggal penjemputan</label>
                            <input type="date" id="tanggal" name="tanggal" class="input @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" min="{{ now()->format('Y-m-d') }}" required>
                            @error('tanggal') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="waktu">Perkiraan waktu</label>
                            <select id="waktu" name="waktu" class="input @error('waktu') is-invalid @enderror" required>
                                <option value="" disabled {{ $waktu === '' ? 'selected' : '' }}>Pilih waktu</option>
                                <option value="pagi" {{ $waktu === 'pagi' ? 'selected' : '' }}>Pagi (08.00–11.00)</option>
                                <option value="siang" {{ $waktu === 'siang' ? 'selected' : '' }}>Siang (11.00–14.00)</option>
                                <option value="sore" {{ $waktu === 'sore' ? 'selected' : '' }}>Sore (14.00–17.00)</option>
                            </select>
                            @error('waktu') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <p class="hint" style="margin-top:-12px">Jam pasti ditentukan admin setelah pesananmu dikonfirmasi.</p>

                    <div class="field">
                        <label for="alamat">Alamat lengkap</label>
                        <textarea id="alamat" name="alamat" class="input @error('alamat') is-invalid @enderror" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan" required>{{ old('alamat') }}</textarea>
                        <span class="hint" id="hint-alamat">Alamat dipakai untuk penjemputan dan pengantaran.</span>
                        @error('alamat') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label for="patokan">Patokan (opsional)</label>
                            <input type="text" id="patokan" name="patokan" class="input" value="{{ old('patokan') }}" placeholder="Contoh: pagar hijau, depan warung">
                            @error('patokan') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="telepon">Nomor HP / WhatsApp</label>
                            <input type="tel" id="telepon" name="telepon" class="input @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
                            @error('telepon') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="catatan">Catatan (opsional)</label>
                        <textarea id="catatan" name="catatan" class="input" placeholder="Contoh: pisahkan baju putih, ada noda di kemeja">{{ old('catatan') }}</textarea>
                        @error('catatan') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Pesanan</button>
                    </div>
                </form>
            </article>

            <article class="panel">
                <div class="panel-heading"><div><span class="panel-eyebrow">ALUR</span><h3>Setelah Kamu Pesan</h3></div></div>
                <ol class="info-steps">
                    <li><strong>Admin mengonfirmasi</strong>Kamu dapat notifikasi berisi jadwal dan jam kedatangan driver.</li>
                    <li><strong>Cucian ditimbang</strong>Driver menimbang di tempat, lalu admin memperbarui berat dan estimasi tagihan.</li>
                    <li><strong>Bayar</strong>Pesanan mulai diproses setelah pembayaran dikonfirmasi.</li>
                    <li><strong>Diproses lalu diantar</strong>Biasanya 1–2 hari. Saat diantar, posisi driver bisa kamu pantau di peta.</li>
                </ol>
            </article>
        </section>

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Dashboard Pelanggan</span></footer>
    </main>
</div>

<script>
    const teks = {
        antar_jemput: { tanggal: 'Tanggal penjemputan', alamat: 'Alamat dipakai untuk penjemputan dan pengantaran.' },
        antar_saja:   { tanggal: 'Tanggal kamu mengantar cucian', alamat: 'Alamat dipakai untuk pengantaran hasil cucian.' }
    };

    function perbaruiLabel() {
        const dipilih = document.querySelector('input[name="tipe"]:checked');
        if (!dipilih) return;
        document.getElementById('label-tanggal').textContent = teks[dipilih.value].tanggal;
        document.getElementById('hint-alamat').textContent = teks[dipilih.value].alamat;
    }

    document.querySelectorAll('input[name="tipe"]').forEach(function (radio) {
        radio.addEventListener('change', perbaruiLabel);
    });
    perbaruiLabel();
</script>
</body>
</html>