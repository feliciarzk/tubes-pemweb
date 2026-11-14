
{{-- Sidebar admin dipakai semua halaman admin.
     Pemakaian: @include('admin.partials.sidebar', ['active' => 'dashboard'])
     Nilai active: dashboard, pesanan, jadwal, driver, pembayaran, shift, laporan --}}
@php
    $active = $active ?? '';
    $menu = [
        'dashboard'  => ['Dashboard',       '/admin/dashboard'],
        'pesanan'    => ['Pesanan',         '/admin/pesanan'],
        'jadwal'     => ['Jadwal Pickup',   '/admin/jadwal-pickup'],
        'driver'     => ['Driver',          '/admin/driver'],
        'pembayaran' => ['Pembayaran',      '/admin/pembayaran'],
        'shift'      => ['Manajemen Shift', '/admin/shift'],
        'laporan'    => ['Laporan',         '/admin/laporan'],
    ];
@endphp

<aside class="sidebar">
    <a href="{{ url('/') }}" class="admin-brand" aria-label="SWASH">
        <svg class="brand-logo" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M24 3C19 11 8 20 8 30a16 16 0 0 0 32 0C40 20 29 11 24 3Z" fill="currentColor"/>
            <path d="M30 17c-2 4-8 7-8 13 0 4 3 7 7 7 3 0 5-2 6-5" stroke="white" stroke-width="3" stroke-linecap="round"/>
            <path d="M17 26c-2 2-3 5-2 8 1 4 5 7 10 7" stroke="white" stroke-width="3" stroke-linecap="round"/>
        </svg>
        <span class="brand-copy">
            <strong>SWASH</strong>
            <small>Laundry Service</small>
        </span>
    </a>

    <p class="sidebar-label">MENU UTAMA</p>

    <nav class="sidebar-nav">
        @foreach ($menu as $key => $item)
            <a href="{{ url($item[1]) }}"
               class="sidebar-link {{ $active === $key ? 'active' : '' }}">
                <span class="nav-mark"></span>
                {{ $item[0] }}
            </a>
        @endforeach
    </nav>

    <div class="sidebar-bottom">

        {{-- Profil Admin: avatar dan tulisan sama-sama bisa diklik --}}
        <a href="{{ url('/admin/profil') }}"
           class="admin-profile"
           aria-label="Manajemen Profil Admin">

            <div class="profile-avatar">A</div>

            <div>
                <strong>Administrator</strong>
                <small>Admin SWASH</small>
            </div>

        </a>

        <a class="back-home" href="{{ url('/') }}">
            Kembali ke halaman utama
        </a>
    </div>
</aside>
