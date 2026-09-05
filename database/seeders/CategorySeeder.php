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
        $men = Category::firstOrCreate(
            ['slug' => 'men'],
            [
                'parent_id' => null,
                'gender' => 'men',
                'name' => 'Men',
                'description' => 'Everyday comfort shoes and apparel made with natural materials for men.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menShoes = Category::firstOrCreate(
            ['slug' => 'men-shoes'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Shoes',
                'description' => 'Men\'s natural material shoes',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menShoeTypes = [
            'Everyday Sneakers' => 'men-everyday-sneakers',
            'Running Shoes' => 'men-running-shoes',
            'Slip-Ons & Loungers' => 'men-slip-ons-loungers',
            'Water-Repellent Shoes' => 'men-water-repellent-shoes',
            'Hiking & Trail Shoes' => 'men-hiking-trail-shoes',
        ];

        foreach ($menShoeTypes as $name => $slug) {
            Category::firstOrCreate(
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

        $menApparel = Category::firstOrCreate(
            ['slug' => 'men-apparel'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Apparel',
                'description' => 'Men\'s sustainable apparel and tees',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Tees & Tops' => 'men-tees-tops', 'Socks' => 'men-socks', 'Sweats & Hoodies' => 'men-sweats-hoodies'] as $name => $slug) {
            Category::firstOrCreate(
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
        $women = Category::firstOrCreate(
            ['slug' => 'women'],
            [
                'parent_id' => null,
                'gender' => 'women',
                'name' => 'Women',
                'description' => 'Everyday comfort shoes and apparel made with natural materials for women.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        $womenShoes = Category::firstOrCreate(
            ['slug' => 'women-shoes'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Shoes',
                'description' => 'Women\'s natural material shoes',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $womenShoeTypes = [
            'Everyday Sneakers' => 'women-everyday-sneakers',
            'Running Shoes' => 'women-running-shoes',
            'Flats & Loungers' => 'women-flats-loungers',
            'Water-Repellent Shoes' => 'women-water-repellent-shoes',
            'Slip-Ons' => 'women-slip-ons',
        ];

        foreach ($womenShoeTypes as $name => $slug) {
            Category::firstOrCreate(
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

        $womenApparel = Category::firstOrCreate(
            ['slug' => 'women-apparel'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Apparel',
                'description' => 'Women\'s sustainable apparel',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Tees & Tops' => 'women-tees-tops', 'Socks' => 'women-socks', 'Bags & Accessories' => 'women-bags-accessories'] as $name => $slug) {
            Category::firstOrCreate(
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
                'title' => 'New Arrivals',
                'slug' => 'new-arrivals',
                'description' => 'Fresh styles and seasonal colorways crafted with premium merino wool and eucalyptus tree fiber.',
                'order' => 1,
            ],
            [
                'title' => 'Best Sellers',
                'slug' => 'best-sellers',
                'description' => 'Our most-loved shoes designed for unmatched everyday comfort and versatile styling.',
                'order' => 2,
            ],
            [
                'title' => 'Sale',
                'slug' => 'sale',
                'description' => 'Limited-time special prices on our sustainable favorites.',
                'order' => 3,
            ],
            [
                'title' => 'Tree Runners',
                'slug' => 'tree-runners',
                'description' => 'Light, breezy shoes made from responsibly sourced eucalyptus tree fiber.',
                'order' => 4,
            ],
            [
                'title' => 'Wool Runners',
                'slug' => 'wool-runners',
                'description' => 'Cozy, breathable sneakers made with soft ZQ certified merino wool.',
                'order' => 5,
            ],
        ];

        foreach ($collections as $col) {
            Collection::firstOrCreate(
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
