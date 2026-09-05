<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Men Main Categories & Subcategories
        $men = Category::updateOrCreate(
            ['slug' => 'men'],
            [
                'parent_id' => null,
                'gender' => 'men',
                'name' => 'Pria',
                'description' => 'Sepatu dan pakaian pria yang nyaman untuk aktivitas harian dari bahan alami ramah lingkungan.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menShoes = Category::updateOrCreate(
            ['slug' => 'men-shoes'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Sepatu',
                'description' => 'Koleksi sepatu pria berbahan material alami',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menShoeTypes = [
            'Sneaker Sehari-hari' => 'men-everyday-sneakers',
            'Sepatu Lari' => 'men-running-shoes',
            'Slip-On & Santai' => 'men-slip-ons-loungers',
            'Sepatu Tahan Air' => 'men-water-repellent-shoes',
            'Sepatu Hiking & Trail' => 'men-hiking-trail-shoes',
        ];

        foreach ($menShoeTypes as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $menShoes->id,
                    'gender' => 'men',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        $menApparel = Category::updateOrCreate(
            ['slug' => 'men-apparel'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Pakaian',
                'description' => 'Pakaian ramah lingkungan dan kaos pria',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Kaos & Atasan' => 'men-tees-tops', 'Kaos Kaki' => 'men-socks', 'Jaket & Hoodie' => 'men-sweats-hoodies'] as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $menApparel->id,
                    'gender' => 'men',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        // 2. Women Main Categories & Subcategories
        $women = Category::updateOrCreate(
            ['slug' => 'women'],
            [
                'parent_id' => null,
                'gender' => 'women',
                'name' => 'Wanita',
                'description' => 'Sepatu dan pakaian wanita yang nyaman untuk aktivitas harian dari bahan alami ramah lingkungan.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        $womenShoes = Category::updateOrCreate(
            ['slug' => 'women-shoes'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Sepatu',
                'description' => 'Koleksi sepatu wanita berbahan material alami',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $womenShoeTypes = [
            'Sneaker Sehari-hari' => 'women-everyday-sneakers',
            'Sepatu Lari' => 'women-running-shoes',
            'Flat & Santai' => 'women-flats-loungers',
            'Sepatu Tahan Air' => 'women-water-repellent-shoes',
            'Slip-On' => 'women-slip-ons',
        ];

        foreach ($womenShoeTypes as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $womenShoes->id,
                    'gender' => 'women',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        $womenApparel = Category::updateOrCreate(
            ['slug' => 'women-apparel'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Pakaian',
                'description' => 'Pakaian wanita ramah lingkungan',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Kaos & Atasan' => 'women-tees-tops', 'Kaos Kaki' => 'women-socks', 'Tas & Aksesori' => 'women-bags-accessories'] as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $womenApparel->id,
                    'gender' => 'women',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        // 3. Collections
        $collections = [
            [
                'title' => 'Produk Terbaru',
                'slug' => 'new-arrivals',
                'description' => 'Pilihan gaya terbaru dan warna musiman berbahan wol merino premium dan serat pohon eukaliptus.',
                'order' => 1,
            ],
            [
                'title' => 'Produk Terlaris',
                'slug' => 'best-sellers',
                'description' => 'Koleksi sepatu paling favorit yang dirancang untuk kenyamanan tak tertandingi sepanjang hari.',
                'order' => 2,
            ],
            [
                'title' => 'Diskon Spesial',
                'slug' => 'sale',
                'description' => 'Penawaran harga spesial terbatas untuk koleksi produk berkelanjutan pilihan.',
                'order' => 3,
            ],
            [
                'title' => 'Koleksi Tree Runners',
                'slug' => 'tree-runners',
                'description' => 'Sepatu ringan dan sejuk dari serat pohon eukaliptus alami terbarukan.',
                'order' => 4,
            ],
            [
                'title' => 'Koleksi Wool Runners',
                'slug' => 'wool-runners',
                'description' => 'Sneaker empuk, hangat, dan bernapas dari wol merino bersertifikat ZQ.',
                'order' => 5,
            ],
        ];

        foreach ($collections as $col) {
            Collection::updateOrCreate(
                ['slug' => $col['slug']],
                [
                    'title' => $col['title'],
                    'description' => $col['description'],
                    'is_active' => true,
                    'order' => $col['order'],
                ]
            );
        }
    }
}
