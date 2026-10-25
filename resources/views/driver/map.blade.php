@extends('layouts.driver')
@section('title', 'Peta')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    <h1 class="page-title">Peta</h1>
    <p class="page-sub" data-track-status data-state="off">Pelacakan mati</p>

    <div id="fullMap" class="map-box is-tall" data-map="{{ json_encode($mapData) }}"></div>
    <div class="map-legend">
        <span><i class="lg-jemput"></i>Jemput</span><span><i class="lg-antar"></i>Antar</span>
        <span><i class="lg-outlet"></i>Outlet</span><span><i class="lg-me"></i>Posisimu</span>
    </div>

    <button type="button" class="btn btn-primary locate-btn" data-action="locate"><i class="bi bi-crosshair"></i>Ke posisiku</button>
    <a href="{{ route('driver.settings') }}" class="btn btn-ghost locate-btn"><i class="bi bi-gear"></i>Pengaturan peta dan lokasi</a>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            SwashDriver.initMap(document.getElementById('fullMap'), { zoomControl: true });
        });
    </script>
@endpush