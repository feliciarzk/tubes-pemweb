@extends('customer.layout')
@section('title', 'Profil')
@section('heading', 'Profil')

@section('content')
<form method="POST" action="{{ route('customer.profile.update') }}" class="sw-form">
    @csrf
    @method('PUT')
    <section class="sw-panel">
        <div class="sw-grid-2">
            <div class="sw-field">
                <label for="name">Nama</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="sw-field">
                <label for="phone">No. HP</label>
                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="sw-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
        <div class="sw-field">
            <label for="address">Alamat utama</label>
            <textarea id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
            <small class="sw-hint">Dipakai sebagai alamat jemput dan antar saat kamu memilih alamat profil.</small>
        </div>
    </section>
    <div class="sw-actions">
        <button type="submit" class="sw-btn sw-btn-primary">Simpan profil</button>
    </div>
</form>
@endsection
