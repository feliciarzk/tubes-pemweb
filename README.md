# SWASH Laundry Service

Aplikasi web layanan laundry dengan antar-jemput. Pelanggan bisa memesan laundry, memantau status pesanan, dan membayar secara online tanpa perlu datang ke tempat laundry.

Proyek ini dibuat untuk mata kuliah Pemrograman Web, dibangun dengan Laravel (PHP) dan Blade, dengan tampilan memakai HTML, CSS, dan JavaScript.

## Peran Pengguna

| Peran | Fitur utama |
|---|---|
| Pelanggan | Buat pesanan, pesanan saya, antar jemput, pembayaran, profil |
| Driver | Dashboard, peta, daftar tugas, pengaturan |
| Admin | Dashboard, pesanan, jadwal pickup, driver, pembayaran, shift, laporan |

## Alur Pesanan

1. Pelanggan membuat pesanan (pilih layanan, antar-jemput atau antar saja, dan alamat).
2. Driver menjemput dan menimbang laundry.
3. Admin menyetujui pesanan dengan harga dan berat.
4. Pelanggan melihat tagihan dan membayar.
5. Laundry diproses, lalu diantar kembali ke pelanggan.

## Cara Menjalankan

Pastikan PHP dan Composer sudah terpasang.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Lalu buka `http://127.0.0.1:8000` di browser.

## Struktur Singkat

- `routes/web.php` : rute untuk pelanggan, driver, dan admin
- `resources/views/customer` : halaman pelanggan
- `resources/views/driver` : halaman driver
- `resources/views/admin` : halaman admin
- `resources/views/layouts` : layout untuk tiap peran

## Status

Saat ini fokus pada bagian frontend. Beberapa fitur masih berupa tampilan dan belum tersimpan ke database.
