<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $unpaid = Order::ownedBy(auth()->id())
            ->where('status', 'menunggu_pembayaran')
            ->latest()->get();

        $paid = Order::ownedBy(auth()->id())
            ->whereNotNull('paid_at')
            ->latest('paid_at')->limit(10)->get();

        return view('customer.payments.index', [
            'unpaid'  => $unpaid,
            'paid'    => $paid,
            'methods' => config('swash.payment_methods'),
        ]);
    }

    public function pay(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status !== 'menunggu_pembayaran' || is_null($order->price)) {
            return back()->withErrors(['payment' => 'Pesanan ini belum bisa dibayar.']);
        }

        $data = $request->validate([
            'payment_method' => 'required|in:' . implode(',', array_keys(config('swash.payment_methods'))),
        ], ['payment_method.required' => 'Pilih metode pembayaran.']);

        // Simulasi pembayaran. Ganti dengan payment gateway kalau dibutuhkan.
        $order->update([
            'payment_method' => $data['payment_method'],
            'paid_at'        => now(),
            'status'         => 'diproses',
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pembayaran diterima. Cucianmu mulai diproses.');
    }
}
