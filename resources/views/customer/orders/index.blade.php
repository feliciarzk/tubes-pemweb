@php
    $kelasStatus = [
        'menunggu_konfirmasi' => 'status-waiting',
        'menunggu_pembayaran' => 'status-payment',
        'diproses' => 'status-processing',
        'selesai' => 'status-complete',
    ];
@endphp
@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
    <header class="admin-topbar">
        <div>
            <span class="topbar-label">SWASH / PELANGGAN</span>
            <h1>Pesanan Saya</h1>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">Buat Pesanan</a>
        </div>
    </header>

    <section class="panel">
        @if ($orders->isEmpty())
            <p>Belum ada pesanan.</p>
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">Buat pesanan pertama</a>
        @else
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr><th>Kode</th><th>Layanan</th><th>Jasa</th><th>Berat</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="order-id">{{ $order->code }}<br><small>{{ $order->created_at->translatedFormat('d M Y') }}</small></td>
                                <td>{{ $order->service_name }}</td>
                                <td>{{ $order->delivery_label }}</td>
                                <td>{{ $order->weight ? $order->weight . ' kg' : '-' }}</td>
                                <td>{{ $order->price ? 'Rp' . number_format($order->price, 0, ',', '.') : '-' }}</td>
                                <td><span class="order-status {{ $kelasStatus[$order->status] ?? 'status-waiting' }}">{{ $order->status_label }}</span></td>
                                <td><a href="{{ route('customer.orders.show', $order) }}" class="text-action">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @endif
    </section>
@endsection