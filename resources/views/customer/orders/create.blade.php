@extends('customer.layout')
@section('title', 'Buat Pesanan')
@section('heading', 'Buat Pesanan')

@section('content')
<form method="POST" action="{{ route('customer.orders.store') }}" class="sw-form">
    @csrf

    <section class="sw-panel">
        <h2>Pilih layanan</h2>
        <div class="sw-choices">
            @foreach($services as $key => $s)
                <label class="sw-choice">
                    <input type="radio" name="service" value="{{ $key }}" @checked(old('service') === $key) required>
                    <span class="sw-choice-body">
                        <strong>{{ $s['name'] }}</strong>
                        <small>Rp{{ number_format($s['price_per_kg'], 0, ',', '.') }} per kg</small>
                    </span>
                </label>
            @endforeach
        </div>
    </section>

    <section class="sw-panel">
        <h2>Pilih jasa</h2>
        <div class="sw-choices">
            <label class="sw-choice">
                <input type="radio" name="delivery_type" value="antar_jemput" @checked(old('delivery_type', 'antar_jemput') === 'antar_jemput')>
                <span class="sw-choice-body">
                    <strong>Antar jemput</strong>
                    <small>Gratis. Driver menjemput, menimbang, lalu mengantar kembali.</small>
                </span>
            </label>
            <label class="sw-choice">
                <input type="radio" name="delivery_type" value="antar_saja" @checked(old('delivery_type') === 'antar_saja')>
                <span class="sw-choice-body">
                    <strong>Antar saja</strong>
                    <small>Gratis. Kamu antar cucian ke toko, kami antar kembali setelah selesai.</small>
                </span>
            </label>
        </div>

        <div id="pickup-fields" class="sw-grid-2">
            <div class="sw-field">
                <label for="pickup_date">Tanggal pickup</label>
                <input type="date" id="pickup_date" name="pickup_date" value="{{ old('pickup_date') }}" min="{{ now()->toDateString() }}">
            </div>
            <div class="sw-field">
                <label for="pickup_time">Jam pickup</label>
                <select id="pickup_time" name="pickup_time">
                    @foreach($slots as $slot)
                        <option value="{{ $slot }}" @selected(old('pickup_time') === $slot)>{{ $slot }} WIB</option>
                    @endforeach
                </select>
            </div>
            <p class="sw-hint">Driver membawa timbangan. Berat dan harga final menunggu persetujuan admin.</p>
        </div>
    </section>

    <section class="sw-panel">
        <h2>Alamat</h2>
        <div class="sw-choices">
            <label class="sw-choice">
                <input type="radio" name="address_type" value="profil" @checked(old('address_type', 'profil') === 'profil')>
                <span class="sw-choice-body">
                    <strong>Alamat di profil</strong>
                    <small>{{ auth()->user()->address ?: 'Belum diisi. Isi di halaman Profil.' }}</small>
                </span>
            </label>
            <label class="sw-choice">
                <input type="radio" name="address_type" value="lain" @checked(old('address_type') === 'lain')>
                <span class="sw-choice-body">
                    <strong>Alamat lain</strong>
                    <small>Tulis alamat lengkap untuk pesanan ini.</small>
                </span>
            </label>
        </div>
        <div class="sw-field" id="address-other-wrap">
            <label for="address_other">Alamat lengkap</label>
            <textarea id="address_other" name="address_other" rows="3">{{ old('address_other') }}</textarea>
        </div>
    </section>

    <section class="sw-panel">
        <div class="sw-field">
            <label for="notes">Catatan untuk kami (opsional)</label>
            <textarea id="notes" name="notes" rows="2" placeholder="Contoh: pisahkan baju putih">{{ old('notes') }}</textarea>
        </div>
    </section>

    <div class="sw-actions">
        <a href="{{ route('customer.dashboard') }}" class="sw-btn sw-btn-ghost">Batal</a>
        <button type="submit" class="sw-btn sw-btn-primary">Buat pesanan</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const pickupFields = document.getElementById('pickup-fields');
    const addressWrap  = document.getElementById('address-other-wrap');

    function sync() {
        const delivery = document.querySelector('[name=delivery_type]:checked')?.value;
        const address  = document.querySelector('[name=address_type]:checked')?.value;
        pickupFields.hidden = delivery === 'antar_saja';
        addressWrap.hidden  = address !== 'lain';
    }
    document.querySelectorAll('[name=delivery_type], [name=address_type]')
        .forEach(el => el.addEventListener('change', sync));
    sync();
</script>
@endpush
