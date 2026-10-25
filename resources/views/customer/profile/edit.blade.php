@extends('layouts.customer')

@section('title', 'Profil')

@push('styles')
    <style>
        .profile-form { display: grid; gap: 16px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field { display: grid; gap: 6px; margin-top: 16px; }
        .form-row .field { margin-top: 0; }
        .field label { font-weight: 600; font-size: 14px; }
        .field input, .field textarea { padding: 10px 12px; border: 1px solid #d5e0e3; border-radius: 8px; font: inherit; }
        .field small { color: #6b7f8c; }
        .form-actions { display: flex; justify-content: flex-end; }
    </style>
@endpush

@section('content')
    <header class="admin-topbar">
        <div>
            <span class="topbar-label">SWASH / PELANGGAN</span>
            <h1>Profil</h1>
        </div>
    </header>

    <form method="POST" action="{{ route('customer.profile.update') }}" class="profile-form">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="form-row">
                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="field">
                    <label for="phone">No. HP</label>
                    <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="field">
                <label for="address">Alamat utama</label>
                <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                <small>Dipakai sebagai alamat jemput dan antar saat kamu memilih alamat profil.</small>
            </div>
        </section>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan profil</button>
        </div>
    </form>
@endsection