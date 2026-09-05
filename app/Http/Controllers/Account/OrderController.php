<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with(['items.variant.product.images', 'payment', 'shipment'])
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    /**
     * Display the specified order with vertical tracking timeline.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $order->loadMissing([
            'items.variant.product.images',
            'payment',
            'shipment.trackings',
        ]);

        return view('account.orders.show', compact('order'));
    }
}
