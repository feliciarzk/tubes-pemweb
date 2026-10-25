/* SWASH Driver: pengaturan, pelacakan lokasi, dan peta (Leaflet). */
(function () {
    'use strict';

    var KEY = 'swash_driver_settings';
    var DEFAULTS = { online: true, share: false, interval: 10, accurate: true, showTasks: true, follow: true };

    var STATUS_TEXT = {
        off: 'Pelacakan mati',
        waiting: 'Mencari lokasi...',
        active: 'Lokasi aktif dan dikirim ke admin',
        denied: 'Izin lokasi ditolak. Aktifkan lewat pengaturan situs di browser.',
        unsupported: 'Browser ini tidak mendukung lokasi.',
        error: 'Lokasi belum tersedia. Coba lagi sebentar.'
    };

    function load() {
        var saved = {};
        try { saved = JSON.parse(localStorage.getItem(KEY) || '{}') || {}; } catch (e) {}
        var out = {};
        Object.keys(DEFAULTS).forEach(function (k) { out[k] = (k in saved) ? saved[k] : DEFAULTS[k]; });
        return out;
    }
    function persist() { try { localStorage.setItem(KEY, JSON.stringify(settings)); } catch (e) {} }
    function meta(name) { var el = document.querySelector('meta[name="' + name + '"]'); return el ? el.content : ''; }

    var settings = load();
    var tracker = { watchId: null, last: null, lastSent: 0, status: 'off', posCbs: [] };

    /* ---------- Toast ---------- */
    var toastEl, toastTimer;
    function toast(msg) {
        if (!toastEl) { toastEl = document.createElement('div'); toastEl.className = 'toast'; document.body.appendChild(toastEl); }
        toastEl.textContent = msg;
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toastEl.classList.remove('show'); }, 1800);
    }

    /* ---------- UI status ---------- */
    function setStatus(s) {
        tracker.status = s;
        document.querySelectorAll('[data-track-status]').forEach(function (el) {
            el.textContent = STATUS_TEXT[s] || '';
            el.setAttribute('data-state', s);
        });
    }
    function updateOnlineUI() {
        document.querySelectorAll('[data-online-label]').forEach(function (el) { el.textContent = settings.online ? 'Online' : 'Offline'; });
        document.querySelectorAll('[data-online-dot]').forEach(function (el) { el.classList.toggle('is-on', !!settings.online); });
    }
    function showCoords(pos) {
        document.querySelectorAll('[data-track-coords]').forEach(function (el) {
            el.textContent = pos.lat.toFixed(5) + ', ' + pos.lng.toFixed(5) + ' (akurasi ±' + Math.round(pos.acc) + ' m)';
        });
    }

    /* ---------- Kirim lokasi ke server ---------- */
    function send(payload) {
        var url = meta('driver-location-url');
        if (!url) return;
        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': meta('csrf-token') },
            body: JSON.stringify(payload)
        }).catch(function () {});
    }

    function onPos(p) {
        tracker.last = { lat: p.coords.latitude, lng: p.coords.longitude, acc: p.coords.accuracy || 0 };
        showCoords(tracker.last);
        tracker.posCbs.forEach(function (cb) { cb(tracker.last); });
        setStatus('active');
        var now = Date.now();
        if (now - tracker.lastSent >= settings.interval * 1000) {
            tracker.lastSent = now;
            send({ active: true, lat: tracker.last.lat, lng: tracker.last.lng, accuracy: tracker.last.acc });
        }
    }
    function onErr(e) { setStatus(e.code === 1 ? 'denied' : 'error'); }

    function start() {
        var was = tracker.watchId !== null;
        if (was) { navigator.geolocation.clearWatch(tracker.watchId); tracker.watchId = null; }

        if (!(settings.online && settings.share)) {
            if (was) send({ active: false });
            setStatus('off');
            return;
        }
        if (!navigator.geolocation) { setStatus('unsupported'); return; }

        tracker.lastSent = 0;
        setStatus('waiting');
        tracker.watchId = navigator.geolocation.watchPosition(onPos, onErr, {
            enableHighAccuracy: !!settings.accurate, maximumAge: 5000, timeout: 20000
        });
    }

    /* ---------- Pengaturan ---------- */
    function bindSettings() {
        document.querySelectorAll('[data-setting]').forEach(function (el) {
            var key = el.getAttribute('data-setting');
            if (el.type === 'checkbox') { el.checked = !!settings[key]; } else { el.value = settings[key]; }
            el.addEventListener('change', function () {
                settings[key] = el.type === 'checkbox' ? el.checked : (isNaN(+el.value) ? el.value : +el.value);
                persist();
                updateOnlineUI();
                start();
                toast('Pengaturan disimpan');
            });
        });

        document.querySelectorAll('[data-action="test-location"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!navigator.geolocation) { setStatus('unsupported'); return; }
                btn.disabled = true;
                navigator.geolocation.getCurrentPosition(function (p) {
                    btn.disabled = false;
                    showCoords({ lat: p.coords.latitude, lng: p.coords.longitude, acc: p.coords.accuracy || 0 });
                    toast('Lokasi berhasil dibaca');
                }, function (e) {
                    btn.disabled = false;
                    setStatus(e.code === 1 ? 'denied' : 'error');
                }, { enableHighAccuracy: !!settings.accurate, timeout: 15000 });
            });
        });

        document.querySelectorAll('[data-action="reset"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                settings = Object.assign({}, DEFAULTS);
                persist();
                document.querySelectorAll('[data-setting]').forEach(function (el) {
                    var k = el.getAttribute('data-setting');
                    if (el.type === 'checkbox') { el.checked = !!settings[k]; } else { el.value = settings[k]; }
                });
                updateOnlineUI();
                start();
                toast('Pengaturan dikembalikan');
            });
        });
    }

    /* ---------- Peta ---------- */
    function pinIcon(label, kind) {
        return L.divIcon({
            className: '',
            html: '<span class="sw-pin sw-pin-' + kind + '">' + label + '</span>',
            iconSize: [30, 30], iconAnchor: [15, 15], popupAnchor: [0, -14]
        });
    }
    function popupNode(t) {
        var d = document.createElement('div');
        var b = document.createElement('strong'); b.textContent = t.customer;
        d.appendChild(b); d.appendChild(document.createElement('br'));
        d.appendChild(document.createTextNode((t.type === 'jemput' ? 'Jemput ' : 'Antar ') + t.time));
        d.appendChild(document.createElement('br'));
        var a = document.createElement('a'); a.href = t.url; a.textContent = 'Buka tugas';
        d.appendChild(a);
        return d;
    }

    function initMap(el, opts) {
        opts = opts || {};
        if (!window.L || !el) return null;
        var data = JSON.parse(el.getAttribute('data-map'));
        var map = L.map(el, { zoomControl: !!opts.zoomControl }).setView([data.outlet.lat, data.outlet.lng], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19, attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var points = [[data.outlet.lat, data.outlet.lng]];
        L.marker(points[0], { icon: pinIcon('O', 'outlet') }).addTo(map).bindPopup('Outlet SWASH');

        var layer = L.layerGroup();
        data.tasks.forEach(function (t, i) {
            L.marker([t.lat, t.lng], { icon: pinIcon(String(i + 1), t.type) }).bindPopup(popupNode(t)).addTo(layer);
            points.push([t.lat, t.lng]);
        });
        if (settings.showTasks) { layer.addTo(map); }
        map.fitBounds(points, { padding: [30, 30], maxZoom: 15 });

        var me = null, ring = null, firstFix = false;
        function draw(pos) {
            var ll = [pos.lat, pos.lng];
            if (!me) {
                ring = L.circle(ll, { radius: pos.acc, color: '#2563eb', weight: 1, fillOpacity: 0.1 }).addTo(map);
                me = L.circleMarker(ll, { radius: 8, color: '#fff', weight: 3, fillColor: '#2563eb', fillOpacity: 1 }).addTo(map);
            } else { me.setLatLng(ll); ring.setLatLng(ll); ring.setRadius(pos.acc); }
            if (!firstFix) { map.setView(ll, 15); firstFix = true; }
            else if (settings.follow) { map.panTo(ll); }
        }
        tracker.posCbs.push(draw);
        if (tracker.last) { draw(tracker.last); }

        var locate = document.querySelector('[data-action="locate"]');
        if (locate) {
            locate.addEventListener('click', function () {
                if (tracker.last) { map.setView([tracker.last.lat, tracker.last.lng], 16); }
                else { toast('Aktifkan "Bagikan lokasi" di Pengaturan dulu'); }
            });
        }
        setTimeout(function () { map.invalidateSize(); }, 250);
        return map;
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindSettings();
        updateOnlineUI();
        setStatus('off');
        start();
    });

    window.SwashDriver = { settings: settings, initMap: initMap, toast: toast };
})();