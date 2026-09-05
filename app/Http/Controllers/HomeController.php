<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroSlides = HeroSlide::where('page', 'home')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $featuredCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn ($q) => $q->where('is_active', true)->with('children')])
            ->orderBy('order')
            ->get();

        $collections = Collection::where('is_active', true)
            ->orderBy('order')
            ->get();

        $newArrivals = Product::where('is_active', true)
            ->with(['variants', 'images', 'category'])
            ->latest('id')
            ->take(8)
            ->get();

        $bestSellers = Product::where('is_active', true)
            ->with(['variants', 'images', 'category'])
            ->take(8)
            ->get();

        return view('home.index', compact('heroSlides', 'featuredCategories', 'collections', 'newArrivals', 'bestSellers'));
    }
}
