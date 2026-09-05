<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_payment' => $user->orders()->where('status', 'pending_payment')->count(),
            'in_shipping' => $user->orders()->whereIn('status', ['paid', 'processing', 'ready_to_ship', 'shipped'])->count(),
            'completed_orders' => $user->orders()->whereIn('status', ['delivered', 'completed'])->count(),
            'wishlist_count' => $user->wishlists()->count(),
            'reviews_count' => $user->reviews()->count(),
        ];

        $defaultAddress = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->first();

        $recentOrders = $user->orders()
            ->with(['items.variant.product.images', 'shipment.trackings', 'payment'])
            ->latest()
            ->take(3)
            ->get();

        return view('account.dashboard', compact('user', 'stats', 'defaultAddress', 'recentOrders'));
    }
}
