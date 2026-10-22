<?php

return [
    // harga per kg, sesuaikan dengan layanan di dashboard
    'services' => [
        'cuci_setrika' => ['name' => 'Cuci & Setrika', 'price_per_kg' => 8000],
        'cuci_kering'  => ['name' => 'Cuci Kering',    'price_per_kg' => 6000],
        'setrika'      => ['name' => 'Setrika Saja',   'price_per_kg' => 4000],
    ],

    'pickup_slots' => ['09.00 - 11.00', '13.00 - 15.00', '16.00 - 18.00'],

    'payment_methods' => [
        'transfer' => 'Transfer bank',
        'qris'     => 'QRIS',
        'tunai'    => 'Tunai ke kurir',
    ],
];
