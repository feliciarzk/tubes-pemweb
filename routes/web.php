<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pelengkap tampilan (tidak butuh file model / config lain)
|--------------------------------------------------------------------------
*/
if (!config('swash')) {
    config(['swash' => [
        'services' => [
            'cuci_setrika' => ['name' => 'Cuci & Setrika', 'price_per_kg' => 8000],
            'cuci_kering'  => ['name' => 'Cuci Kering',    'price_per_kg' => 6000],
            'setrika'      => ['name' => 'Setrika Saja',   'price_per_kg' => 4000],
        ],
        'pickup_slots'    => ['09.00 - 11.00', '13.00 - 15.00', '16.00 - 18.00'],
        'payment_methods' => ['transfer' => 'Transfer bank', 'qris' => 'QRIS', 'tunai' => 'Tunai ke kurir'],
    ]]);
}

if (!class_exists('SampleOrder')) {
    class SampleOrder extends \Illuminate\Database\Eloquent\Model
    {
        protected $guarded = [];
        protected $casts = ['pickup_date' => 'date', 'paid_at' => 'datetime', 'weight' => 'decimal:2'];

        const STATUSES = [
            'menunggu_pickup'     => 'Menunggu pickup',
            'menunggu_konfirmasi' => 'Menunggu konfirmasi admin',
            'menunggu_pembayaran' => 'Menunggu pembayaran',
            'diproses'            => 'Diproses',
            'diantar'             => 'Diantar',
            'selesai'             => 'Selesai',
        ];

        public function getServiceNameAttribute(): string
        {
            return config("swash.services.{$this->service}.name", $this->service);
        }

        public function getStatusLabelAttribute(): string
        {
            return self::STATUSES[$this->status] ?? $this->status;
        }

        public function getDeliveryLabelAttribute(): string
        {
            return $this->delivery_type === 'antar_jemput' ? 'Antar jemput' : 'Antar saja';
        }

        public function getStatusStepAttribute(): int
        {
            return array_search($this->status, array_keys(self::STATUSES), true) ?: 0;
        }
    }
}

/*
|--------------------------------------------------------------------------
| TAMPILAN SAJA: tanpa login, tanpa database, tanpa controller
|--------------------------------------------------------------------------
| Semua halaman bisa dibuka langsung lewat URL. Isinya data contoh.
*/

// nama & alamat contoh untuk sidebar dan form di halaman /customer/*
if (request()->is('customer/*')) {
    Auth::setUser((new \Illuminate\Foundation\Auth\User)->forceFill([
        'id'      => 0,
        'name'    => 'Pelanggan',
        'email'   => 'pelanggan@swash.test',
        'phone'   => '081234567890',
        'address' => 'Jl. Contoh No. 1, Surabaya',
        'role'    => 'customer',
    ]));
}

// data pesanan contoh (hanya objek biasa, tidak menyentuh database)
$samples = collect([
    ['id' => 130, 'code' => 'SW-00130', 'service' => 'cuci_setrika', 'delivery_type' => 'antar_jemput',
     'address_type' => 'profil', 'address' => 'Jl. Contoh No. 1, Surabaya',
     'pickup_date' => now()->addDay(), 'pickup_time' => '09.00 - 11.00',
     'status' => 'menunggu_pickup', 'created_at' => now()],
    ['id' => 129, 'code' => 'SW-00129', 'service' => 'cuci_kering', 'delivery_type' => 'antar_jemput',
     'address_type' => 'profil', 'address' => 'Jl. Contoh No. 1, Surabaya',
     'pickup_date' => now()->subDay(), 'pickup_time' => '13.00 - 15.00',
     'weight' => 5, 'price' => 30000, 'status' => 'menunggu_pembayaran', 'created_at' => now()->subDays(2)],
    ['id' => 128, 'code' => 'SW-00128', 'service' => 'cuci_setrika', 'delivery_type' => 'antar_jemput',
     'address_type' => 'profil', 'address' => 'Jl. Contoh No. 1, Surabaya',
     'pickup_date' => now()->subDays(2), 'pickup_time' => '09.00 - 11.00',
     'weight' => 4.5, 'price' => 36000, 'status' => 'diproses', 'payment_method' => 'qris',
     'paid_at' => now()->subDay(), 'created_at' => now()->subDays(3)],
    ['id' => 127, 'code' => 'SW-00127', 'service' => 'cuci_setrika', 'delivery_type' => 'antar_saja',
     'address_type' => 'lain', 'address' => 'Jl. Kos Mawar No. 5, Surabaya',
     'weight' => 3, 'price' => 24000, 'status' => 'selesai', 'payment_method' => 'transfer',
     'paid_at' => now()->subDays(9), 'created_at' => now()->subDays(10)],
])->map(fn ($row) => (new SampleOrder)->forceFill($row));

