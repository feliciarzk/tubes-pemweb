@php
    $rp = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');
@endphp
@extends('layouts.customer')

@section('title', 'Pembayaran')

@push('styles')
    <style>
        .bill-row { display: flex; flex-wrap: wrap; align-items: center; gap: 12px 16px; padding: 14px 0; border-top: 1px solid #e4ecee; }
        .bill-row:first-of-type { border-top: 0; }
        .bill-row .bill-info { flex: 1; min-width: 180px; }
        .bill-row .bill-info small { display: block; color: #6b7f8c; }

        /* Dropdown metode pembayaran */
        .dd { position: relative; }
        .dd-trigger { display: inline-flex; align-items: center; justify-content: space-between; gap: 12px; min-width: 176px; padding: 10px 12px 10px 14px; border: 0; border-radius: 10px; background: #eef4f5; color: #12303f; font: inherit; cursor: pointer; transition: background .15s, box-shadow .15s; }
        .dd-trigger:hover { background: #e2eef0; }
        .dd-trigger:focus { outline: none; }
        .dd-trigger:focus-visible, .dd-trigger[aria-expanded="true"] { background: #fff; box-shadow: 0 0 0 2px rgba(10, 158, 155, .35), 0 2px 8px rgba(18, 48, 63, .08); }
        .dd-trigger.dd-error { background: #fef2f2; box-shadow: 0 0 0 2px rgba(229, 72, 77, .45); }
        .dd-label.is-placeholder { color: #6b7f8c; }
        .dd-chevron { width: 16px; height: 16px; flex: none; color: #6b7f8c; transition: transform .2s; }
        .dd-trigger[aria-expanded="true"] .dd-chevron { transform: rotate(180deg); }

        .dd-menu { position: absolute; top: calc(100% + 8px); right: 0; z-index: 30; min-width: 100%; margin: 0; padding: 6px; list-style: none; background: #fff; border-radius: 12px; box-shadow: 0 12px 32px rgba(18, 48, 63, .16), 0 0 0 1px rgba(18, 48, 63, .04); animation: dd-pop .14s ease-out; }
        .dd-menu[hidden] { display: none; }
        .dd-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 10px 12px; border-radius: 8px; color: #12303f; white-space: nowrap; cursor: pointer; }
        .dd-item:hover, .dd-item:focus { background: #eef7f7; outline: none; }
        .dd-item[aria-selected="true"] { color: #087f81; font-weight: 600; }
        .dd-item[aria-selected="true"]::after { content: "✓"; font-size: 13px; }
        @keyframes dd-pop { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endpush

@section('content')
    <header class="admin-topbar">
        <div>
            <span class="topbar-label">SWASH / PELANGGAN</span>
            <h1>Pembayaran</h1>
        </div>
    </header>

    <section class="panel">
        <div class="panel-heading"><h3>Menunggu dibayar</h3></div>

        @forelse ($unpaid as $order)
            <form method="POST" action="{{ route('customer.payments.pay', $order) }}" class="bill-row">
                @csrf
                <div class="bill-info">
                    <strong>{{ $order->code }}</strong> · {{ $order->service_name }}
                    <small>{{ $order->weight }} kg</small>
                </div>
                <strong>{{ $rp($order->price) }}</strong>

                <div class="dd" data-dropdown>
                    <input type="hidden" name="payment_method" value="">
                    <button type="button" class="dd-trigger" aria-haspopup="listbox" aria-expanded="false" aria-label="Metode pembayaran">
                        <span class="dd-label is-placeholder">Pilih metode</span>
                        <svg class="dd-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <ul class="dd-menu" role="listbox" hidden>
                        @foreach ($methods as $key => $label)
                            <li class="dd-item" role="option" tabindex="-1" data-value="{{ $key }}" aria-selected="false">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Bayar</button>
            </form>
        @empty
            <p>Tidak ada tagihan. Pesanan yang sudah ditimbang dan dikonfirmasi admin akan muncul di sini.</p>
        @endforelse
    </section>

    <section class="panel">
        <div class="panel-heading"><h3>Riwayat pembayaran</h3></div>

        @forelse ($paid as $order)
            <div class="bill-row">
                <div class="bill-info">
                    <strong>{{ $order->code }}</strong> · {{ $order->service_name }}
                    <small>{{ $order->paid_at->translatedFormat('d M Y, H.i') }} · {{ $methods[$order->payment_method] ?? '-' }}</small>
                </div>
                <strong>{{ $rp($order->price) }}</strong>
            </div>
        @empty
            <p>Belum ada pembayaran.</p>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            var dropdowns = document.querySelectorAll('[data-dropdown]');

            function closeAll() {
                dropdowns.forEach(function (dd) {
                    dd.querySelector('.dd-menu').hidden = true;
                    dd.querySelector('.dd-trigger').setAttribute('aria-expanded', 'false');
                });
            }

            dropdowns.forEach(function (dd) {
                var trigger = dd.querySelector('.dd-trigger');
                var menu = dd.querySelector('.dd-menu');
                var input = dd.querySelector('input[type="hidden"]');
                var label = dd.querySelector('.dd-label');
                var items = Array.prototype.slice.call(menu.querySelectorAll('.dd-item'));

                function open() {
                    closeAll();
                    menu.hidden = false;
                    trigger.setAttribute('aria-expanded', 'true');
                }

                function close() {
                    menu.hidden = true;
                    trigger.setAttribute('aria-expanded', 'false');
                }

                function choose(item) {
                    items.forEach(function (i) { i.setAttribute('aria-selected', 'false'); });
                    item.setAttribute('aria-selected', 'true');
                    input.value = item.dataset.value;
                    label.textContent = item.textContent;
                    label.classList.remove('is-placeholder');
                    trigger.classList.remove('dd-error');
                    close();
                    trigger.focus();
                }

                function focusItem(index) {
                    var next = items[(index + items.length) % items.length];
                    if (next) next.focus();
                }

                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.hidden ? open() : close();
                });

                trigger.addEventListener('keydown', function (e) {
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        open();
                        focusItem(e.key === 'ArrowDown' ? 0 : items.length - 1);
                    }
                });

                items.forEach(function (item, index) {
                    item.addEventListener('click', function () { choose(item); });
                    item.addEventListener('keydown', function (e) {
                        if (e.key === 'ArrowDown') { e.preventDefault(); focusItem(index + 1); }
                        else if (e.key === 'ArrowUp') { e.preventDefault(); focusItem(index - 1); }
                        else if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); choose(item); }
                        else if (e.key === 'Escape') { close(); trigger.focus(); }
                        else if (e.key === 'Tab') { close(); }
                    });
                });

                // wajib pilih metode sebelum bayar
                dd.closest('form').addEventListener('submit', function (e) {
                    if (!input.value) {
                        e.preventDefault();
                        trigger.classList.add('dd-error');
                        open();
                    }
                });
            });

            document.addEventListener('click', closeAll);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });
        })();
    </script>
@endpush