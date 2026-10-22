@extends('customer.layout')
@section('title', 'Pembayaran')
@section('heading', 'Pembayaran')

@section('content')
<section class="sw-panel">
    <h2>Menunggu dibayar</h2>
    @forelse($unpaid as $order)
        <form method="POST" action="{{ route('customer.payments.pay', $order) }}" class="sw-bill">
            @csrf
            <div>
                <strong>{{ $order->code }}</strong> &middot; {{ $order->service_name }}
                <p>{{ $order->weight }} kg</p>
            </div>
            <div class="sw-total">Rp{{ number_format($order->price, 0, ',', '.') }}</div>
            <select name="payment_method" required aria-label="Metode pembayaran">
                <option value="">Pilih metode</option>
                @foreach($methods as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="sw-btn sw-btn-primary">Bayar</button>
        </form>
    @empty
        <div class="sw-empty"><p>Tidak ada tagihan. Pesanan yang sudah ditimbang dan dikonfirmasi admin akan muncul di sini.</p></div>
    @endforelse
</section>

<section class="sw-panel">
    <h2>Riwayat pembayaran</h2>
    @forelse($paid as $order)
        <div class="sw-bill sw-bill-paid">
            <div>
                <strong>{{ $order->code }}</strong> &middot; {{ $order->service_name }}
                <p>{{ $order->paid_at->translatedFormat('d M Y, H.i') }} &middot; {{ $methods[$order->payment_method] ?? '-' }}</p>
            </div>
            <div class="sw-total">Rp{{ number_format($order->price, 0, ',', '.') }}</div>
        </div>
    @empty
        <div class="sw-empty"><p>Belum ada pembayaran.</p></div>
    @endforelse
</section>
@endsection
