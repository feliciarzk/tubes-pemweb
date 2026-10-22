@extends('customer.layout')
@section('title', 'Pesanan Saya')
@section('heading', 'Pesanan Saya')
@section('action')
    <a href="{{ route('customer.orders.create') }}" class="sw-btn sw-btn-primary">Buat pesanan</a>
@endsection

@section('content')
<section class="sw-panel">
    @forelse($orders as $order)
        @if($loop->first)
        <div class="sw-table-wrap">
        <table class="sw-table">
            <thead><tr>
                <th>Kode</th><th>Layanan</th><th>Jasa</th><th>Berat</th><th>Total</th><th>Status</th><th></th>
            </tr></thead>
            <tbody>
        @endif
            <tr>
                <td><strong>{{ $order->code }}</strong><br><small>{{ $order->created_at->translatedFormat('d M Y') }}</small></td>
                <td>{{ $order->service_name }}</td>
                <td>{{ $order->delivery_label }}</td>
                <td>{{ $order->weight ? $order->weight . ' kg' : '-' }}</td>
                <td>{{ $order->price ? 'Rp' . number_format($order->price, 0, ',', '.') : '-' }}</td>
                <td><span class="sw-badge sw-badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                <td><a href="{{ route('customer.orders.show', $order) }}">Detail</a></td>
            </tr>
        @if($loop->last)
            </tbody>
        </table>
        </div>
        {{ $orders->links() }}
        @endif
    @empty
        <div class="sw-empty">
            <p>Belum ada pesanan.</p>
            <a href="{{ route('customer.orders.create') }}" class="sw-btn sw-btn-primary">Buat pesanan pertama</a>
        </div>
    @endforelse
</section>
@endsection
