<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fetch Categories
        $menSneakers = Category::where('slug', 'men-everyday-sneakers')->first();
        $menRunning = Category::where('slug', 'men-running-shoes')->first();
        $menLoungers = Category::where('slug', 'men-slip-ons-loungers')->first();
        $menMizzles = Category::where('slug', 'men-water-repellent-shoes')->first();
        $menHiking = Category::where('slug', 'men-hiking-trail-shoes')->first();
        $menTees = Category::where('slug', 'men-tees-tops')->first();
        $menHoodies = Category::where('slug', 'men-sweats-hoodies')->first();
        $menSocks = Category::where('slug', 'men-socks')->first();
        $bags = Category::where('slug', 'bags-accessories')->first();

        $womenSneakers = Category::where('slug', 'women-everyday-sneakers')->first();
        $womenRunning = Category::where('slug', 'women-running-shoes')->first();
        $womenFlats = Category::where('slug', 'women-flats-loungers')->first();
        $womenSlipOns = Category::where('slug', 'women-slip-ons')->first();
        $womenMizzles = Category::where('slug', 'women-water-repellent-shoes')->first();
        $womenTees = Category::where('slug', 'women-tees-tops')->first();
        $womenSocks = Category::where('slug', 'women-socks')->first();

        // 2. Fetch Collections
        $newArrivalsCol = Collection::where('slug', 'new-arrivals')->first();
        $bestSellersCol = Collection::where('slug', 'best-sellers')->first();
        $saleCol = Collection::where('slug', 'sale')->first();
        $treeCol = Collection::where('slug', 'tree-runners')->first();
        $woolCol = Collection::where('slug', 'wool-runners')->first();

        // 3. Products Master Dataset
        $productsData = [
            // ----------------------------------------------------
            // MEN SHOES
            // ----------------------------------------------------
            [
                'category_id' => $menSneakers?->id ?? 1,
                'name' => "Men's Tree Runner Go",
                'slug' => 'mens-tree-runner-go',
                'short_description' => 'Light, breezy everyday shoe made with FSC® certified eucalyptus tree fiber.',
                'description' => '<p>Designed for daily walking, travel, and non-stop movement. The Men\'s Tree Runner Go features a breathable eucalyptus tree fiber upper, cushioned SweetFoam® sugarcane midsole, and super soft castor bean oil insole.</p><p>Remarkably flexible, machine washable, and crafted with 100% natural materials for ultimate all-day comfort.</p>',
                'material_info' => 'Upper: FSC-certified TENCEL™ Lyocell (eucalyptus tree fiber). Midsole: SweetFoam® made with Brazilian sugarcane. Insole: Castor bean oil blend with ZQ merino wool lining.',
                'sustainability_note' => 'Carbon footprint: 4.87 kg CO2e. 100% carbon neutral through verified climate initiatives.',
                'base_price' => 1750000,
                'compare_at_price' => 1950000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 600,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id, $treeCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Mist Blue (Blizzard Sole)',
                        'color_hex' => '#5c778a',
                        'sizes' => ['39' => 8, '40' => 15, '41' => 12, '42' => 20, '43' => 6, '44' => 4],
                    ],
                    [
                        'color_name' => 'Natural White (Blizzard Sole)',
                        'color_hex' => '#ffffff',
                        'sizes' => ['39' => 5, '40' => 10, '41' => 14, '42' => 18, '43' => 8, '44' => 3],
                    ],
                    [
                        'color_name' => 'Forest Green',
                        'color_hex' => '#4e6e58',
                        'sizes' => ['40' => 6, '41' => 8, '42' => 12, '43' => 4],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/tree-runner-blue.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/tree-runner-white.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/tree-runner-forest.png', 'order' => 3, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $menRunning?->id ?? 1,
                'name' => "Men's Tree Dasher 2",
                'slug' => 'mens-tree-dasher-2',
                'short_description' => 'Active performance running shoe with responsive natural cushion and grip.',
                'description' => '<p>The Tree Dasher 2 is our everyday running and training shoe made with natural materials. Features an improved heel collar for locked-in support, extra-traction natural rubber pads, and high-energy return SweetFoam®.</p>',
                'material_info' => 'FSC-certified eucalyptus fiber seamless one-piece upper. SweetFoam® sugarcane midsole. FSC-certified natural rubber outsole pads.',
                'sustainability_note' => 'Carbon footprint: 7.21 kg CO2e. Completely carbon neutral through certified climate programs.',
                'base_price' => 2150000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 700,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Sage Haze',
                        'color_hex' => '#7d8d7e',
                        'sizes' => ['39' => 6, '40' => 14, '41' => 10, '42' => 16, '43' => 8, '44' => 5],
                    ],
                    [
                        'color_name' => 'Thunder Navy',
                        'color_hex' => '#2b3a4a',
                        'sizes' => ['40' => 8, '41' => 12, '42' => 15, '43' => 7, '44' => 2],
                    ],
                    [
                        'color_name' => 'Mineral Crimson',
                        'color_hex' => '#9e4747',
                        'sizes' => ['40' => 4, '41' => 6, '42' => 9, '43' => 3],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/tree-dasher-sage.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/tree-dasher-navy.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/tree-dasher-red.png', 'order' => 3, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $menSneakers?->id ?? 1,
                'name' => "Men's Wool Runner 2",
                'slug' => 'mens-wool-runner-2',
                'short_description' => 'The icon reinvented: softer, bouncier, and sustainably crafted with ZQ Merino wool.',
                'description' => '<p>Our revolutionary wool sneaker that started it all, upgraded with 15+ improvements. Cozy merino wool upper regulates temperature naturally and resists odor without synthetic chemicals.</p>',
                'material_info' => 'ZQ-certified New Zealand Merino wool upper. Sugarcane-based SweetFoam® sole. Recycled bottle polyester laces.',
                'sustainability_note' => 'Carbon footprint: 5.42 kg CO2e. 100% natural and renewable materials.',
                'base_price' => 1850000,
                'compare_at_price' => 2100000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 650,
                'collections' => array_filter([$bestSellersCol?->id, $woolCol?->id, $saleCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Natural Grey (Cream Sole)',
                        'color_hex' => '#888582',
                        'sizes' => ['39' => 4, '40' => 12, '41' => 18, '42' => 22, '43' => 10, '44' => 6],
                    ],
                    [
                        'color_name' => 'Natural Black (Dark Sole)',
                        'color_hex' => '#222222',
                        'sizes' => ['39' => 7, '40' => 15, '41' => 20, '42' => 25, '43' => 12, '44' => 8],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/wool-runner-grey.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/wool-runner-black.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $menLoungers?->id ?? 1,
                'name' => "Men's Canvas Cruiser Slip On",
                'slug' => 'mens-canvas-cruiser-slip-on',
                'short_description' => 'Effortless classic slip-on sneaker crafted with durable organic cotton canvas.',
                'description' => '<p>An ultra-versatile daily slip-on shoe. Easy on, easy off, with cushioned arch support and breathable canvas that softens with every single wear.</p>',
                'material_info' => '100% Organic cotton canvas upper, natural rubber outsole, recycled EVA footbed.',
                'sustainability_note' => 'Carbon footprint: 4.10 kg CO2e.',
                'base_price' => 1450000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 580,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Warm White',
                        'color_hex' => '#ded7cd',
                        'sizes' => ['39' => 5, '40' => 10, '41' => 15, '42' => 14, '43' => 8, '44' => 4],
                    ],
                    [
                        'color_name' => 'Blizzard White',
                        'color_hex' => '#ffffff',
                        'sizes' => ['39' => 6, '40' => 11, '41' => 16, '42' => 19, '43' => 7, '44' => 3],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/canvas-cruiser-white.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/cruiser-slipon-blizzard.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $menLoungers?->id ?? 1,
                'name' => "Men's Runner NZ Slip On",
                'slug' => 'mens-runner-nz-slip-on',
                'short_description' => 'Ribbed knit slip-on sneaker combining sock-like comfort with all-day support.',
                'description' => '<p>Step right in. The Runner NZ Slip On hugs your foot with a textured stretch-knit collar and bouncy sugarcane SweetFoam® sole for a zero-pressure walking experience.</p>',
                'material_info' => 'Ribbed engineered knit upper from eucalyptus fiber and recycled nylon.',
                'sustainability_note' => 'Carbon footprint: 4.60 kg CO2e.',
                'base_price' => 1650000,
                'compare_at_price' => 1850000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 600,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Mushroom Taupe',
                        'color_hex' => '#b2a496',
                        'sizes' => ['39' => 4, '40' => 9, '41' => 12, '42' => 16, '43' => 6, '44' => 2],
                    ],
                    [
                        'color_name' => 'Anthracite Charcoal',
                        'color_hex' => '#444240',
                        'sizes' => ['39' => 7, '40' => 14, '41' => 18, '42' => 20, '43' => 9, '44' => 5],
                    ],
                    [
                        'color_name' => 'Oatmeal Natural',
                        'color_hex' => '#ded4c5',
                        'sizes' => ['40' => 5, '41' => 8, '42' => 11, '43' => 4],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/runner-nz-mushroom.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/runner-nz-anthracite.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/runner-nz-oat.png', 'order' => 3, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $menMizzles?->id ?? 1,
                'name' => "Men's Wool Runner-up Mizzle",
                'slug' => 'mens-wool-runner-up-mizzle',
                'short_description' => 'Water-repellent high-top sneaker made with puddle-resistant ZQ Merino wool.',
                'description' => '<p>Keep feet dry and cozy no matter the forecast. Features bio-based water-repellent Puddle Guard® technology and grippy all-weather traction lugs.</p>',
                'material_info' => 'ZQ Merino wool upper treated with ECO Puddle Guard®. Natural rubber all-terrain lug outsole.',
                'sustainability_note' => 'Carbon footprint: 6.80 kg CO2e.',
                'base_price' => 2350000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => false,
                'weight_grams' => 750,
                'collections' => array_filter([$woolCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'True Black (Black Sole)',
                        'color_hex' => '#1f1f1f',
                        'sizes' => ['40' => 8, '41' => 14, '42' => 16, '43' => 7, '44' => 3],
                    ],
                    [
                        'color_name' => 'Dappled Grey',
                        'color_hex' => '#6b6967',
                        'sizes' => ['40' => 5, '41' => 9, '42' => 12, '43' => 6],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/wool-runner-black.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/wool-runner-grey.png', 'order' => 2, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // WOMEN SHOES
            // ----------------------------------------------------
            [
                'category_id' => $womenFlats?->id ?? 1,
                'name' => "Women's Tree Lounger",
                'slug' => 'womens-tree-lounger',
                'short_description' => 'Breezy eucalyptus slip-on shoe with sockless ease and cushioned comfort.',
                'description' => '<p>The ultimate casual slip-on flat for travel and weekend strolls. Silky-smooth eucalyptus tree fiber keeps your feet feeling cool and refreshed all day long.</p>',
                'material_info' => 'FSC-certified eucalyptus fiber upper, SweetFoam® sugarcane sole, merino wool lined insole.',
                'sustainability_note' => 'Carbon footprint: 3.90 kg CO2e.',
                'base_price' => 1550000,
                'compare_at_price' => 1750000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 450,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id, $treeCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Dusty Mauve',
                        'color_hex' => '#9d7370',
                        'sizes' => ['36' => 6, '37' => 14, '38' => 18, '39' => 20, '40' => 12, '41' => 4],
                    ],
                    [
                        'color_name' => 'Warm Terracotta',
                        'color_hex' => '#b87358',
                        'sizes' => ['36' => 4, '37' => 10, '38' => 15, '39' => 16, '40' => 8, '41' => 2],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/tree-lounger-pink.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/tree-lounger-terracotta.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $womenRunning?->id ?? 1,
                'name' => "Women's Tree Dasher 2",
                'slug' => 'womens-tree-dasher-2',
                'short_description' => 'High-performance running shoe engineered with natural breathable fibers.',
                'description' => '<p>Crafted for morning jogs, 5Ks, and active city routines. Features a seamless anatomical upper, padded heel counter, and natural SweetFoam® midsole for maximum bounce.</p>',
                'material_info' => 'Eucalyptus tree fiber knit upper, sugarcane SweetFoam® midsole, FSC natural rubber outsole.',
                'sustainability_note' => 'Carbon footprint: 6.90 kg CO2e.',
                'base_price' => 2150000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 620,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Sage Frost',
                        'color_hex' => '#7d8d7e',
                        'sizes' => ['36' => 5, '37' => 12, '38' => 20, '39' => 18, '40' => 10, '41' => 3],
                    ],
                    [
                        'color_name' => 'Ocean Navy',
                        'color_hex' => '#324a5e',
                        'sizes' => ['36' => 4, '37' => 9, '38' => 14, '39' => 15, '40' => 7],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/tree-dasher-sage.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/tree-dasher-navy.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $womenSneakers?->id ?? 1,
                'name' => "Women's Tree Runner Go",
                'slug' => 'womens-tree-runner-go',
                'short_description' => 'Everyday lightweight walking shoe with breathable eucalyptus tree fiber.',
                'description' => '<p>Your go-to shoe for everything. Cushioned, cloud-soft, machine-washable, and sustainably made for non-stop comfort from desk to weekend.</p>',
                'material_info' => 'FSC eucalyptus fiber, SweetFoam® sugarcane midsole.',
                'sustainability_note' => 'Carbon footprint: 4.40 kg CO2e.',
                'base_price' => 1750000,
                'compare_at_price' => 1950000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 540,
                'collections' => array_filter([$bestSellersCol?->id, $treeCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Sky Blue (White Sole)',
                        'color_hex' => '#5c778a',
                        'sizes' => ['36' => 8, '37' => 16, '38' => 22, '39' => 20, '40' => 14, '41' => 6],
                    ],
                    [
                        'color_name' => 'Pure Blizzard White',
                        'color_hex' => '#ffffff',
                        'sizes' => ['36' => 6, '37' => 12, '38' => 19, '39' => 18, '40' => 10, '41' => 4],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/tree-runner-blue.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/tree-runner-white.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $womenSlipOns?->id ?? 1,
                'name' => "Women's Canvas Cruiser Slip On",
                'slug' => 'womens-canvas-cruiser-slip-on',
                'short_description' => 'Clean organic canvas slip-on with cushioned arch support.',
                'description' => '<p>Classic slip-on silhouette made modern with 100% organic cotton canvas and zero break-in period. Casual, crisp, and comfortable.</p>',
                'material_info' => '100% Organic cotton canvas upper, SweetFoam® insole.',
                'sustainability_note' => 'Carbon footprint: 3.85 kg CO2e.',
                'base_price' => 1450000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 520,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Warm White',
                        'color_hex' => '#ded7cd',
                        'sizes' => ['36' => 6, '37' => 14, '38' => 20, '39' => 18, '40' => 12, '41' => 5],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/canvas-cruiser-white.png', 'order' => 1, 'is_primary' => true],
                ],
            ],
            [
                'category_id' => $womenSlipOns?->id ?? 1,
                'name' => "Women's Cruiser Slip On",
                'slug' => 'womens-cruiser-slip-on',
                'short_description' => 'Seamless knit slip-on sneaker for lightweight easy-wear comfort.',
                'description' => '<p>A timeless slip-on silhouette in bright Blizzard white. Lightweight, bouncy, and built for wherever the day takes you.</p>',
                'material_info' => 'Engineered knit upper with SweetFoam® sugarcane sole.',
                'sustainability_note' => 'Carbon footprint: 4.15 kg CO2e.',
                'base_price' => 1650000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 530,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Blizzard White',
                        'color_hex' => '#ffffff',
                        'sizes' => ['36' => 5, '37' => 12, '38' => 18, '39' => 16, '40' => 9, '41' => 3],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/cruiser-slipon-blizzard.png', 'order' => 1, 'is_primary' => true],
                ],
            ],
            [
                'category_id' => $womenSlipOns?->id ?? 1,
                'name' => "Women's Runner NZ Slip On",
                'slug' => 'womens-runner-nz-slip-on',
                'short_description' => 'Ribbed knit slip-on sneaker with premium shock absorption.',
                'description' => '<p>Experience walking on clouds with our Runner NZ Slip On. Flexible ribbed collar fits like a second skin while SweetFoam® absorbs impact effortlessly.</p>',
                'material_info' => 'FSC eucalyptus fiber ribbed knit upper.',
                'sustainability_note' => 'Carbon footprint: 4.50 kg CO2e.',
                'base_price' => 1750000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 560,
                'collections' => array_filter([$bestSellersCol?->id, $newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Mushroom Taupe',
                        'color_hex' => '#b2a496',
                        'sizes' => ['36' => 7, '37' => 15, '38' => 20, '39' => 18, '40' => 11, '41' => 4],
                    ],
                    [
                        'color_name' => 'Anthracite Charcoal',
                        'color_hex' => '#444240',
                        'sizes' => ['36' => 6, '37' => 12, '38' => 17, '39' => 15, '40' => 10, '41' => 3],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/runner-nz-mushroom.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/runner-nz-anthracite.png', 'order' => 2, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // APPAREL & ACCESSORIES
            // ----------------------------------------------------
            [
                'category_id' => $menTees?->id ?? 1,
                'name' => "Men's Sea Tee Classic",
                'slug' => 'mens-sea-tee-classic',
                'short_description' => 'Soft, breathable daily t-shirt crafted with organic cotton and crab shell fiber.',
                'description' => '<p>Meet the world\'s most innovative natural t-shirt. Blended with Chitosan (a renewable fiber made from discarded crab shells) and organic Peruvian cotton to naturally stay fresh longer.</p>',
                'material_info' => '65% Organic Peruvian Pima Cotton, 35% SeaCell™ Lyocell with Chitosan.',
                'sustainability_note' => 'Carbon footprint: 6.30 kg CO2e. 100% natural and microplastic-free.',
                'base_price' => 650000,
                'compare_at_price' => 750000,
                'is_active' => true,
                'is_featured' => false,
                'weight_grams' => 250,
                'collections' => array_filter([$newArrivalsCol?->id, $saleCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Natural White',
                        'color_hex' => '#ffffff',
                        'sizes' => ['S' => 10, 'M' => 20, 'L' => 25, 'XL' => 15],
                    ],
                    [
                        'color_name' => 'Classic Charcoal',
                        'color_hex' => '#2b2b2b',
                        'sizes' => ['S' => 8, 'M' => 18, 'L' => 20, 'XL' => 12],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/wool-runner-black.png', 'order' => 1, 'is_primary' => true],
                ],
            ],
            [
                'category_id' => $menSocks?->id ?? 1,
                'name' => "Trino™ Tubers Crew Socks",
                'slug' => 'trino-tubers-crew-socks',
                'short_description' => 'Breathable everyday crew socks made with Tree & Merino wool blend.',
                'description' => '<p>The softest socks on earth. Engineered with our proprietary Trino™ yarn, blending breathable eucalyptus tree fiber with silky ZQ Merino wool.</p>',
                'material_info' => '50% TENCEL™ Lyocell, 35% ZQ Merino Wool, 12% Recycled Nylon, 3% Spandex.',
                'sustainability_note' => 'Carbon footprint: 1.20 kg CO2e.',
                'base_price' => 250000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => false,
                'weight_grams' => 100,
                'collections' => array_filter([$bestSellersCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Heather Grey',
                        'color_hex' => '#888582',
                        'sizes' => ['S/M' => 30, 'L/XL' => 40],
                    ],
                    [
                        'color_name' => 'Natural Black',
                        'color_hex' => '#212121',
                        'sizes' => ['S/M' => 25, 'L/XL' => 35],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/wool-runner-grey.png', 'order' => 1, 'is_primary' => true],
                ],
            ],
            [
                'category_id' => $bags?->id ?? 1,
                'name' => "fifa Anytime Tote Bag",
                'slug' => 'fifa-anytime-tote-bag',
                'short_description' => 'Durable organic cotton canvas tote for groceries, beach, and daily travel.',
                'description' => '<p>Generous capacity, reinforced shoulder straps, and interior pocket for your essentials. Built from 100% heavyweight organic cotton canvas to replace single-use plastic forever.</p>',
                'material_info' => '100% Heavyweight Organic Cotton Canvas (14oz).',
                'sustainability_note' => 'Carbon footprint: 2.10 kg CO2e.',
                'base_price' => 450000,
                'compare_at_price' => 550000,
                'is_active' => true,
                'is_featured' => false,
                'weight_grams' => 350,
                'collections' => array_filter([$newArrivalsCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Natural Canvas',
                        'color_hex' => '#e8e2d5',
                        'sizes' => ['One Size' => 50],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/canvas-cruiser-white.png', 'order' => 1, 'is_primary' => true],
                ],
            ],
        ];

        // 4. Seed Products, Variants, Images & Reviews
        foreach ($productsData as $data) {
            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'material_info' => $data['material_info'],
                    'sustainability_note' => $data['sustainability_note'],
                    'base_price' => $data['base_price'],
                    'compare_at_price' => $data['compare_at_price'],
                    'is_active' => $data['is_active'],
                    'is_featured' => $data['is_featured'],
                    'weight_grams' => $data['weight_grams'],
                ]
            );

            // Sync collections
            if (!empty($data['collections'])) {
                $product->collections()->sync($data['collections']);
            }

            // Sync Images
            $product->images()->delete();
            foreach ($data['images'] as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img['url'],
                    'order' => $img['order'],
                    'is_primary' => $img['is_primary'],
                ]);
            }

            // Sync Variants
            $product->variants()->delete();
            $varIndex = 1;
            foreach ($data['variants'] as $varGroup) {
                foreach ($varGroup['sizes'] as $size => $stock) {
                    $sku = 'FIF-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(Str::slug($varGroup['color_name']), 0, 4)) . '-' . $size . '-' . $varIndex;
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'color_name' => $varGroup['color_name'],
                        'color_hex' => $varGroup['color_hex'],
                        'size' => (string) $size,
                        'stock' => (int) $stock,
                        'price_override' => null,
                        'is_active' => true,
                    ]);
                    $varIndex++;
                }
            }

            // Seed Sample Verified Reviews for each product
            $user = User::first();
            if ($user && $product->reviews()->count() === 0) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => 5,
                    'title' => 'Sepatu paling nyaman yang pernah saya pakai!',
                    'comment' => 'Materialnya sangat sejuk di kaki dan solnya empuk luar biasa. Dipakai jalan seharian tidak membuat pegal sama sekali.',
                    'is_approved' => true,
                ]);
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => 5,
                    'title' => 'Sangat berkualitas dan ramah lingkungan',
                    'comment' => 'Desainnya clean, minimalis, dan sangat cocok dipadukan dengan celana apapun. Worth every penny!',
                    'is_approved' => true,
                ]);
            }
        }

        echo "ProductSeeder completed: " . count($productsData) . " rich products with transparent PNGs and variants seeded.\n";
    }
}
