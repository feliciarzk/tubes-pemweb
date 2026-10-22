<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SWASH') · SWASH Laundry</title>

    {{-- Font: ini yang bikin tampilan jadi serif sebelumnya --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">

</head>
<body>
<div class="sw-shell">
    <aside class="sw-side">
        <a class="sw-brand" href="{{ route('customer.dashboard') }}" style="display:flex;align-items:center;gap:10px;">
            <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="#14a89a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3s6 6.2 6 10.5A6 6 0 0 1 6 13.5C6 9.2 12 3 12 3z"/>
                <path d="M9.5 14.5a2.5 2.5 0 0 0 2.5 2.5" opacity=".6"/>
            </svg>
            <span>
                <strong>SWASH</strong>
                <span>Laundry Service</span>
            </span>
        </a>

        <nav class="sw-nav">
            @php
                $menu = [
                    ['customer.dashboard',       'Dashboard',     'customer.dashboard',  'grid'],
                    ['customer.orders.create',   'Buat Pesanan',  'customer.orders.create', 'plus'],
                    ['customer.orders.index',    'Pesanan Saya',  'customer.orders.index', 'list'],
                    ['customer.schedule',        'Jadwal Pickup', 'customer.schedule',   'calendar'],
                    ['customer.payments.index',  'Pembayaran',    'customer.payments.*', 'card'],
                    ['customer.profile.edit',    'Profil',        'customer.profile.*',  'user'],
                ];

                $icons = [
                    'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
                    'plus'     => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>',
                    'list'     => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3v2h6V3M9 12h6M9 16h4"/>',
                    'calendar' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 10h18M8 2v4M16 2v4"/>',
                    'card'     => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>',
                    'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
                ];
            @endphp

            @foreach ($menu as [$route, $label, $pattern, $icon])
                <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$icon] !!}</svg>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sw-user">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="sw-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</span>
                <span>
                    <strong>{{ auth()->user()->name ?? 'Pelanggan' }}</strong>
                    <small style="display:block;">Pelanggan</small>
                </span>
            </div>
            <a class="sw-btn sw-btn-ghost" href="{{ url('/') }}">Kembali ke halaman utama</a>
        </div>
    </aside>

    <main class="sw-main">
        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>