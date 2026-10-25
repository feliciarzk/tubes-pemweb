(function () {
    'use strict';

    function $(s, r) { return (r || document).querySelector(s); }
    function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
    function rupiah(n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }
    function injectStyle(css) {
        var s = document.createElement('style');
        s.textContent = css;
        document.head.appendChild(s);
    }

    injectStyle(
        '[hidden]{display:none!important}' +
        '.js-hint{font-size:13px;color:#75899a;margin-top:10px}' +
        '.js-hint.is-warn{color:#be123c;font-weight:600}' +
        '.slot-state{display:block;font-size:12px;margin-top:2px;color:#75899a}' +
        '.is-full .option-body,.is-full{opacity:.5}' +
        '.is-full .slot-state{color:#be123c;font-weight:600}' +
        '.proof-preview{display:block;max-width:220px;max-height:220px;margin-top:10px;border-radius:12px;border:1px solid #e5efed}'
    );

    var PRICES = {
        cuci_setrika: { name: 'Cuci & Setrika', price: 8000 },
        cuci_kering:  { name: 'Cuci Kering',    price: 6000 },
        setrika:      { name: 'Setrika Saja',   price: 4000 }
    };

    function checked(name) { var el = $('[name="' + name + '"]:checked'); return el ? el.value : null; }

    function groupWrap(el) {
        return el.closest('.field') || (el.closest('.option-grid') && el.closest('.option-grid').parentElement) || el.parentElement;
    }
    function setVisible(names, show) {
        var wraps = [];
        names.forEach(function (n) {
            $$('[name="' + n + '"]').forEach(function (el) {
                var w = groupWrap(el);
                if (w && wraps.indexOf(w) < 0) wraps.push(w);
                el.disabled = !show;
            });
        });
        wraps.forEach(function (w) {
            w.hidden = !show;
            var row = w.closest('.form-row');
            if (row) {
                row.hidden = $$('.field', row).every(function (f) { return f.hidden; }) && !!$$('.field', row).length;
            }
        });
    }

    /* ---------- Tarif per kg ---------- */
    function initPriceHint() {
        var radios = $$('input[name="service"]');
        if (!radios.length) return;
        var hint = document.createElement('p');
        hint.className = 'js-hint';
        var host = radios[0].closest('.option-grid') || radios[0].parentElement;
        host.insertAdjacentElement('afterend', hint);

        function render() {
            var p = PRICES[checked('service')];
            hint.textContent = p
                ? 'Tarif ' + p.name + ': ' + rupiah(p.price) + ' per kg. Harga pasti dihitung setelah cucian ditimbang driver.'
                : 'Pilih layanan untuk melihat tarif per kg.';
        }
        radios.forEach(function (r) { r.addEventListener('change', render); });
        render();
    }

    /* ---------- Alamat & jenis pengantaran ---------- */
    function initDeliveryToggle() {
        var delivery = $$('input[name="delivery_type"]');
        function renderDelivery() {
            var v = checked('delivery_type');
            if (v === null) return;
            setVisible(['pickup_date', 'pickup_time'], v === 'antar_jemput');
        }
        delivery.forEach(function (r) { r.addEventListener('change', renderDelivery); });
        renderDelivery();

        var addr = $$('input[name="address_type"]');
        function renderAddr() {
            var v = checked('address_type');
            if (v === null) return;
            setVisible(['address'], v !== 'profil');
        }
        addr.forEach(function (r) { r.addEventListener('change', renderAddr); });
        renderAddr();
    }

    /* ---------- Slot jadwal & kapasitas (simulasi tampilan) ---------- */
    var CAPACITY = 5;
    // Data contoh. Validasi kapasitas yang sebenarnya harus dilakukan di server.
    function booked(dateStr, idx) {
        if (!dateStr) return 0;
        var d = new Date(dateStr + 'T00:00:00');
        return ((d.getDate() + d.getMonth() * 3) * 3 + idx * 2) % 7;
    }

    function initSlots() {
        var dateEl = $('[name="pickup_date"]');
        var slotEls = $$('[name="pickup_time"]');
        if (!dateEl || !slotEls.length) return;

        var t = new Date();
        var iso = t.getFullYear() + '-' + String(t.getMonth() + 1).padStart(2, '0') + '-' + String(t.getDate()).padStart(2, '0');
        if (!dateEl.min) dateEl.min = iso;

        var isSelect = slotEls[0].tagName === 'SELECT';
        var items = [];   // {el, label, idx}
        if (isSelect) {
            $$('option', slotEls[0]).filter(function (o) { return o.value; }).forEach(function (o, i) {
                items.push({ el: o, label: o.textContent.trim(), value: o.value, idx: i });
            });
        } else {
            slotEls.forEach(function (r, i) {
                items.push({ el: r, label: r.value, value: r.value, idx: i });
            });
        }

        var msg = document.createElement('p');
        msg.className = 'js-hint';
        var host = isSelect ? slotEls[0] : (slotEls[0].closest('.option-grid') || slotEls[0].parentElement);
        host.insertAdjacentElement('afterend', msg);

        function render() {
            msg.className = 'js-hint';
            if (!dateEl.value) { msg.textContent = 'Pilih tanggal untuk melihat slot yang tersedia.'; return; }

            var free = [];
            var changedSelection = null;

            items.forEach(function (it) {
                var left = CAPACITY - booked(dateEl.value, it.idx);
                var full = left <= 0;
                if (!full) free.push(it.label);

                if (isSelect) {
                    it.el.disabled = full;
                    it.el.textContent = it.label + (full ? ' (penuh)' : ' (sisa ' + left + ')');
                    if (full && it.el.selected) { changedSelection = it.label; slotEls[0].value = ''; }
                } else {
                    var box = it.el.closest('.option') || it.el.parentElement;
                    it.el.disabled = full;
                    box.classList.toggle('is-full', full);
                    var state = $('.slot-state', box);
                    if (!state) {
                        state = document.createElement('small');
                        state.className = 'slot-state';
                        ($('.option-body', box) || box).appendChild(state);
                    }
                    state.textContent = full ? 'Penuh' : 'Sisa ' + left + ' slot';
                    if (full && it.el.checked) { changedSelection = it.label; it.el.checked = false; }
                }
            });

            if (changedSelection) {
                msg.classList.add('is-warn');
                msg.textContent = 'Slot ' + changedSelection + ' sudah penuh pada tanggal ini. ' +
                    (free.length ? 'Pilih slot lain: ' + free.join(', ') + '.' : 'Pilih tanggal lain.');
            } else if (!free.length) {
                msg.classList.add('is-warn');
                msg.textContent = 'Semua slot pada tanggal ini penuh. Pilih tanggal lain.';
            } else {
                msg.textContent = 'Slot tersedia: ' + free.join(', ') + '.';
            }
        }
        dateEl.addEventListener('change', render);
        render();
    }

    /* ---------- Bukti pembayaran: validasi + preview ---------- */
    function initProofUpload() {
        $$('input[type="file"]').forEach(function (input) {
            if (!(/image/.test(input.accept) || /bukti|proof/i.test(input.name))) return;
            var img = document.createElement('img');
            img.className = 'proof-preview';
            img.alt = 'Pratinjau bukti pembayaran';
            img.hidden = true;
            var warn = document.createElement('p');
            warn.className = 'js-hint is-warn';
            warn.hidden = true;
            input.insertAdjacentElement('afterend', warn);
            warn.insertAdjacentElement('afterend', img);

            input.addEventListener('change', function () {
                var f = input.files && input.files[0];
                img.hidden = true; warn.hidden = true;
                if (!f) return;
                if (!/^image\//.test(f.type)) { warn.textContent = 'File harus berupa gambar (JPG atau PNG).'; warn.hidden = false; input.value = ''; return; }
                if (f.size > 2 * 1024 * 1024) { warn.textContent = 'Ukuran gambar maksimal 2 MB.'; warn.hidden = false; input.value = ''; return; }
                var url = URL.createObjectURL(f);
                img.onload = function () { URL.revokeObjectURL(url); };
                img.src = url;
                img.hidden = false;
            });
        });
    }

    /* ---------- Pesan sukses hilang sendiri ---------- */
    function initFlash() {
        $$('.flash').forEach(function (el) {
            setTimeout(function () { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; }, 5000);
            setTimeout(function () { el.hidden = true; }, 5500);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initPriceHint();
        initDeliveryToggle();
        initSlots();
        initProofUpload();
        initFlash();
    });
})();