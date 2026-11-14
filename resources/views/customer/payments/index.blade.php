@php
    $rp = fn ($angka) => 'Rp' . number_format($angka, 0, ',', '.');
    $methods = collect($methods)->except('tunai')->all();
    $bank = config('swash.bank') ?: ['name' => 'BCA', 'number' => '1234567890', 'holder' => 'SWASH Laundry'];
@endphp
@extends('layouts.customer')

@section('title', 'Pembayaran')

@section('content')
    <header class="admin-topbar">
        <h1>Pembayaran</h1>
    </header>

    <section class="panel">
        <div class="panel-heading"><h3>Menunggu dibayar</h3></div>

        @forelse ($unpaid as $order)
            <form method="POST" action="{{ route('customer.payments.pay', $order) }}" class="bill-row"
                  data-action="{{ route('customer.payments.pay', $order) }}"
                  data-code="{{ $order->code }}" data-service="{{ $order->service_name }}" data-total="{{ $rp($order->price) }}">
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
            <p>Tidak ada tagihan.</p>
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
    {{-- Muncul setelah klik Bayar --}}
    <div class="pay-modal" id="pay-modal" role="dialog" aria-modal="true" aria-labelledby="pay-title" hidden>
        <div class="pay-backdrop" data-close></div>

        <form method="POST" enctype="multipart/form-data" class="pay-card" id="pay-form" tabindex="-1">
            @csrf
            <input type="hidden" name="payment_method" id="pay-method">

            <div class="pay-head">
                <div>
                    <h3 id="pay-title">Konfirmasi pembayaran</h3>
                    <small id="pay-order"></small>
                </div>
                <button type="button" class="pay-x" data-close aria-label="Tutup">&times;</button>
            </div>

            <div class="pay-total"><span>Total</span><strong id="pay-total"></strong></div>

            <div class="transfer-box" id="pay-transfer">
                <div>
                    <span>{{ $bank['name'] }}</span>
                    <strong>{{ $bank['number'] }}</strong>
                    <small>a.n. {{ $bank['holder'] }}</small>
                </div>
                <button type="button" class="btn btn-ghost btn-sm" data-copy="{{ $bank['number'] }}">Salin</button>
            </div>

            <div class="qris-box" id="pay-qris" hidden>
                <div class="qris-img">QRIS</div>
                <small>Scan dengan aplikasi e-wallet atau m-banking.</small>
            </div>

            <div class="pay-field" id="pay-sender">
                <label for="sender_name">Nama pengirim</label>
                <input id="sender_name" name="sender_name" placeholder="Sesuai nama di rekening">
            </div>

            <div class="pay-field">
                <label for="proof">Bukti pembayaran</label>
                <label class="upload" for="proof">
                    <input id="proof" type="file" name="proof" accept="image/*,.pdf" required>
                    <img id="proof-preview" alt="Pratinjau bukti" hidden>
                    <span id="proof-text">Pilih file atau seret ke sini</span>
                    <small>JPG, PNG, atau PDF. Maksimal 2 MB.</small>
                </label>
                <small class="pay-error" id="proof-error" hidden></small>
            </div>

            <div class="pay-field">
                <label for="pay_note">Catatan (opsional)</label>
                <input id="pay_note" name="note">
            </div>

            <div class="pay-actions">
                <button type="button" class="btn btn-ghost" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Kirim bukti</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var modal = document.getElementById('pay-modal');
            var payForm = document.getElementById('pay-form');
            var proof = document.getElementById('proof');
            var proofText = document.getElementById('proof-text');
            var proofPreview = document.getElementById('proof-preview');
            var proofError = document.getElementById('proof-error');
            var MAX_SIZE = 2 * 1024 * 1024;

            function resetProof() {
                proofText.textContent = 'Pilih file atau seret ke sini';
                proofPreview.hidden = true;
                proofPreview.removeAttribute('src');
                proofError.hidden = true;
            }

            function openModal(row, method) {
                var isTransfer = method === 'transfer';
                payForm.action = row.dataset.action;
                document.getElementById('pay-order').textContent = row.dataset.code + ' · ' + row.dataset.service;
                document.getElementById('pay-total').textContent = row.dataset.total;
                document.getElementById('pay-method').value = method;
                document.getElementById('pay-transfer').hidden = !isTransfer;
                document.getElementById('pay-sender').hidden = !isTransfer;
                document.getElementById('pay-qris').hidden = isTransfer;
                modal.hidden = false;
                document.body.classList.add('modal-open');
                payForm.focus();
            }

            function closeModal() {
                modal.hidden = true;
                document.body.classList.remove('modal-open');
                payForm.reset();
                resetProof();
            }

            modal.querySelectorAll('[data-close]').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });

            proof.addEventListener('change', function () {
                var file = proof.files[0];
                proofError.hidden = true;
                if (!file) { resetProof(); return; }
                if (file.size > MAX_SIZE) {
                    proof.value = '';
                    resetProof();
                    proofError.textContent = 'Ukuran file maksimal 2 MB.';
                    proofError.hidden = false;
                    return;
                }
                proofText.textContent = file.name;
                if (file.type.indexOf('image/') === 0) {
                    proofPreview.src = URL.createObjectURL(file);
                    proofPreview.hidden = false;
                } else {
                    proofPreview.hidden = true;
                }
            });

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
                    e.preventDefault();
                    if (!input.value) {
                        trigger.classList.add('dd-error');
                        open();
                        return;
                    }
                    openModal(this, input.value);
                });
            });

            document.querySelectorAll('[data-copy]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    navigator.clipboard.writeText(btn.dataset.copy).then(function () {
                        btn.textContent = 'Tersalin';
                        setTimeout(function () { btn.textContent = 'Salin'; }, 1500);
                    });
                });
            });

            document.addEventListener('click', closeAll);
            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                closeAll();
                if (!modal.hidden) closeModal();
            });
        })();
    </script>
@endpush