<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::ownedBy(auth()->id())->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('customer.orders.create', [
            'services' => config('swash.services'),
            'slots'    => config('swash.pickup_slots'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service'       => 'required|in:' . implode(',', array_keys(config('swash.services'))),
            'delivery_type' => 'required|in:antar_jemput,antar_saja',
            'address_type'  => 'required|in:profil,lain',
            'address_other' => 'required_if:address_type,lain|nullable|string|max:500',
            'pickup_date'   => 'required_if:delivery_type,antar_jemput|nullable|date|after_or_equal:today',
            'pickup_time'   => 'required_if:delivery_type,antar_jemput|nullable|in:' . implode(',', config('swash.pickup_slots')),
            'notes'         => 'nullable|string|max:500',
        ], [
            'service.required'            => 'Pilih layanan dulu.',
            'address_other.required_if'   => 'Tulis alamat lengkapnya.',
            'pickup_date.required_if'     => 'Pilih tanggal pickup.',
            'pickup_date.after_or_equal'  => 'Tanggal pickup tidak boleh lewat.',
            'pickup_time.required_if'     => 'Pilih jam pickup.',
        ]);

        $user = auth()->user();
        $address = $data['address_type'] === 'profil' ? $user->address : $data['address_other'];

        if (blank($address)) {
            return back()
                ->withErrors(['address_type' => 'Alamat di profil masih kosong. Isi di halaman Profil atau pakai alamat lain.'])
                ->withInput();
        }

        $pickup = $data['delivery_type'] === 'antar_jemput';

        $order = Order::create([
            'code'          => 'TMP-' . uniqid(),
            'user_id'       => $user->id,
            'service'       => $data['service'],
            'delivery_type' => $data['delivery_type'],
            'address_type'  => $data['address_type'],
            'address'       => $address,
            'pickup_date'   => $pickup ? $data['pickup_date'] : null,
            'pickup_time'   => $pickup ? $data['pickup_time'] : null,
            'notes'         => $data['notes'] ?? null,
            // antar saja: pelanggan antar ke toko, admin yang menimbang
            'status'        => $pickup ? 'menunggu_pickup' : 'menunggu_konfirmasi',
        ]);

        $order->update(['code' => 'SW-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)]);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pesanan dibuat. ' . ($pickup
                ? 'Driver akan menimbang cucianmu saat pickup.'
                : 'Antar cucianmu ke toko untuk ditimbang.'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('customer.orders.show', compact('order'));
    }

    public function schedule()
    {
        $orders = Order::ownedBy(auth()->id())
            ->where('delivery_type', 'antar_jemput')
            ->where('status', 'menunggu_pickup')
            ->orderBy('pickup_date')
            ->get();

        return view('customer.orders.schedule', compact('orders'));
    }
}
