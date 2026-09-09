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
                'description' => 'Tas, ransel, dan aksesori pria ergonomis untuk aktivitas harian, kerja, dan bepergian berbahan ramah lingkungan.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menBags = Category::updateOrCreate(
            ['slug' => 'men-bags'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Tas Pria',
                'description' => 'Koleksi ransel, tas kerja, sling bag, dan travel bag pria berbahan material premium.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $menBagTypes = [
            'Ransel & Backpack' => 'men-backpacks',
            'Tas Kerja & Briefcase' => 'men-briefcases',
            'Tas Selempang & Sling' => 'men-sling-bags',
            'Duffle & Travel Bag' => 'men-duffle-travel',
            'Tote Bag Pria' => 'men-tote-bags',
        ];

        foreach ($menBagTypes as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $menBags->id,
                    'gender' => 'men',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        $menAccessories = Category::updateOrCreate(
            ['slug' => 'men-accessories'],
            [
                'parent_id' => $men->id,
                'gender' => 'men',
                'name' => 'Aksesori & Dompet',
                'description' => 'Dompet, cardholder, dan pouch organizer pria.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Dompet & Cardholder' => 'men-wallets', 'Pouch & Organizer' => 'men-pouches', 'Tali & Aksesori Tas' => 'men-straps-accessories'] as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $menAccessories->id,
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
                'description' => 'Tas, shoulder bag, dan aksesori wanita elegan untuk aktivitas harian dari bahan alami dan ramah lingkungan.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        $womenBags = Category::updateOrCreate(
            ['slug' => 'women-bags'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Tas Wanita',
                'description' => 'Koleksi tote bag, shoulder bag, crossbody, dan handbag wanita.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $womenBagTypes = [
            'Tote Bag & Shopper' => 'women-tote-bags',
            'Tas Bahu & Shoulder Bag' => 'women-shoulder-bags',
            'Tas Selempang & Crossbody' => 'women-crossbody-bags',
            'Handbag & Satchel' => 'women-handbags',
            'Ransel Modis & Mini Backpack' => 'women-mini-backpacks',
        ];

        foreach ($womenBagTypes as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $womenBags->id,
                    'gender' => 'women',
                    'name' => $name,
                    'order' => 0,
                    'is_active' => true,
                ]
            );
        }

        $womenAccessories = Category::updateOrCreate(
            ['slug' => 'women-accessories'],
            [
                'parent_id' => $women->id,
                'gender' => 'women',
                'name' => 'Aksesori & Dompet',
                'description' => 'Dompet, clutch, dan pouch kosmetik wanita.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        foreach (['Dompet & Clutches' => 'women-wallets', 'Pouch & Makeup Case' => 'women-pouches', 'Gantungan & Bag Charms' => 'women-bag-charms'] as $name => $slug) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $womenAccessories->id,
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
                'description' => 'Koleksi tas dan ransel desain terkini dari material ramah lingkungan premium.',
                'order' => 1,
            ],
            [
                'title' => 'Produk Terlaris',
                'slug' => 'best-sellers',
                'description' => 'Koleksi tas paling favorit yang dirancang untuk daya tahan maksimal dan fungsionalitas harian.',
                'order' => 2,
            ],
            [
                'title' => 'Diskon Spesial',
                'slug' => 'sale',
                'description' => 'Penawaran harga spesial terbatas untuk koleksi produk tas berkelanjutan pilihan.',
                'order' => 3,
            ],
            [
                'title' => 'Koleksi Ransel Urban',
                'slug' => 'urban-backpacks',
                'description' => 'Ransel ergonomis tahan cuaca dari material daur ulang untuk kerja dan traveling.',
                'order' => 4,
            ],
            [
                'title' => 'Koleksi Eco Canvas',
                'slug' => 'eco-canvas',
                'description' => 'Tas kanvas katun organik tahan banting dengan aksen kulit nabati alami.',
                'order' => 5,
            ],
            [
                'title' => 'Koleksi Vegan Leather',
                'slug' => 'leather-essentials',
                'description' => 'Tas kulit sintetis nabati premium tanpa uji coba hewani dan bertekstur mewah.',
                'order' => 6,
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
