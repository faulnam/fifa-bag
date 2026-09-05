<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $reviews = $user->reviews()
            ->with(['product.images', 'orderItem'])
            ->latest()
            ->paginate(10);

        return view('account.reviews.index', compact('reviews'));
    }
}
