@extends('customer.layout')
@section('title', 'Jadwal Pickup')
@section('heading', 'Jadwal Pickup')

@section('content')
<section class="sw-panel">
    @forelse($orders as $order)
        <a class="sw-schedule" href="{{ route('customer.orders.show', $order) }}">
            <div class="sw-date">
                <strong>{{ $order->pickup_date->format('d') }}</strong>
                <span>{{ $order->pickup_date->translatedFormat('M') }}</span>
            </div>
            <div>
                <strong>{{ $order->pickup_date->translatedFormat('l') }}, {{ $order->pickup_time }} WIB</strong>
                <p>{{ $order->code }} &middot; {{ $order->service_name }}</p>
                <small>{{ $order->address }}</small>
            </div>
        </a>
    @empty
        <div class="sw-empty">
            <p>Tidak ada pickup yang dijadwalkan.</p>
            <a href="{{ route('customer.orders.create') }}" class="sw-btn sw-btn-primary">Buat pesanan</a>
        </div>
    @endforelse
</section>
@endsection
