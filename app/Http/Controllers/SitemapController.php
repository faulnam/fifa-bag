<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml for SEO search engines.
     */
    public function index(): Response
    {
        $products = Product::where('is_active', true)->select('slug', 'updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();
        $collections = Collection::where('is_active', true)->select('slug', 'updated_at')->get();
        $pages = Page::select('slug', 'updated_at')->get();
        $blogs = BlogPost::where('is_published', true)->select('slug', 'updated_at')->get();

        $content = view('sitemap', [
            'products' => $products,
            'categories' => $categories,
            'collections' => $collections,
            'pages' => $pages,
            'blogs' => $blogs,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
