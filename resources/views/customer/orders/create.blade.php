@php
    $jasa = [
        'antar_jemput' => ['Antar jemput', 'Driver jemput dan antar kembali.'],
        'antar_saja' => ['Antar saja', 'Kamu antar ke toko, kami antar kembali.'],
    ];

    $alamat = [
        'profil' => ['Alamat di profil', auth()->user()->address ?: 'Belum diisi di Profil.'],
        'lain' => ['Alamat lain', 'Tulis alamat lengkap.'],
    ];
@endphp
@extends('layouts.customer')

@section('title', 'Buat Pesanan')

@section('content')
    <header class="admin-topbar">
        <div>
            <h1>Buat Pesanan</h1>
        </div>
    </header>

    <form method="POST" action="{{ route('customer.orders.store') }}" class="order-form">
        @csrf

        <section class="panel">
            <div class="panel-heading"><h3>Pilih layanan</h3></div>
            <div class="choice-list">
                @foreach ($services as $key => $s)
                    <label class="choice">
                        <input type="radio" name="service" value="{{ $key }}" @checked(old('service', request('layanan')) === $key) required>
                        <span>
                            <strong>{{ $s['name'] }}</strong>
                            <small>Rp{{ number_format($s['price_per_kg'], 0, ',', '.') }} per kg</small>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="panel">
            <div class="panel-heading"><h3>Pilih jasa</h3></div>
            <div class="choice-list">
                @foreach ($jasa as $value => [$judul, $ket])
                    <label class="choice">
                        <input type="radio" name="delivery_type" value="{{ $value }}" @checked(old('delivery_type', 'antar_jemput') === $value)>
                        <span><strong>{{ $judul }}</strong><small>{{ $ket }}</small></span>
                    </label>
                @endforeach
            </div>

            <div id="pickup-fields" class="form-row">
                <div class="field">
                    <label for="pickup_date_btn">Tanggal pickup</label>
                    <div class="dd" data-dd="date" data-min="{{ now()->toDateString() }}">
                        <input type="hidden" name="pickup_date" value="{{ old('pickup_date') }}">
                        <button type="button" class="dd-trigger" id="pickup_date_btn" aria-haspopup="dialog" aria-expanded="false">
                            <span class="dd-value" data-placeholder="Pilih tanggal"></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        </button>
                        <div class="dd-panel cal" hidden></div>
                    </div>
                    <small class="field-error" hidden>Pilih tanggal pickup.</small>
                </div>

                <div class="field">
                    <label for="pickup_time_btn">Jam pickup</label>
                    <div class="dd" data-dd="time">
                        <input type="hidden" name="pickup_time" value="{{ old('pickup_time') }}">
                        <button type="button" class="dd-trigger" id="pickup_time_btn" aria-haspopup="listbox" aria-expanded="false">
                            <span class="dd-value" data-placeholder="Pilih jam"></span>
                            <svg class="dd-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <ul class="dd-panel dd-list" role="listbox" hidden>
                            @foreach ($slots as $slot)
                                <li role="option" tabindex="-1" data-value="{{ $slot }}">{{ $slot }} WIB</li>
                            @endforeach
                        </ul>
                    </div>
                    <small class="field-error" hidden>Pilih jam pickup.</small>
                </div>

            </div>
        </section>

        <section class="panel">
            <div class="panel-heading"><h3>Alamat</h3></div>
            <div class="choice-list">
                @foreach ($alamat as $value => [$judul, $ket])
                    <label class="choice">
                        <input type="radio" name="address_type" value="{{ $value }}" @checked(old('address_type', 'profil') === $value)>
                        <span><strong>{{ $judul }}</strong><small>{{ $ket }}</small></span>
                    </label>
                @endforeach
            </div>

            <div class="field" id="address-other">
                <label for="address_other">Alamat lengkap</label>
                <textarea id="address_other" name="address_other" rows="3">{{ old('address_other') }}</textarea>
            </div>
        </section>

        <section class="panel">
            <div class="field field-first">
                <label for="notes">Catatan (opsional)</label>
                <textarea id="notes" name="notes" rows="2" placeholder="Contoh: pisahkan baju putih">{{ old('notes') }}</textarea>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">Buat pesanan</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const pickup = document.getElementById('pickup-fields');
        const addressOther = document.getElementById('address-other');

        function sync() {
            const jasa = document.querySelector('[name=delivery_type]:checked')?.value;
            const alamat = document.querySelector('[name=address_type]:checked')?.value;
            pickup.hidden = jasa === 'antar_saja';
            addressOther.hidden = alamat !== 'lain';
        }

        document.querySelectorAll('[name=delivery_type], [name=address_type]')
            .forEach(el => el.addEventListener('change', sync));
        sync();

        // ---------- dropdown jam & kalender tanggal ----------
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const HARI = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        const pad = n => String(n).padStart(2, '0');
        const iso = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        const parseISO = v => { const [y, m, d] = v.split('-').map(Number); return new Date(y, m - 1, d); };
        const labelTanggal = d => `${d.getDate()} ${BULAN[d.getMonth()].slice(0, 3)} ${d.getFullYear()}`;

        function closeAll() {
            document.querySelectorAll('.dd.open').forEach(dd => {
                dd.classList.remove('open');
                dd.querySelector('.dd-panel').hidden = true;
                dd.querySelector('.dd-trigger').setAttribute('aria-expanded', 'false');
            });
        }

        document.addEventListener('click', closeAll);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });

        document.querySelectorAll('.dd').forEach(dd => {
            const trigger = dd.querySelector('.dd-trigger');
            const panel = dd.querySelector('.dd-panel');
            const valueEl = dd.querySelector('.dd-value');
            const hidden = dd.querySelector('input[type=hidden]');
            const error = dd.parentElement.querySelector('.field-error');

            const setText = text => {
                valueEl.textContent = text || valueEl.dataset.placeholder;
                valueEl.classList.toggle('placeholder', !text);
            };
            const setValue = (value, text) => {
                hidden.value = value;
                setText(text);
                dd.classList.remove('invalid');
                error.hidden = true;
            };
            const open = () => {
                closeAll();
                if (dd.dataset.dd === 'date') renderCal();
                dd.classList.add('open');
                panel.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
            };

            // klik di dalam dropdown tidak dianggap klik di luar
            dd.addEventListener('click', e => e.stopPropagation());
            trigger.addEventListener('click', () => dd.classList.contains('open') ? closeAll() : open());

            // ----- jam -----
            if (dd.dataset.dd === 'time') {
                const items = [...panel.querySelectorAll('li')];
                const pilih = li => {
                    items.forEach(i => i.removeAttribute('aria-selected'));
                    li.setAttribute('aria-selected', 'true');
                    setValue(li.dataset.value, li.textContent.trim());
                    closeAll();
                    trigger.focus();
                };
                const awal = items.find(i => i.dataset.value === hidden.value);
                if (awal) awal.setAttribute('aria-selected', 'true');
                setText(awal ? awal.textContent.trim() : '');

                items.forEach(li => li.addEventListener('click', () => pilih(li)));
                trigger.addEventListener('keydown', e => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        open();
                        (items.find(i => i.getAttribute('aria-selected')) || items[0]).focus();
                    }
                });
                panel.addEventListener('keydown', e => {
                    const i = items.indexOf(document.activeElement);
                    if (e.key === 'ArrowDown') { e.preventDefault(); items[Math.min(i + 1, items.length - 1)].focus(); }
                    if (e.key === 'ArrowUp') { e.preventDefault(); items[Math.max(i - 1, 0)].focus(); }
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); if (i >= 0) pilih(items[i]); }
                });
            }

            // ----- tanggal -----
            let renderCal = () => {};
            if (dd.dataset.dd === 'date') {
                const min = parseISO(dd.dataset.min);
                let view = hidden.value ? parseISO(hidden.value) : new Date(min);
                view = new Date(view.getFullYear(), view.getMonth(), 1);
                setText(hidden.value ? labelTanggal(parseISO(hidden.value)) : '');

                renderCal = () => {
                    const sel = hidden.value ? iso(parseISO(hidden.value)) : null;
                    const offset = (view.getDay() + 6) % 7; // minggu mulai hari Senin
                    const total = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
                    const bulanMin = new Date(min.getFullYear(), min.getMonth(), 1);

                    let html = `<div class="cal-head">
                        <button type="button" class="cal-nav" data-nav="-1" aria-label="Bulan sebelumnya" ${view <= bulanMin ? 'disabled' : ''}>&lsaquo;</button>
                        <strong>${BULAN[view.getMonth()]} ${view.getFullYear()}</strong>
                        <button type="button" class="cal-nav" data-nav="1" aria-label="Bulan berikutnya">&rsaquo;</button>
                    </div><div class="cal-grid">`;
                    HARI.forEach(h => html += `<span class="cal-wd">${h}</span>`);
                    for (let i = 0; i < offset; i++) html += '<span></span>';
                    for (let n = 1; n <= total; n++) {
                        const d = new Date(view.getFullYear(), view.getMonth(), n);
                        const kelas = ['cal-day', iso(d) === iso(min) ? 'today' : '', iso(d) === sel ? 'selected' : ''].join(' ');
                        html += `<button type="button" class="${kelas}" data-date="${iso(d)}" ${d < min ? 'disabled' : ''}>${n}</button>`;
                    }
                    html += `</div><div class="cal-foot">
                        <button type="button" class="btn btn-ghost btn-sm" data-act="clear">Hapus</button>
                        <button type="button" class="btn btn-primary btn-sm" data-act="today">Hari ini</button>
                    </div>`;
                    panel.innerHTML = html;
                };

                panel.addEventListener('click', e => {
                    const nav = e.target.closest('[data-nav]');
                    const day = e.target.closest('[data-date]');
                    const act = e.target.closest('[data-act]');

                    if (nav) {
                        view = new Date(view.getFullYear(), view.getMonth() + Number(nav.dataset.nav), 1);
                        renderCal();
                    } else if (day) {
                        setValue(day.dataset.date, labelTanggal(parseISO(day.dataset.date)));
                        closeAll();
                    } else if (act && act.dataset.act === 'today') {
                        setValue(iso(min), labelTanggal(min));
                        closeAll();
                    } else if (act && act.dataset.act === 'clear') {
                        hidden.value = '';
                        setText('');
                        closeAll();
                    }
                });
            }
        });

        document.querySelector('.order-form').addEventListener('submit', e => {
            if (pickup.hidden) return;
            const kosong = [...pickup.querySelectorAll('.dd')].filter(dd => !dd.querySelector('input[type=hidden]').value);
            if (!kosong.length) return;
            e.preventDefault();
            kosong.forEach(dd => {
                dd.classList.add('invalid');
                dd.parentElement.querySelector('.field-error').hidden = false;
            });
            kosong[0].querySelector('.dd-trigger').focus();
        });
    </script>
@endpush