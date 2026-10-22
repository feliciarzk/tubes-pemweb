@extends('customer.layout')
@section('title', $order->code)
@section('heading', 'Pesanan ' . $order->code)
@section('action')
    <a href="{{ route('customer.orders.index') }}" class="sw-btn sw-btn-ghost">Kembali</a>
@endsection

@section('content')
@php
    $steps = [
        0 => 'Pesanan dibuat',
        1 => $order->delivery_type === 'antar_jemput' ? 'Dijemput & ditimbang' : 'Diterima & ditimbang',
        2 => 'Menunggu pembayaran',
        3 => 'Diproses',
        4 => 'Diantar',
        5 => 'Selesai',
    ];
    $current = $order->status_step;
@endphp

<section class="sw-panel">
    <h2>Status</h2>
    <ol class="sw-steps">
        @foreach($steps as $i => $label)
            <li class="{{ $i < $current ? 'is-done' : ($i === $current ? 'is-now' : '') }}">{{ $label }}</li>
        @endforeach
    </ol>
</section>

@if($order->status === 'menunggu_pembayaran')
<section class="sw-panel sw-panel-accent">
    <h2>Tagihan</h2>
    <p>{{ $order->weight }} kg &times; Rp{{ number_format(config("swash.services.{$order->service}.price_per_kg", 0), 0, ',', '.') }}</p>
    <p class="sw-total">Rp{{ number_format($order->price, 0, ',', '.') }}</p>
    <a href="{{ route('customer.payments.index') }}" class="sw-btn sw-btn-primary">Bayar sekarang</a>
</section>
@elseif($order->status === 'menunggu_konfirmasi')
<section class="sw-panel">
    <p>Berat sudah dicatat atau sedang menunggu penimbangan. Admin akan mengonfirmasi harga sebentar lagi.</p>
</section>
@endif

<section class="sw-panel">
    <h2>Detail pesanan</h2>
    <dl class="sw-dl">
        <dt>Layanan</dt><dd>{{ $order->service_name }}</dd>
        <dt>Jasa</dt><dd>{{ $order->delivery_label }}</dd>
        @if($order->pickup_date)
            <dt>Pickup</dt><dd>{{ $order->pickup_date->translatedFormat('l, d F Y') }}, {{ $order->pickup_time }} WIB</dd>
        @endif
        <dt>Alamat</dt><dd>{{ $order->address }}</dd>
        <dt>Berat</dt><dd>{{ $order->weight ? $order->weight . ' kg' : 'Belum ditimbang' }}</dd>
        <dt>Total</dt><dd>{{ $order->price ? 'Rp' . number_format($order->price, 0, ',', '.') : 'Belum ditentukan' }}</dd>
        @if($order->paid_at)
            <dt>Dibayar</dt><dd>{{ $order->paid_at->translatedFormat('d M Y, H.i') }} ({{ config('swash.payment_methods.' . $order->payment_method) }})</dd>
        @endif
        @if($order->notes)
            <dt>Catatan</dt><dd>{{ $order->notes }}</dd>
        @endif
    </dl>
</section>
@endsection
