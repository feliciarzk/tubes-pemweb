<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="driver-location-url" content="{{ route('driver.location.store') }}">
    <title>@yield('title', 'Driver') | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/driver.css') }}">
    @stack('head')
</head>
<body>
    <header class="driver-bar">
        <div class="driver-wrap">
            <div>
                <div class="driver-brand"><i class="bi bi-droplet-half"></i> SWASH Driver</div>
                <div class="driver-hello">Halo, {{ $driverName ?? 'Driver' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="driver-logout">Keluar</button>
            </form>
        </div>
    </header>

    <main class="driver-wrap">
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <nav class="bottom-nav" aria-label="Menu driver">
        <div class="driver-wrap bottom-inner">
            <a href="{{ route('driver.dashboard') }}" class="bottom-link {{ request()->routeIs('driver.dashboard', 'driver.tasks.*') ? 'active' : '' }}"><i class="bi bi-house-door"></i>Beranda</a>
            <a href="{{ route('driver.map') }}" class="bottom-link {{ request()->routeIs('driver.map') ? 'active' : '' }}"><i class="bi bi-map"></i>Peta</a>
            <a href="{{ route('driver.settings') }}" class="bottom-link {{ request()->routeIs('driver.settings') ? 'active' : '' }}"><i class="bi bi-gear"></i>Pengaturan</a>
        </div>
    </nav>

    <script src="{{ asset('js/driver.js') }}"></script>
    @stack('scripts')
</body>
</html>