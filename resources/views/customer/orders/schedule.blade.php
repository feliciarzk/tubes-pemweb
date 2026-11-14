@extends('layouts.customer')

@section('title', 'Antar Jemput')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    <header class="admin-topbar">
        <h1>Antar Jemput</h1>
    </header>

    @php
        // TODO backend: ubah jadi false kalau controller + data pesanan sudah jadi
        $demo = true;

        // urutan status; sesuaikan dgn nilai di kolom status kamu
        $flowFull = [
            'menunggu_jemput'     => 'Menunggu dijemput',
            'dijemput'            => 'Driver menuju lokasimu',
            'menunggu_acc'        => 'Ditimbang, menunggu konfirmasi admin',
            'menunggu_pembayaran' => 'Menunggu pembayaran',
            'diproses'            => 'Sedang dicuci',
            'siap_antar'          => 'Siap diantar',
            'diantar'             => 'Sedang diantar',
            'selesai'             => 'Selesai',
        ];

        // warna badge: pakai class status yang sudah ada di customer.css
        $statusClass = [
            'menunggu_jemput'     => 'status-waiting',
            'dijemput'            => 'status-onway',
            'menunggu_acc'        => 'status-waiting',
            'menunggu_pembayaran' => 'status-payment',
            'diproses'            => 'status-processing',
            'siap_antar'          => 'status-scheduled',
            'diantar'             => 'status-delivery',
            'selesai'             => 'status-complete',
        ];

        // teks status laporan driver (backend nanti mengisi status: baru / diproses / selesai)
        $reportText = [
            'baru'     => 'Laporanmu sudah terkirim dan menunggu ditinjau admin.',
            'diproses' => 'Laporanmu sedang ditangani admin.',
            'selesai'  => 'Laporanmu sudah ditindaklanjuti admin.',
        ];

        // 95 menit -> "1 jam 35 menit", 25 menit -> "25 menit", 30 jam -> "1 hari 6 jam"
        $durasi = function (int $menit): string {
            $jam = intdiv($menit, 60);
            $sisa = $menit % 60;
            if ($jam >= 24) {
                $hari = intdiv($jam, 24);
                $jamSisa = $jam % 24;
                return $hari . ' hari' . ($jamSisa ? ' ' . $jamSisa . ' jam' : '');
            }
            if ($jam > 0) {
                return $jam . ' jam' . ($sisa ? ' ' . $sisa . ' menit' : '');
            }
            return max(1, $sisa) . ' menit';
        };

        // data palsu supaya tampilan bisa dilihat tanpa backend. ganti status lewat ?status=...
        if ($demo) {
            $demoStatus = request('status', 'diantar');
            // sudah bayar kalau status sudah lewat tahap pembayaran; ?telat=1 untuk simulasi terlambat
            $demoPaid = in_array($demoStatus, ['diproses', 'siap_antar', 'diantar', 'selesai'])
                ? now()->subHours(request()->has('telat') ? 30 : 5)
                : null;
            $orders = collect([(object) [
                'id' => 132,
                'code' => 'SW-00132',
                'service_name' => 'Cuci & Setrika',
                'service_type' => 'antar_jemput',
                'status' => $demoStatus,
                'pickup_date' => now(),
                'pickup_time' => '10:00',
                'address' => 'Jl. Contoh No. 1, Surabaya',
                'weight_kg' => 4.5,
                'total_price' => 36000,
                'paid_at' => $demoPaid,
                'driver_arrived' => false,
                'driver_lat' => request()->has('nolokasi') ? null : -7.2975,
                'driver_lng' => 112.7390,
                'pickup_lat' => -7.3126,
                'pickup_lng' => 112.7283,
                'delivery_lat' => -7.3126,
                'delivery_lng' => 112.7283,
                'driver' => (object) ['name' => 'Bagas Pratama', 'phone' => '081915854566'],
            ]]);
        }
    @endphp

    @if ($demo)
        <div class="notice">
            Mode demo (data palsu). Lihat tahap lain:
            <a href="?status=dijemput">dijemput</a>,
            <a href="?status=menunggu_pembayaran">menunggu pembayaran</a>,
            <a href="?status=diproses">diproses</a>,
            <a href="?status=diproses&amp;telat=1">terlambat</a>,
            <a href="?status=diantar">diantar</a>,
            <a href="?status=selesai">selesai</a>,
            <a href="?status=dijemput&amp;nolokasi=1">lokasi driver belum ada</a>,
            <a href="?status=diantar&amp;laporan=diproses">sudah lapor</a>
        </div>
    @endif

    @forelse ($orders as $order)
        @php
            $driver = $order->driver;
            $flow = $order->service_type === 'antar_jemput'
                ? $flowFull
                : array_slice($flowFull, 2, null, true); // tanpa tahap jemput
            $keys = array_keys($flow);
            $current = array_search($order->status, $keys);
            $isDelivering = $order->status === 'diantar';
            $showDriver = $driver && in_array($order->status, ['dijemput', 'diantar']);
            $showMap = $showDriver && $order->driver_lat && $order->driver_lng;
            $reportStatus = $demo ? request('laporan') : ($order->driverReport->status ?? null); // TODO backend
            $destLat = $isDelivering ? $order->delivery_lat : $order->pickup_lat;
            $destLng = $isDelivering ? $order->delivery_lng : $order->pickup_lng;

            // link: detail & bayar selalu ke halaman asli; di mode demo yang butuh backend dimatikan
            $urlShow = route('customer.orders.show', $order->id);
            $urlPay = route('customer.payments.index');
            $urlTrack = $demo ? '' : route('customer.orders.tracking', $order);
            $urlReport = $demo ? '#' : route('customer.orders.report', $order); // TODO backend

            // target pengantaran = 1 hari setelah pembayaran berhasil
            $paidAt = $order->paid_at ? \Carbon\Carbon::parse($order->paid_at) : null;
            $target = $paidAt?->copy()->addDay();
            $isLate = $target && $order->status !== 'selesai' && now()->greaterThan($target);
            $teksSelisih = $target ? $durasi(max(1, (int) floor(abs($target->diffInMinutes(now()))))) : '';

            // chat langsung ke WhatsApp driver (driver ditugaskan oleh admin)
            $waNomor = preg_replace('/\D/', '', $driver?->phone ?? '');
            $waNomor = str_starts_with($waNomor, '0') ? '62' . substr($waNomor, 1) : $waNomor;
            $urlChat = $driver
                ? 'https://wa.me/' . $waNomor . '?text=' . rawurlencode('Halo kak ' . $driver->name . ', saya pelanggan SWASH. Pesanan ' . $order->code . '.')
                : '#';
        @endphp

        <section class="panel trip-card">
            <div class="trip-head">
                <div class="order-id">{{ $order->code }}<br><small>{{ $order->service_name }}</small></div>
                <div class="trip-badges">
                    @if ($isLate)
                        <span class="order-status status-late">Terlambat</span>
                    @endif
                    <span class="order-status {{ $statusClass[$order->status] ?? '' }}">{{ $flow[$order->status] ?? $order->status }}</span>
                </div>
            </div>

            <div class="stepper" aria-label="Progres pesanan">
                @foreach ($keys as $i => $key)
                    <span class="{{ $current !== false && $i <= $current ? 'done' : '' }}" title="{{ $flow[$key] }}"></span>
                @endforeach
            </div>

            @if ($target)
                <div class="eta-box {{ $isLate ? 'late' : '' }}">
                    <span>{{ $order->status === 'selesai' ? 'Target pengantaran' : 'Estimasi diantar' }}</span>
                    <strong>{{ $target->translatedFormat('l, d M Y') }}, {{ $target->format('H.i') }} WIB</strong>
                    @if ($isLate)
                        <small>Terlambat {{ $teksSelisih }} dari target.</small>
                    @elseif ($order->status !== 'selesai')
                        <small>Sisa waktu {{ $teksSelisih }}</small>
                    @endif
                </div>
            @elseif ($order->status !== 'selesai')
                <p class="eta-note">Diantar 1 hari setelah pembayaran.</p>
            @endif

            @if ($order->service_type === 'antar_jemput' && $order->status === 'menunggu_jemput')
                <p>Jemput: {{ $order->pickup_date->translatedFormat('l, d M Y') }}, {{ $order->pickup_time }} WIB<br>
                   <small>{{ $order->address }}</small></p>
            @endif

            @if ($order->status === 'menunggu_acc')
                <div class="notice">Pesananmu sudah ditimbang. Admin sedang menghitung tagihan.</div>
            @endif

            @if ($order->status === 'menunggu_pembayaran')
                <div class="notice">
                    Berat {{ $order->weight_kg }} kg, total Rp {{ number_format($order->total_price, 0, ',', '.') }}.
                    <a href="{{ $urlPay }}" class="btn btn-primary btn-sm">Bayar sekarang</a>
                </div>
            @endif

            @if ($showDriver)
                <div class="notice alert" id="arrived-{{ $order->id }}" @if (! $order->driver_arrived) hidden @endif>
                    {{ $isDelivering ? 'Driver sudah di depan. Silakan keluar untuk menerima laundry.' : 'Driver sudah di depan. Silakan serahkan laundry.' }}
                </div>
            @endif

            @if ($showMap)
                <p class="trip-eta"><span class="live-dot"></span><span id="eta-{{ $order->id }}"></span></p>
                <div class="map-box"
                     id="map-{{ $order->id }}"
                     data-order="{{ $order->id }}"
                     data-status="{{ $order->status }}"
                     data-track-url="{{ $urlTrack }}"
                     data-driver-lat="{{ $order->driver_lat }}" data-driver-lng="{{ $order->driver_lng }}"
                     data-dest-lat="{{ $destLat }}" data-dest-lng="{{ $destLng }}"
                     data-dest-label="{{ $isDelivering ? 'Alamatmu' : 'Lokasi jemput' }}"></div>
            @elseif ($showDriver)
                <div class="notice" data-wait-location>Menunggu lokasi driver. Peta akan muncul begitu driver mulai membagikan lokasinya.</div>
            @endif

            @if ($showDriver)
                <div class="driver-card">
                    <div class="driver-avatar">{{ strtoupper(substr($driver->name, 0, 1)) }}</div>
                    <div class="driver-info"><strong>{{ $driver->name }}</strong><br><small>Driver SWASH · ditugaskan admin</small></div>
                    <div class="driver-actions">
                        <a href="{{ $urlChat }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">Chat</a>
                        <a href="tel:{{ $driver->phone }}" class="btn btn-primary btn-sm">Telepon</a>
                    </div>
                </div>
            @endif

            @if ($driver)
                <dialog class="report-dialog" id="report-{{ $order->id }}">
                    <form method="POST" action="{{ $urlReport }}" class="report-form" data-report="{{ $order->id }}">
                        @csrf
                        <h3>Laporkan driver</h3>
                        <select name="category" required>
                            <option value="">Pilih masalah</option>
                            <option>Driver tidak sopan</option>
                            <option>Driver tidak datang atau terlalu lama</option>
                            <option>Laundry rusak atau hilang</option>
                            <option>Minta bayaran di luar tagihan</option>
                            <option>Lainnya</option>
                        </select>
                        <textarea name="description" rows="4" required maxlength="1000" placeholder="Ceritakan kejadiannya"></textarea>
                        <div class="driver-actions">
                            <button type="button" class="btn btn-ghost btn-sm" data-close>Batal</button>
                            <button class="btn btn-primary">Kirim laporan</button>
                        </div>
                    </form>
                </dialog>
            @endif

            <p class="notice" id="report-note-{{ $order->id }}" @if (! $reportStatus) hidden @endif>{{ $reportText[$reportStatus] ?? '' }}</p>

            <div class="trip-links">
                <a href="{{ $urlShow }}" class="text-action">Detail pesanan</a>
                @if ($driver)
                    <button type="button" class="text-action text-danger" id="report-btn-{{ $order->id }}" data-open="report-{{ $order->id }}" @if ($reportStatus) hidden @endif>Laporkan driver</button>
                @endif
            </div>
        </section>
    @empty
        <section class="panel">
            <p>Belum ada pesanan yang sedang berjalan.</p>
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">Buat pesanan</a>
        </section>
    @endforelse

    <div class="swash-toast" id="toast" hidden></div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const DEMO = {{ $demo ? 'true' : 'false' }};

        /*
         * Yang butuh backend ada di objek api ini.
         * Pas backend jadi, tinggal ganti isi fungsi yang bertanda TODO.
         */
        const api = {
            // posisi driver terbaru
            async track(el) {
                if (DEMO) {
                    // driver bergerak pelan ke tujuan
                    const pos = el._pos || [+el.dataset.driverLat, +el.dataset.driverLng];
                    const dest = [+el.dataset.destLat, +el.dataset.destLng];
                    el._pos = [pos[0] + (dest[0] - pos[0]) * 0.12, pos[1] + (dest[1] - pos[1]) * 0.12];
                    const arrived = jarakKm(el._pos, dest) < 0.05;
                    return { lat: el._pos[0], lng: el._pos[1], arrived, status: el.dataset.status };
                }
                // TODO backend: response JSON { lat, lng, arrived, status }
                const res = await fetch(el.dataset.trackUrl, { headers: { 'Accept': 'application/json' } });
                return res.json();
            }
        };

        function toast(text) {
            const t = document.getElementById('toast');
            t.textContent = text;
            t.hidden = false;
            clearTimeout(t._timer);
            t._timer = setTimeout(() => t.hidden = true, 2500);
        }

        // jarak garis lurus (km)
        function jarakKm(a, b) {
            const r = Math.PI / 180;
            const dLat = (b[0] - a[0]) * r, dLng = (b[1] - a[1]) * r;
            const x = Math.sin(dLat / 2) ** 2 + Math.cos(a[0] * r) * Math.cos(b[0] * r) * Math.sin(dLng / 2) ** 2;
            return 6371 * 2 * Math.asin(Math.sqrt(x));
        }

        function formatDurasi(menit) {
            const j = Math.floor(menit / 60), m = menit % 60;
            if (j === 0) return `${menit} menit`;
            return m ? `${j} jam ${m} menit` : `${j} jam`;
        }

        // ---------- peta ----------
        document.querySelectorAll('.map-box[data-order]').forEach(el => {
            const driverPos = [+el.dataset.driverLat, +el.dataset.driverLng];
            const destPos = [+el.dataset.destLat, +el.dataset.destLng];
            const eta = document.getElementById('eta-' + el.dataset.order);
            const arrivedNotice = document.getElementById('arrived-' + el.dataset.order);

            const map = L.map(el).fitBounds([driverPos, destPos], { padding: [40, 40] });
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            const pin = kelas => L.divIcon({ className: '', html: `<span class="map-pin ${kelas}"></span>`, iconSize: [22, 22], iconAnchor: [11, 11] });
            const label = { permanent: true, direction: 'top', offset: [0, -10] };
            L.marker(destPos, { icon: pin('map-pin-dest') }).addTo(map).bindTooltip(el.dataset.destLabel, label);
            const driverMarker = L.marker(driverPos, { icon: pin('map-pin-driver') }).addTo(map).bindTooltip('Driver', label);

            const updateEta = pos => {
                const km = jarakKm(pos, destPos);
                // asumsi kecepatan rata-rata 25 km/jam
                const menit = Math.max(1, Math.round(km / 25 * 60));
                eta.textContent = km < 0.05 ? 'Driver sudah sampai' : `Sekitar ${km.toFixed(1)} km lagi, ± ${formatDurasi(menit)}`;
            };
            updateEta(driverPos);

            const timer = setInterval(async () => {
                try {
                    const d = await api.track(el);
                    if (d.lat && d.lng) {
                        driverMarker.setLatLng([d.lat, d.lng]);
                        updateEta([d.lat, d.lng]);
                    }
                    arrivedNotice.hidden = !d.arrived;
                    // reload hanya kalau status pesanan berubah
                    if (d.status !== el.dataset.status) location.reload();
                } catch (e) {}
            }, DEMO ? 3000 : 10000);
        });

        // lokasi driver belum masuk: cek ulang tiap 15 detik
        if (!DEMO && document.querySelector('[data-wait-location]')) {
            setTimeout(() => location.reload(), 15000);
        }

        // ---------- dialog lapor ----------
        document.querySelectorAll('[data-open]').forEach(b =>
            b.addEventListener('click', () => document.getElementById(b.dataset.open).showModal()));
        document.querySelectorAll('dialog [data-close]').forEach(b =>
            b.addEventListener('click', () => b.closest('dialog').close()));

        document.querySelectorAll('form[data-report]').forEach(f => {
            f.addEventListener('submit', e => {
                if (!DEMO) return; // TODO backend: biarkan form terkirim ke route lapor
                e.preventDefault();
                const id = f.dataset.report;
                const note = document.getElementById('report-note-' + id);
                note.textContent = 'Laporanmu sudah terkirim dan menunggu ditinjau admin.';
                note.hidden = false;
                document.getElementById('report-btn-' + id).hidden = true;
                f.closest('dialog').close();
                f.reset();
                toast('Laporan terkirim ke admin');
            });
        });
    </script>
@endpush