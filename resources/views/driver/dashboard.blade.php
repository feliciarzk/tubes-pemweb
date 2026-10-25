@extends('layouts.driver')
@section('title', 'Beranda')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    @php
        $sorted = $tasks->sortBy('time')->values();
        $next   = $sorted->first();
    @endphp

    {{-- Status kerja & pelacakan --}}
    <section class="task-card status-card">
        <div class="status-title"><span class="state-dot" data-online-dot></span><span data-online-label>Online</span></div>
        <div data-track-status data-state="off" style="margin:2px 0 8px 18px">Pelacakan mati</div>

        <label class="switch-row">
            <span class="switch-text"><strong>Online</strong><small>Matikan saat istirahat atau selesai bertugas</small></span>
            <input type="checkbox" class="switch" data-setting="online">
        </label>
        <label class="switch-row">
            <span class="switch-text"><strong>Bagikan lokasi ke admin</strong><small>Admin melihat posisimu di peta monitoring</small></span>
            <input type="checkbox" class="switch" data-setting="share">
        </label>
    </section>

    {{-- Statistik --}}
    <section class="stat-grid" aria-label="Ringkasan hari ini">
        <div class="stat-box"><span>Tugas hari ini</span><strong>{{ $tasks->count() }}</strong></div>
        <div class="stat-box"><span>Jemput</span><strong>{{ $tasks->where('type', 'jemput')->count() }}</strong></div>
        <div class="stat-box"><span>Antar</span><strong>{{ $tasks->where('type', 'antar')->count() }}</strong></div>
        <div class="stat-box"><span>Selesai</span><strong>{{ $doneToday }}</strong></div>
    </section>

    {{-- Tugas berikutnya --}}
    @if ($next)
        <section class="next-card">
            <div class="next-label">TUGAS BERIKUTNYA · {{ $next->time }}</div>
            <div class="next-name">{{ $next->type === 'jemput' ? 'Jemput' : 'Antar' }} · {{ $next->customer }}</div>
            <div class="next-meta"><i class="bi bi-geo-alt"></i> {{ $next->address }}</div>
            <div class="next-actions">
                <a href="{{ route('driver.tasks.show', $next->id) }}" class="btn btn-light">Buka tugas</a>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $next->lat }},{{ $next->lng }}" target="_blank" rel="noopener" class="btn btn-outline-light"><i class="bi bi-signpost-2"></i>Rute</a>
            </div>
        </section>
    @endif

    {{-- Peta mini --}}
    <article class="task-card">
        <div class="card-head">
            <h2 class="card-title">Peta tugas</h2>
            <a href="{{ route('driver.map') }}" class="card-link">Perbesar</a>
        </div>
        <div id="miniMap" class="map-box" data-map="{{ json_encode($mapData) }}"></div>
        <div class="map-legend">
            <span><i class="lg-jemput"></i>Jemput</span><span><i class="lg-antar"></i>Antar</span>
            <span><i class="lg-outlet"></i>Outlet</span><span><i class="lg-me"></i>Posisimu</span>
        </div>
    </article>

    {{-- Daftar tugas --}}
    <h2 class="card-title" style="margin-top:20px">Semua tugas</h2>
    @forelse ($sorted as $task)
        <a href="{{ route('driver.tasks.show', $task->id) }}" class="task-card">
            <div class="task-top">
                <span class="time-pill">{{ $task->time }}</span>
                <span class="chip chip-{{ $task->type }}">{{ $task->type === 'jemput' ? 'Jemput' : 'Antar' }}</span>
            </div>
            <div class="task-name">{{ $task->customer }} <span class="task-code">· {{ $task->code }}</span></div>
            <div class="task-meta"><i class="bi bi-geo-alt"></i>{{ $task->address }}</div>
            <div class="task-meta"><i class="bi bi-basket"></i>{{ $task->service }}</div>
        </a>
    @empty
        <div class="empty">Belum ada tugas. Admin akan menugaskan pesanan kepadamu.</div>
    @endforelse
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            SwashDriver.initMap(document.getElementById('miniMap'), { zoomControl: false });
        });
    </script>
@endpush