@php
    $admin = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Profil | SWASH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
<div class="admin-layout">

    @include('admin.sidebar', ['active' => 'profil'])

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <span class="topbar-label">SWASH / ADMIN</span>
                <h1>Manajemen Profil</h1>
            </div>
        </header>

        <section class="welcome-section">
            <div>
                <p class="welcome-label">PENGATURAN AKUN</p>
                <h2>Profil Administrator</h2>
                <p>Kelola informasi pribadi dan keamanan akun administrator SWASH.</p>
            </div>
        </section>

        @if (session('success'))
            <div class="profile-alert success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="profile-alert error">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-page">

            {{-- INFORMASI PROFIL --}}
            <section class="profile-card">

                <div class="profile-heading">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                    </div>

                    <div>
                        <h3>{{ $admin->name ?? 'Administrator' }}</h3>
                        <p>Administrator SWASH</p>
                    </div>
                </div>

                <h3 class="profile-section-title">Informasi Pribadi</h3>

                <form action="{{ url('/admin/profil') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $admin->name ?? '') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $admin->email ?? '') }}"
                            placeholder="Masukkan email"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $admin->phone ?? '') }}"
                            placeholder="Masukkan nomor HP"
                        >
                    </div>

                    <div class="form-group">
                        <label>Kode Admin</label>
                        <input
                            type="text"
                            value="{{ $admin->admin_code ?? 'AD-001' }}"
                            readonly
                        >
                        <small>Kode admin tidak dapat diubah.</small>
                    </div>

                    <div class="form-group">
                        <label>Shift</label>
                        <input
                            type="text"
                            value="{{ $admin->shift ?? '-' }}"
                            readonly
                        >
                        <small>Informasi shift dikelola melalui Manajemen Shift.</small>
                    </div>

                    <button type="submit" class="btn-save">
                        Simpan Perubahan
                    </button>
                </form>

            </section>

            {{-- GANTI PASSWORD --}}
            <section class="profile-card">

                <h3 class="profile-section-title">Keamanan Akun</h3>

                <p style="font-size:13px; color:#7b8494; margin-top:-10px; margin-bottom:22px;">
                    Perbarui password secara berkala untuk menjaga keamanan akun.
                </p>

                <form action="{{ url('/admin/profil/password') }}" method="POST" class="profile-form">
                    @csrf

                    <div class="form-group">
                        <label>Password Lama</label>
                        <input
                            type="password"
                            name="current_password"
                            placeholder="Masukkan password lama"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password baru"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-save">
                        Ganti Password
                    </button>
                </form>

            </section>

        </div>

        <footer class="admin-footer">
            <span>SWASH Laundry Service</span>
            <span>Panel Administrasi</span>
        </footer>

    </main>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
