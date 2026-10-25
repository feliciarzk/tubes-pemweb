(function () {
    'use strict';

    function $(s, r) { return (r || document).querySelector(s); }
    function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
    function rupiah(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }
    function shortRp(v) {
        if (v >= 1000000) return (v / 1000000).toLocaleString('id-ID') + ' jt';
        if (v >= 1000) return (v / 1000).toLocaleString('id-ID') + ' rb';
        return String(v);
    }
    function esc(s) {
        return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; });
    }
    function injectStyle(css) {
        var s = document.createElement('style');
        s.textContent = css;
        document.head.appendChild(s);
    }
    function loadScript(src, cb) {
        var s = document.createElement('script');
        s.src = src; s.onload = cb;
        s.onerror = function () { cb(new Error('gagal memuat ' + src)); };
        document.head.appendChild(s);
    }

    injectStyle(
        '[hidden]{display:none!important}' +
        '.tool-buttons{display:flex;gap:8px;flex-wrap:wrap;align-items:center}' +
        '.admin-filter{padding:9px 12px;border:1px solid var(--border);border-radius:12px;background:var(--surface);color:var(--text);font:inherit}' +
        '.table-count{font-size:12px;color:var(--faint);margin-top:12px}' +
        '.chart-wrap{position:relative;height:260px}' +
        '.revenue-summary{display:flex;align-items:baseline;gap:12px;flex-wrap:wrap;margin-bottom:14px}' +
        '.revenue-summary strong{font-size:26px;font-weight:700}' +
        '.delta-up{color:#047857;font-weight:600}.delta-down{color:#be123c;font-weight:600}' +
        '@media print{.sidebar,.no-print,.admin-search,.admin-filter,.admin-footer,.welcome-section{display:none!important}' +
        '.admin-layout{display:block;padding:0}.panel,.stat-card{box-shadow:none}body{background:#fff}}'
    );

    /* ---------- 1. Cari + filter status (+ rentang tanggal opsional) ---------- */
    function rowStatus(tr) {
        var el = tr.querySelector('.order-status');
        return el ? el.textContent.trim() : '';
    }

    function initTableTools() {
        $$('.admin-search').forEach(function (input) {
            if (input.hasAttribute('oninput')) return;            // halaman yang sudah punya filter sendiri
            var panel = input.closest('.panel');
            var table = panel && $('table.orders-table', panel);
            if (!table || !table.tBodies[0]) return;

            var body = table.tBodies[0];
            var rows = Array.prototype.slice.call(body.rows);
            var cols = table.tHead ? table.tHead.rows[0].cells.length : 1;

            var statuses = [];
            rows.forEach(function (r) {
                var s = rowStatus(r);
                if (s && statuses.indexOf(s) < 0) statuses.push(s);
            });

            var select = null;
            if (statuses.length > 1) {
                select = document.createElement('select');
                select.className = 'admin-filter';
                select.setAttribute('aria-label', 'Filter status');
                select.innerHTML = '<option value="">Semua status</option>' +
                    statuses.map(function (s) { return '<option>' + esc(s) + '</option>'; }).join('');
                input.insertAdjacentElement('afterend', select);
            }

            var from = $('[data-date-from]'), to = $('[data-date-to]');

            var empty = document.createElement('tr');
            empty.className = 'no-result';
            empty.style.display = 'none';
            empty.innerHTML = '<td colspan="' + cols + '">Tidak ada pesanan yang cocok. Ubah kata kunci atau filter.</td>';
            body.appendChild(empty);

            var count = document.createElement('p');
            count.className = 'table-count';
            var anchor = table.closest('.table-responsive') || table;
            anchor.insertAdjacentElement('afterend', count);

            function apply() {
                var q = input.value.trim().toLowerCase();
                var st = select ? select.value : '';
                var d1 = from && from.value, d2 = to && to.value;
                var shown = 0;

                rows.forEach(function (tr) {
                    var ok = (!q || tr.innerText.toLowerCase().indexOf(q) > -1) &&
                             (!st || rowStatus(tr) === st);
                    if (ok && (d1 || d2) && tr.dataset.date) {
                        if (d1 && tr.dataset.date < d1) ok = false;
                        if (d2 && tr.dataset.date > d2) ok = false;
                    }
                    tr.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });
                empty.style.display = shown ? 'none' : '';
                count.textContent = 'Menampilkan ' + shown + ' dari ' + rows.length + ' baris';
            }

            input.addEventListener('input', apply);
            if (select) select.addEventListener('change', apply);
            if (from) from.addEventListener('change', apply);
            if (to) to.addEventListener('change', apply);
            apply();
        });
    }

    /* ---------- 2. Ekspor CSV + cetak ---------- */
    function csvCell(v) { return '"' + String(v).replace(/"/g, '""') + '"'; }

    function exportCsv(table, filename) {
        if (!table) return;
        var heads = $$('thead th', table).map(function (th) { return th.textContent.trim(); });
        var skip = heads.map(function (h) { return /^aksi$/i.test(h); });

        var lines = [heads.filter(function (h, i) { return !skip[i]; }).map(csvCell).join(',')];
        $$('tbody tr', table).forEach(function (tr) {
            if (tr.classList.contains('no-result') || tr.style.display === 'none') return;
            var cells = Array.prototype.slice.call(tr.cells)
                .filter(function (c, i) { return !skip[i]; })
                .map(function (c) { return csvCell(c.innerText.replace(/\s+/g, ' ').trim()); });
            lines.push(cells.join(','));
        });

        var blob = new Blob(['\uFEFF' + lines.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(function () { URL.revokeObjectURL(a.href); }, 1000);
    }

    function today() { return new Date().toISOString().slice(0, 10); }

    function initExport() {
        // tombol manual: <button data-export="csv" data-target="#tabel" data-filename="x.csv">
        $$('[data-export="csv"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                exportCsv($(btn.dataset.target || 'main table.orders-table'), btn.dataset.filename || 'swash-' + today() + '.csv');
            });
        });
        $$('[data-action="print"]').forEach(function (btn) {
            btn.addEventListener('click', function () { window.print(); });
        });

        // otomatis di halaman Laporan dan Pesanan
        if (!/\/admin\/(laporan|pesanan)/.test(location.pathname) || $('[data-export]')) return;
        var table = $('main table.orders-table');
        var panel = table && table.closest('.panel');
        var heading = panel && $('.panel-heading', panel);
        if (!heading) return;

        var box = document.createElement('div');
        box.className = 'tool-buttons no-print';
        box.innerHTML = '<button type="button" class="btn btn-ghost btn-sm" data-act="csv">Ekspor CSV</button>' +
                        '<button type="button" class="btn btn-ghost btn-sm" data-act="print">Cetak</button>';
        heading.appendChild(box);
        box.querySelector('[data-act="csv"]').addEventListener('click', function () {
            exportCsv(table, 'swash-laporan-' + today() + '.csv');
        });
        box.querySelector('[data-act="print"]').addEventListener('click', function () { window.print(); });
    }

    /* ---------- 3. Grafik pendapatan (dashboard) ---------- */
    var SAMPLE = {
        harian: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            now:  [65000, 80000, 45000, 120000, 95000, 150000, 115000],
            prev: [50000, 70000, 60000, 90000, 85000, 110000, 100000]
        },
        bulanan: {
            labels: ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
            now:  [1850000, 2100000, 1950000, 2400000, 2650000, 2300000],
            prev: [1600000, 1700000, 1800000, 1650000, 2000000, 2200000]
        }
    };

    function sum(a) { return a.reduce(function (x, y) { return x + y; }, 0); }

    function buildRevenuePanel() {
        if ($('#revenueChart')) return $('#revenueChart');
        var anchor = $('#ringkasan');
        if (!/\/admin\/dashboard/.test(location.pathname) || !anchor) return null;

        var sec = document.createElement('section');
        sec.className = 'panel no-print';
        sec.innerHTML =
            '<div class="panel-heading"><div><span class="panel-eyebrow">PENDAPATAN</span><h3>Grafik Pendapatan</h3></div>' +
            '<div class="tool-buttons"><button type="button" class="btn btn-sm btn-primary" data-range="harian">Harian</button>' +
            '<button type="button" class="btn btn-sm btn-ghost" data-range="bulanan">Bulanan</button></div></div>' +
            '<div class="revenue-summary"><strong id="revTotal"></strong><span id="revDelta"></span></div>' +
            '<div class="chart-wrap"><canvas id="revenueChart" aria-label="Grafik pendapatan"></canvas></div>' +
            '<p class="demo-note">Data grafik masih contoh untuk keperluan tampilan.</p>';
        anchor.parentNode.insertBefore(sec, anchor);
        return $('#revenueChart');
    }

    function initRevenueChart() {
        var canvas = buildRevenuePanel();
        if (!canvas) return;

        loadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js', function (err) {
            if (err || !window.Chart) {
                var p = document.createElement('p');
                p.className = 'demo-note';
                p.textContent = 'Grafik tidak dapat dimuat. Periksa koneksi internet.';
                canvas.parentNode.replaceWith(p);
                return;
            }

            var chart = new Chart(canvas, {
                data: {
                    labels: [],
                    datasets: [
                        { type: 'bar', label: 'Periode ini', data: [], backgroundColor: '#08a5a5', borderRadius: 8, maxBarThickness: 42 },
                        { type: 'line', label: 'Periode sebelumnya', data: [], borderColor: '#a3b3bf', backgroundColor: '#a3b3bf',
                          borderDash: [6, 4], tension: 0.3, pointRadius: 3 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { callbacks: { label: function (c) { return c.dataset.label + ': ' + rupiah(c.parsed.y); } } }
                    },
                    scales: { y: { beginAtZero: true, ticks: { callback: shortRp } } }
                }
            });

            function show(range) {
                var d = SAMPLE[range];
                chart.data.labels = d.labels;
                chart.data.datasets[0].data = d.now;
                chart.data.datasets[1].data = d.prev;
                chart.update();

                var now = sum(d.now), prev = sum(d.prev);
                var pct = prev ? ((now - prev) / prev) * 100 : 0;
                $('#revTotal').textContent = rupiah(now);
                var el = $('#revDelta');
                el.className = pct >= 0 ? 'delta-up' : 'delta-down';
                el.textContent = (pct >= 0 ? 'Naik ' : 'Turun ') + Math.abs(pct).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + '% dari periode sebelumnya';

                $$('[data-range]').forEach(function (b) {
                    var active = b.dataset.range === range;
                    b.className = 'btn btn-sm ' + (active ? 'btn-primary' : 'btn-ghost');
                });
            }

            $$('[data-range]').forEach(function (b) {
                b.addEventListener('click', function () { show(b.dataset.range); });
            });
            show('harian');
        });
    }

    /* ---------- 4. Pesan sukses hilang sendiri ---------- */
    function initFlash() {
        $$('.flash, .assign-flash').forEach(function (el) {
            setTimeout(function () { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; }, 5000);
            setTimeout(function () { el.hidden = true; }, 5500);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTableTools();
        initExport();
        initRevenueChart();
        initFlash();
    });
})();