<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SWASH') | SWASH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    @stack('styles')
</head>
<body>
@php
    $name = auth()->user()->name ?? 'Pelanggan';
    $initial = strtoupper(substr($name, 0, 1));

    // [route, label, pola route untuk menu aktif]
    $menu = [
        ['customer.dashboard', 'Dashboard', 'customer.dashboard'],
        ['customer.orders.create', 'Buat Pesanan', 'customer.orders.create'],
        ['customer.orders.index', 'Pesanan Saya', 'customer.orders.index'],
        ['customer.schedule', 'Antar Jemput', 'customer.schedule'],
        ['customer.payments.index', 'Pembayaran', 'customer.payments.*'],
        ['customer.profile.edit', 'Profil', 'customer.profile.*'],
    ];
@endphp
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
            @foreach ($menu as [$route, $label, $pattern])
                <a href="{{ route($route) }}" class="sidebar-link {{ request()->routeIs($pattern) ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
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
        @yield('content')

        <footer class="admin-footer"><span>SWASH Laundry Service</span><span>Dashboard Pelanggan</span></footer>
    </main>
</div>
@stack('scripts')
</body>
</html>