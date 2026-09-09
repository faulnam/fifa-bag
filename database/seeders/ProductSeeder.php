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
        $menBackpacks = Category::where('slug', 'men-backpacks')->first();
        $menBriefcases = Category::where('slug', 'men-briefcases')->first();
        $menSling = Category::where('slug', 'men-sling-bags')->first();
        $menDuffle = Category::where('slug', 'men-duffle-travel')->first();
        $menTote = Category::where('slug', 'men-tote-bags')->first();
        $menWallets = Category::where('slug', 'men-wallets')->first();

        $womenTote = Category::where('slug', 'women-tote-bags')->first();
        $womenShoulder = Category::where('slug', 'women-shoulder-bags')->first();
        $womenCrossbody = Category::where('slug', 'women-crossbody-bags')->first();
        $womenHandbags = Category::where('slug', 'women-handbags')->first();
        $womenMiniBackpacks = Category::where('slug', 'women-mini-backpacks')->first();
        $womenWallets = Category::where('slug', 'women-wallets')->first();

        // 2. Fetch Collections
        $newArrivalsCol = Collection::where('slug', 'new-arrivals')->first();
        $bestSellersCol = Collection::where('slug', 'best-sellers')->first();
        $saleCol = Collection::where('slug', 'sale')->first();
        $urbanCol = Collection::where('slug', 'urban-backpacks')->first();
        $ecoCanvasCol = Collection::where('slug', 'eco-canvas')->first();
        $leatherCol = Collection::where('slug', 'leather-essentials')->first();

        // 3. Products Master Dataset
        $productsData = [
            // ----------------------------------------------------
            // 1. MEN'S COMMUTER ROLLTOP BACKPACK 20L
            // ----------------------------------------------------
            [
                'category_id' => $menBackpacks?->id ?? 1,
                'name' => "Ransel Pria Commuter Rolltop 20L",
                'slug' => 'mens-commuter-rolltop-backpack',
                'short_description' => 'Ransel harian tahan air dengan kompartemen laptop 16 inci berbahan poliester daur ulang bersertifikasi GRS.',
                'description' => '<p>Dirancang untuk profesional urban, pesepeda, dan petualang harian. Ransel Pria Commuter Rolltop dilengkapi penutup rolltop magnetik yang dapat disesuaikan kapasitasnya, kompartemen laptop 16 inci berbalut busa pelindung, serta saku samping untuk botol minum.</p><p>Dibuat dari kain kanvas tahan cuaca daur ulang dengan lapisan tahan air bebas PFC, menjadikannya pilihan tangguh nan ramah lingkungan.</p>',
                'material_info' => 'Bahan Utama: 100% Recycled Ocean Plastic Polyester (RPET). Lapisan: Katun organik bersertifikasi GOTS. Ritsleting: YKK AquaGuard® tahan air. Gesper: Aluminium daur ulang.',
                'sustainability_note' => 'Jejak karbon: 3.42 kg CO2e. Terbuat dari 24 botol plastik daur ulang yang diselamatkan dari lautan.',
                'base_price' => 1450000,
                'compare_at_price' => 1650000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 850,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id, $urbanCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Mist Navy (Blizzard Trim)',
                        'color_hex' => '#2b3a4a',
                        'sizes' => ['20L (Reguler)' => 18, '25L (Large)' => 12],
                    ],
                    [
                        'color_name' => 'Matte Black',
                        'color_hex' => '#1f1f1f',
                        'sizes' => ['20L (Reguler)' => 25, '25L (Large)' => 15],
                    ],
                    [
                        'color_name' => 'Olive Moss',
                        'color_hex' => '#4e5d48',
                        'sizes' => ['20L (Reguler)' => 10, '25L (Large)' => 8],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/commuter-backpack-navy.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/commuter-backpack-black.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/commuter-backpack-olive.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 2. MEN'S EXECUTIVE LEATHER BRIEFCASE
            // ----------------------------------------------------
            [
                'category_id' => $menBriefcases?->id ?? 1,
                'name' => "Tas Kerja Pria Executive Leather Briefcase",
                'slug' => 'mens-executive-leather-briefcase',
                'short_description' => 'Tas kerja ramping berkelas dengan kompartemen laptop busa protektif dari kulit vegan nabati premium.',
                'description' => '<p>Sempurnakan penampilan profesional Anda dengan Executive Leather Briefcase. Menawarkan siluet terstruktur elegan, kantong dokumen ganda, slot pena & kartu nama, serta strap bahu ergonomis yang dapat dilepas pasang.</p><p>Material kulit nabati berbasis tumbuhan memberikan ketahanan luar biasa terhadap goresan tanpa merusak ekosistem hewan.</p>',
                'material_info' => 'Bahan: Premium Vegan Bio-Leather (Cactus Leather). Lining: Microfiber daur ulang lembut. Hardware: Logam kuningan lapis satin anti-karat.',
                'sustainability_note' => 'Jejak karbon: 2.85 kg CO2e. 100% bebas bahan hewani (PETA-Approved Vegan).',
                'base_price' => 1850000,
                'compare_at_price' => 2100000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 950,
                'collections' => array_filter([$bestSellersCol?->id, $leatherCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Mocha Brown',
                        'color_hex' => '#4d372c',
                        'sizes' => ['Standard (15")' => 16, 'Slim (14")' => 10],
                    ],
                    [
                        'color_name' => 'Onyx Black',
                        'color_hex' => '#1c1c1c',
                        'sizes' => ['Standard (15")' => 20, 'Slim (14")' => 12],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/executive-briefcase-brown.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/executive-briefcase-black.png', 'order' => 2, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 3. MEN'S URBAN TECHNICAL SLING PACK
            // ----------------------------------------------------
            [
                'category_id' => $menSling?->id ?? 1,
                'name' => "Tas Selempang Pria Urban Technical Sling",
                'slug' => 'mens-urban-sling-pack',
                'short_description' => 'Sling bag ringkas multifungsi berfitur weatherproof untuk mobilitas aktif harian dan gadget penting.',
                'description' => '<p>Sling bag minimalis modern yang siap menemani rutinitas kota Anda. Muat untuk tablet hingga 11 inci, dompet, kunci, dan powerbank dengan sistem strap quick-release Fidlock® magnetik yang praktis.</p>',
                'material_info' => 'Material: Cordura® EcoMade Fabric tahan gesek. Gesper magnet: Quick-release Fidlock®. Ritsleting anti-maling terlindung.',
                'sustainability_note' => 'Jejak karbon: 1.65 kg CO2e. Bebas emisi karbon 100% melalui offset iklim tersertifikasi.',
                'base_price' => 890000,
                'compare_at_price' => 990000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 420,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Matte Black',
                        'color_hex' => '#1a1a1a',
                        'sizes' => ['One Size (6L)' => 28],
                    ],
                    [
                        'color_name' => 'Urban Grey',
                        'color_hex' => '#6e6e6e',
                        'sizes' => ['One Size (6L)' => 15],
                    ],
                    [
                        'color_name' => 'Olive Green',
                        'color_hex' => '#4a5445',
                        'sizes' => ['One Size (6L)' => 12],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/urban-sling-black.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/urban-sling-grey.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/urban-sling-olive.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 4. MEN'S WEEKEND TRAVEL DUFFLE 35L
            // ----------------------------------------------------
            [
                'category_id' => $menDuffle?->id ?? 1,
                'name' => "Tas Travel Pria Weekend Duffle 35L",
                'slug' => 'mens-weekend-travel-duffle',
                'short_description' => 'Duffle bag kapasitas luas dengan kompartemen sepatu terpisah dan strap empuk ergonomis.',
                'description' => '<p>Teman bepergian akhir pekan dan sesi gym yang sempurna. Dilengkapi kantong sepatu berventilasi khusus, saku paspor tersembunyi, serta pegangan kulit kokoh yang nyaman di genggaman.</p>',
                'material_info' => 'Bahan: Kanvas daur ulang tahan cuaca dengan aksen kulit nabati. Kompartemen sepatu: Lapisan antibakteri tahan air.',
                'sustainability_note' => 'Jejak karbon: 4.10 kg CO2e. Dirancang awet dan bergaransi servis seumur hidup.',
                'base_price' => 1690000,
                'compare_at_price' => 1890000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 1100,
                'collections' => array_filter([$bestSellersCol?->id, $saleCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Charcoal Black',
                        'color_hex' => '#282828',
                        'sizes' => ['35L (Weekend)' => 20, '45L (Overnight)' => 10],
                    ],
                    [
                        'color_name' => 'Navy Slate',
                        'color_hex' => '#2d3b4e',
                        'sizes' => ['35L (Weekend)' => 14, '45L (Overnight)' => 8],
                    ],
                    [
                        'color_name' => 'Olive Moss',
                        'color_hex' => '#485242',
                        'sizes' => ['35L (Weekend)' => 12],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/travel-duffle-black.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/travel-duffle-navy.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/travel-duffle-olive.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 5. MEN'S SAGE CANVAS DAYPACK 18L
            // ----------------------------------------------------
            [
                'category_id' => $menBackpacks?->id ?? 1,
                'name' => "Ransel Pria Sage Canvas Daypack 18L",
                'slug' => 'mens-sage-commuter-daypack',
                'short_description' => 'Ransel kanvas organik berstruktur kokoh dan sejuk di punggung untuk aktivitas santai maupun kerja.',
                'description' => '<p>Daypack serbaguna dengan bantalan punggung berpori sejuk dan kompartemen laptop 15 inci. Desain minimalis tak lekang oleh waktu yang cocok dipadukan dengan berbagai gaya pakaian.</p>',
                'material_info' => 'Bahan Utama: 100% Organic Heavyweight Duck Canvas 16oz. Tali bahu: Busa bio SweetFoam® ramah bumi.',
                'sustainability_note' => 'Jejak karbon: 2.70 kg CO2e. Menggunakan pewarna alami bebas racun (non-toxic vegetable dye).',
                'base_price' => 1350000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 780,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id, $urbanCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Sage Green',
                        'color_hex' => '#7d8d7e',
                        'sizes' => ['18L (Standard)' => 24],
                    ],
                    [
                        'color_name' => 'Charcoal Grey',
                        'color_hex' => '#3d3d3d',
                        'sizes' => ['18L (Standard)' => 16],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/sage-daypack-green.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/sage-daypack-charcoal.png', 'order' => 2, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 6. MEN'S UTILITY ORGANIC CANVAS TOTE
            // ----------------------------------------------------
            [
                'category_id' => $menTote?->id ?? 1,
                'name' => "Tote Bag Pria Utility Organic Canvas",
                'slug' => 'mens-heavyweight-canvas-tote',
                'short_description' => 'Tote bag kanvas tebal dengan saku botol air internal dan resleting YKK kuat.',
                'description' => '<p>Tote bag pria berkapasitas lega yang dirancang tangguh untuk membawa laptop, buku, botol minum, dan perlengkapan harian dengan rapi dan terorganisir.</p>',
                'material_info' => 'Bahan: Heavy Cotton Canvas 18oz Organik & Leather Handle.',
                'sustainability_note' => 'Jejak karbon: 1.80 kg CO2e.',
                'base_price' => 950000,
                'compare_at_price' => 1150000,
                'is_active' => true,
                'is_featured' => false,
                'weight_grams' => 600,
                'collections' => array_filter([$ecoCanvasCol?->id, $bestSellersCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Sand Cream',
                        'color_hex' => '#e5dec5',
                        'sizes' => ['One Size' => 20],
                    ],
                    [
                        'color_name' => 'Washed Black',
                        'color_hex' => '#2a2a2a',
                        'sizes' => ['One Size' => 18],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/canvas-tote-cream.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/canvas-tote-black.png', 'order' => 2, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 7. WOMEN'S CLASSIC CANVAS & LEATHER TOTE
            // ----------------------------------------------------
            [
                'category_id' => $womenTote?->id ?? 2,
                'name' => "Tote Bag Wanita Classic Canvas & Leather",
                'slug' => 'womens-classic-canvas-tote',
                'short_description' => 'Tote bag feminin elegan berpadu kanvas katun organik dan aksen tali kulit nabati murni.',
                'description' => '<p>Tas jinjing esensial untuk wanita modern. Memiliki proporsi sempurna untuk membawa laptop 14 inci, tablet, pouch kosmetik, dan kebutuhan harian dengan kenyamanan maksimal.</p>',
                'material_info' => 'Bahan: 100% GOTS Certified Organic Cotton Canvas. Tali: Vegetable-tanned leather Italia.',
                'sustainability_note' => 'Jejak karbon: 2.10 kg CO2e. 100% biodegradable and eco-friendly.',
                'base_price' => 1190000,
                'compare_at_price' => 1390000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 650,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id, $ecoCanvasCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Warm Cream',
                        'color_hex' => '#f0ece1',
                        'sizes' => ['One Size (16L)' => 30],
                    ],
                    [
                        'color_name' => 'Terracotta Rust',
                        'color_hex' => '#a65846',
                        'sizes' => ['One Size (16L)' => 15],
                    ],
                    [
                        'color_name' => 'Midnight Black',
                        'color_hex' => '#1f1f1f',
                        'sizes' => ['One Size (16L)' => 20],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/canvas-tote-cream.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/canvas-tote-terracotta.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/canvas-tote-black.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 8. WOMEN'S CRESCENT SHOULDER HOBO BAG
            // ----------------------------------------------------
            [
                'category_id' => $womenShoulder?->id ?? 2,
                'name' => "Tas Bahu Wanita Crescent Hobo Bag",
                'slug' => 'womens-crescent-shoulder-bag',
                'short_description' => 'Bentuk bulan sabit ikonik yang anggun, dibuat dari kulit sintetis apel (apple leather) ramah lingkungan.',
                'description' => '<p>Tas bahu berestetika tinggi yang memeluk bahu Anda dengan pas. Dibuat dari AppleSkin™ — inovasi material nabati dari limbah industri apel yang lentur, tahan air, dan bertekstur mewah.</p>',
                'material_info' => 'Bahan: AppleSkin™ (Bio-based vegan leather dari kulit apel). Hardware: Gold-tone satin finish.',
                'sustainability_note' => 'Jejak karbon: 1.95 kg CO2e. Mengurangi limbah pertanian buah apel secara signifikan.',
                'base_price' => 1590000,
                'compare_at_price' => null,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 490,
                'collections' => array_filter([$newArrivalsCol?->id, $bestSellersCol?->id, $leatherCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Oat Cream',
                        'color_hex' => '#ede7dc',
                        'sizes' => ['Medium' => 24, 'Mini' => 12],
                    ],
                    [
                        'color_name' => 'Warm Brown',
                        'color_hex' => '#6e4a35',
                        'sizes' => ['Medium' => 18, 'Mini' => 8],
                    ],
                    [
                        'color_name' => 'Noir Black',
                        'color_hex' => '#1a1a1a',
                        'sizes' => ['Medium' => 20],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/crescent-shoulder-cream.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/crescent-shoulder-brown.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/crescent-shoulder-black.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 9. WOMEN'S FLAP CROSSBODY BAG
            // ----------------------------------------------------
            [
                'category_id' => $womenCrossbody?->id ?? 2,
                'name' => "Tas Selempang Wanita Flap Crossbody Bag",
                'slug' => 'womens-soft-mauve-crossbody',
                'short_description' => 'Tas selempang elegan warna pastel beraksen emas dengan kompartemen ganda terorganisir.',
                'description' => '<p>Pilihan tas selempang serbaguna untuk brunch santai, kencan makan malam, hingga jalan-jalan liburan. Tali bahu dapat disesuaikan panjangnya untuk model sling maupun shoulder bag.</p>',
                'material_info' => 'Bahan: Ultra-soft Vegan Nappa Leather. Lining: Poliester satin daur ulang.',
                'sustainability_note' => 'Jejak karbon: 1.82 kg CO2e.',
                'base_price' => 1490000,
                'compare_at_price' => 1690000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 520,
                'collections' => array_filter([$bestSellersCol?->id, $leatherCol?->id, $saleCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Blush Mauve',
                        'color_hex' => '#9d7370',
                        'sizes' => ['Compact' => 22],
                    ],
                    [
                        'color_name' => 'Honey Tan',
                        'color_hex' => '#b28659',
                        'sizes' => ['Compact' => 18],
                    ],
                    [
                        'color_name' => 'Pitch Black',
                        'color_hex' => '#1c1c1c',
                        'sizes' => ['Compact' => 14],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/leather-crossbody-mauve.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/leather-crossbody-tan.png', 'order' => 2, 'is_primary' => false],
                    ['url' => '/images/products/leather-crossbody-black.png', 'order' => 3, 'is_primary' => false],
                ],
            ],

            // ----------------------------------------------------
            // 10. WOMEN'S MINI URBAN BACKPACK
            // ----------------------------------------------------
            [
                'category_id' => $womenMiniBackpacks?->id ?? 2,
                'name' => "Ransel Wanita Mini Urban Backpack",
                'slug' => 'womens-petite-leather-backpack',
                'short_description' => 'Ransel modis nan praktis berbahan kulit nabati tahan cipratan air untuk outfit harian casual-chic.',
                'description' => '<p>Kombinasi sempurna antara kepraktisan backpack dan keanggunan tas fashion. Dilengkapi saku belakang anti-pencurian untuk smartphone dan dompet berharga Anda.</p>',
                'material_info' => 'Bahan: Vegan Smooth Leather tahan gores dengan resleting logam halus.',
                'sustainability_note' => 'Jejak karbon: 2.20 kg CO2e.',
                'base_price' => 1390000,
                'compare_at_price' => 1550000,
                'is_active' => true,
                'is_featured' => true,
                'weight_grams' => 600,
                'collections' => array_filter([$newArrivalsCol?->id, $urbanCol?->id]),
                'variants' => [
                    [
                        'color_name' => 'Blush Mauve',
                        'color_hex' => '#9d7370',
                        'sizes' => ['Mini (10L)' => 18],
                    ],
                    [
                        'color_name' => 'Midnight Black',
                        'color_hex' => '#212121',
                        'sizes' => ['Mini (10L)' => 22],
                    ],
                ],
                'images' => [
                    ['url' => '/images/products/leather-crossbody-mauve.png', 'order' => 1, 'is_primary' => true],
                    ['url' => '/images/products/commuter-backpack-black.png', 'order' => 2, 'is_primary' => false],
                ],
            ],
        ];

        // 4. Insert Products, Variants, Images & Reviews
        foreach ($productsData as $data) {
            $collections = $data['collections'] ?? [];
            $variants = $data['variants'] ?? [];
            $images = $data['images'] ?? [];
            unset($data['collections'], $data['variants'], $data['images']);

            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);

            // Sync Collections
            if (!empty($collections)) {
                $product->collections()->sync($collections);
            }

            // Sync Images
            $product->images()->delete();
            foreach ($images as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'image_path' => $img['url'],
                    'is_primary' => $img['is_primary'],
                    'order' => $img['order'],
                ]);
            }

            // Sync Variants
            $product->variants()->delete();
            foreach ($variants as $v) {
                foreach ($v['sizes'] as $size => $stock) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper(Str::slug($product->name . '-' . $v['color_name'] . '-' . $size)),
                        'color_name' => $v['color_name'],
                        'color_hex' => $v['color_hex'],
                        'size' => (string) $size,
                        'stock' => $stock,
                        'price_override' => null,
                        'is_active' => true,
                    ]);
                }
            }

            // Dummy Reviews
            if ($product->reviews()->count() === 0) {
                $reviews = [
                    [
                        'rating' => 5,
                        'title' => 'Tas terbaik yang pernah saya miliki!',
                        'comment' => 'Kualitas jahitan sangat rapi, bahannya tebal tapi ringan saat dipakai seharian. Kompartemen laptopnya juga sangat empuk.',
                        'is_approved' => true,
                    ],
                    [
                        'rating' => 5,
                        'title' => 'Sangat fungsional dan estetik',
                        'comment' => 'Modelnya minimalis dan ramah lingkungan. Cocok sekali untuk kuliah dan kerja harian.',
                        'is_approved' => true,
                    ],
                    [
                        'rating' => 4,
                        'title' => 'Bahan premium dan tahan air',
                        'comment' => 'Pernah kena gerimis dan bagian dalam tetap kering sempurna. Pengiriman biteship juga sangat cepat!',
                        'is_approved' => true,
                    ],
                ];

                $user = User::first();
                foreach ($reviews as $rev) {
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $user?->id ?? 1,
                        'rating' => $rev['rating'],
                        'title' => $rev['title'],
                        'comment' => $rev['comment'],
                        'is_approved' => $rev['is_approved'],
                    ]);
                }
            }
        }
    }
}