/*
|--------------------------------------------------------------------------
| Publik & auth (bawaan kamu)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('customer.home');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($data)) {
        $request->session()->regenerate();
        return redirect('/');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', function (Request $request) {
    $data = $request->validateWithBag('register', [
        'name'     => 'required|string|max:100',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create($data);
    Auth::login($user);
    $request->session()->regenerate();

    return redirect('/');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Customer
|--------------------------------------------------------------------------
*/
// link lama di dashboard.blade.php diarahkan ke URL baru
Route::redirect('/dashboard', '/customer/dashboard');
Route::redirect('/pesanan/baru', '/customer/pesanan/buat');
Route::redirect('/pesanan', '/customer/pesanan');
Route::redirect('/jadwal-pickup', '/customer/jadwal-pickup');
Route::redirect('/pembayaran', '/customer/pembayaran');
Route::redirect('/profil', '/customer/profil');

$info = fn (string $pesan) => fn () => back()->with('success', $pesan);

Route::prefix('customer')->name('customer.')->group(function () use ($samples, $info) {
    Route::get('/dashboard', fn () => view('customer.dashboard'))->name('dashboard');

    Route::get('/pesanan', fn () => view('customer.orders.index', [
        'orders' => new LengthAwarePaginator($samples, $samples->count(), 10),
    ]))->name('orders.index');

    Route::get('/pesanan/buat', fn () => view('customer.orders.create', [
        'services' => config('swash.services'),
        'slots'    => config('swash.pickup_slots'),
    ]))->name('orders.create');

    Route::post('/pesanan', fn () => redirect()->route('customer.orders.index')
        ->with('success', 'Mode tampilan: pesanan belum benar-benar disimpan.'))->name('orders.store');

    Route::get('/pesanan/{id}', fn ($id) => view('customer.orders.show', [
        'order' => $samples->firstWhere('id', (int) $id) ?? abort(404),
    ]))->name('orders.show');

    Route::get('/jadwal-pickup', fn () => view('customer.orders.schedule', [
        'orders' => $samples->where('status', 'menunggu_pickup')->sortBy('pickup_date'),
    ]))->name('schedule');

    Route::get('/pembayaran', fn () => view('customer.payments.index', [
        'unpaid'  => $samples->where('status', 'menunggu_pembayaran'),
        'paid'    => $samples->whereNotNull('paid_at'),
        'methods' => config('swash.payment_methods'),
    ]))->name('payments.index');

    Route::post('/pembayaran/{id}', $info('Mode tampilan: pembayaran belum benar-benar diproses.'))->name('payments.pay');

    Route::get('/profil', fn () => view('customer.profile.edit', ['user' => auth()->user()]))->name('profile.edit');
    Route::put('/profil', $info('Mode tampilan: profil belum benar-benar disimpan.'))->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
    Route::get('/pesanan', fn () => view('admin.orders'))->name('orders');
    Route::get('/jadwal-pickup', fn () => view('admin.schedules'))->name('schedules');
    Route::get('/driver', fn () => view('admin.drivers'))->name('drivers');
    Route::get('/pembayaran', fn () => view('admin.payments'))->name('payments');
    Route::get('/laporan', fn () => view('admin.reports'))->name('reports');
});