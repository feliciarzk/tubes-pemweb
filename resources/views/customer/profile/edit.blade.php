@php
    $kode = $user->code ?? 'PL-' . str_pad($user->id, 3, '0', STR_PAD_LEFT);
@endphp
@extends('layouts.customer')

@section('title', 'Profil')

@section('content')
    <header class="admin-topbar">
        <h1>Profil</h1>
    </header>

    @if (session('success'))
        <div class="flash" role="status">{{ session('success') }}</div>
    @endif

    {{-- Data akun --}}
    <form method="POST" action="{{ route('customer.profile.update') }}" class="profile-form">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-heading"><h3>Data akun</h3></div>

            <div class="form-row">
                <div class="field">
                    <label for="code">Kode pelanggan</label>
                    <input id="code" value="{{ $kode }}" readonly tabindex="-1">
                </div>
                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="phone">No. HP</label>
                    <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<small class="field-error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="field">
                <label for="address">Alamat utama</label>
                <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')<small class="field-error">{{ $message }}</small>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan profil</button>
            </div>
        </section>
    </form>

    {{-- Ganti password --}}
    <form method="POST" action="{{ route('customer.profile.password') }}" class="profile-form">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-heading"><h3>Ganti password</h3></div>

            <div class="field">
                <label for="current_password">Password lama</label>
                <input id="current_password" type="password" name="current_password" autocomplete="current-password" required>
                @error('current_password')<small class="field-error">{{ $message }}</small>@enderror
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="password">Password baru</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" minlength="8" required>
                    @error('password')<small class="field-error">{{ $message }}</small>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ganti password</button>
            </div>
        </section>
    </form>
@endsection