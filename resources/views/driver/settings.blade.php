@extends('layouts.driver')
@section('title', 'Pengaturan')

@section('content')
    <h1 class="page-title">Pengaturan</h1>
    <p class="page-sub">Pengaturan tersimpan di HP ini.</p>

    <article class="task-card">
        <h2 class="card-title">Profil driver</h2>
        <div class="info-row"><span>Nama</span><strong>{{ $driver['name'] }}</strong></div>
        <div class="info-row"><span>ID driver</span><strong>{{ $driver['code'] }}</strong></div>
        <div class="info-row"><span>No. HP</span><strong>{{ $driver['phone'] }}</strong></div>
        <div class="info-row"><span>Kendaraan</span><strong>{{ $driver['vehicle'] }} · {{ $driver['plate'] }}</strong></div>
        <p class="note">Data profil diatur oleh admin.</p>
    </article>

    <article class="task-card">
        <h2 class="card-title">Status kerja</h2>
        <label class="switch-row">
            <span class="switch-text"><strong>Online</strong><small>Saat offline, lokasi tidak dibagikan</small></span>
            <input type="checkbox" class="switch" data-setting="online">
        </label>
    </article>

    <article class="task-card">
        <h2 class="card-title">Pelacakan lokasi</h2>
        <label class="switch-row">
            <span class="switch-text"><strong>Bagikan lokasi ke admin</strong><small>Posisimu tampil di peta monitoring admin</small></span>
            <input type="checkbox" class="switch" data-setting="share">
        </label>
        <label class="switch-row">
            <span class="switch-text"><strong>Akurasi tinggi (GPS)</strong><small>Lebih akurat, baterai lebih cepat habis</small></span>
            <input type="checkbox" class="switch" data-setting="accurate">
        </label>
        <label class="switch-row">
            <span class="switch-text"><strong>Kirim setiap</strong><small>Interval makin pendek, makin boros baterai dan data</small></span>
            <select class="select" data-setting="interval">
                <option value="5">5 detik</option>
                <option value="10">10 detik</option>
                <option value="30">30 detik</option>
                <option value="60">60 detik</option>
            </select>
        </label>

        <div data-track-status data-state="off" style="margin-top:10px">Pelacakan mati</div>
        <div class="coords" data-track-coords></div>
        <button type="button" class="btn btn-ghost" data-action="test-location"><i class="bi bi-crosshair"></i>Tes baca lokasi</button>
        <p class="note">Lokasi hanya terkirim selama halaman ini terbuka di browser. Browser meminta izin lokasi saat pertama kali diaktifkan. Fitur lokasi butuh HTTPS atau alamat localhost.</p>
    </article>

    <article class="task-card">
        <h2 class="card-title">Tampilan peta</h2>
        <label class="switch-row">
            <span class="switch-text"><strong>Tampilkan titik tugas</strong><small>Penanda jemput dan antar di peta</small></span>
            <input type="checkbox" class="switch" data-setting="showTasks">
        </label>
        <label class="switch-row">
            <span class="switch-text"><strong>Ikuti posisiku</strong><small>Peta bergeser otomatis mengikuti lokasimu</small></span>
            <input type="checkbox" class="switch" data-setting="follow">
        </label>
    </article>

    <button type="button" class="btn btn-text" data-action="reset">Kembalikan pengaturan awal</button>
@endsection