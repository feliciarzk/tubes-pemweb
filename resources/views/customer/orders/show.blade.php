@php
    $steps = [
        'Pesanan dibuat',
        $order->delivery_type === 'antar_jemput' ? 'Dijemput & ditimbang' : 'Diterima & ditimbang',
        'Menunggu pembayaran',
        'Diproses',
        'Diantar',
        'Selesai',
    ];
    $current = $order->status === 'selesai' ? count($steps) : $order->status_step;

    $rp = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');
    $kg = fn ($berat) => rtrim(rtrim(number_format((float) $berat, 2, ',', '.'), '0'), ',') . ' kg';
    $hargaPerKg = config("swash.services.{$order->service}.price_per_kg", 0);

    $detail = [
        'Layanan' => $order->service_name,
        'Jasa' => $order->delivery_label,
    ];
    if ($order->pickup_date) {
        $detail['Pickup'] = $order->pickup_date->translatedFormat('l, d F Y') . ', ' . $order->pickup_time . ' WIB';
    }
    $detail['Alamat'] = $order->address;
    $detail['Berat'] = $order->weight ? $kg($order->weight) : 'Belum ditimbang';
    $detail['Total'] = $order->price ? $rp($order->price) : 'Belum ditentukan';
    if ($order->paid_at) {
        $detail['Dibayar'] = $order->paid_at->translatedFormat('d M Y, H.i') . ' (' . config('swash.payment_methods.' . $order->payment_method) . ')';
    }
    if ($order->notes) {
        $detail['Catatan'] = $order->notes;
    }
@endphp
@extends('layouts.customer')

@section('title', $order->code)

@section('content')
    <header class="admin-topbar">
        <h1>Pesanan {{ $order->code }}</h1>
        <div class="topbar-actions">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-ghost">Kembali</a>
        </div>
    </header>

    <section class="panel">
        <div class="panel-heading"><h3>Status</h3></div>
        <ol class="tracker">
            @foreach ($steps as $i => $label)
                <li class="tracker-step {{ $i < $current ? 'done' : ($i === $current ? 'current' : '') }}">
                    <div class="tracker-dot">{{ $i < $current ? '✓' : $i + 1 }}</div>
                    <strong>{{ $label }}</strong>
                </li>
            @endforeach
        </ol>
    </section>

    @if ($order->status === 'menunggu_pembayaran')
        <section class="panel">
            <div class="bill-card">
                <div class="bill-top">
                    <div><span>Berat × harga per kg</span><strong>{{ $kg($order->weight) }} × {{ $rp($hargaPerKg) }}</strong></div>
                    <div><span>Total tagihan</span><strong>{{ $rp($order->price) }}</strong></div>
                </div>
                <a href="{{ route('customer.payments.index') }}" class="btn btn-primary">Bayar Sekarang</a>
            </div>
        </section>
    @elseif ($order->status === 'menunggu_konfirmasi')
        <section class="panel">
            <p class="status-hint">Menunggu penimbangan dan konfirmasi admin.</p>
        </section>
    @endif

    <section class="panel">
        <div class="panel-heading"><h3>Detail Pesanan</h3></div>
        <div class="detail-list">
            @foreach ($detail as $label => $isi)
                <div class="detail-row"><span>{{ $label }}</span><strong>{{ $isi }}</strong></div>
            @endforeach
        </div>
    </section>
@endsection