<?php

namespace App\Http\Controllers;

use App\Models\StoreLocation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request): View
    {
        $query = StoreLocation::where('is_active', true)->orderBy('city')->orderBy('name');

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $stores = $query->get();
        $cities = StoreLocation::where('is_active', true)->distinct()->pluck('city')->sort();

        return view('stores.index', compact('stores', 'cities'));
    }
}
