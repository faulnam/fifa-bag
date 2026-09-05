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
                'name' => "Sepatu Pria Tree Runner Go",
                'slug' => 'mens-tree-runner-go',
                'short_description' => 'Sepatu harian ringan dan sejuk berbahan serat pohon eucalyptus bersertifikasi FSC®.',
                'description' => '<p>Dirancang untuk jalan santai harian, bepergian, dan aktivitas non-stop. Sepatu Pria Tree Runner Go dilengkapi upper serat pohon eucalyptus yang bernapas, midsole SweetFoam® dari tebu alami yang empuk, serta insole berbahan minyak biji jarak yang super lembut.</p><p>Sangat fleksibel, dapat dicuci dengan mesin cuci, dan dibuat dari 100% material alami terbarukan untuk kenyamanan optimal sepanjang hari.</p>',
                'material_info' => 'Upper: Serat TENCEL™ Lyocell bersertifikasi FSC (pohon eucalyptus). Midsole: SweetFoam® berbahan tebu alami Brasil. Insole: Campuran minyak biji jarak dengan lapisan wol merino ZQ.',
                'sustainability_note' => 'Jejak karbon: 4.87 kg CO2e. 100% netral karbon melalui inisiatif iklim terverifikasi.',
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
                'name' => "Sepatu Lari Pria Tree Dasher 2",
                'slug' => 'mens-tree-dasher-2',
                'short_description' => 'Sepatu lari performa aktif dengan bantalan alami responsif dan daya cengkeram optimal.',
                'description' => '<p>Tree Dasher 2 adalah sepatu lari dan latihan harian kami yang terbuat dari bahan alami. Dilengkapi kerah tumit yang diperbarui untuk penopang ekstra, bantalan sol karet alam anti-selip, serta SweetFoam® dengan pengembalian energi tinggi.</p>',
                'material_info' => 'Upper satu rajutan tanpa sambungan dari serat eucalyptus bersertifikasi FSC. Midsole SweetFoam® dari tebu alami. Bantalan outsole karet alam bersertifikasi FSC.',
                'sustainability_note' => 'Jejak karbon: 7.21 kg CO2e. Sepenuhnya netral karbon melalui program iklim tersertifikasi.',
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
                'name' => "Sepatu Pria Wool Runner 2",
                'slug' => 'mens-wool-runner-2',
                'short_description' => 'Ikon klasik terlahir kembali: lebih lembut, membal, dan dibuat dari wol merino ZQ alami.',
                'description' => '<p>Sneaker wol revolusioner yang mengawali segalanya, kini disempurnakan dengan lebih dari 15 peningkatan. Upper wol merino yang nyaman mengatur suhu kaki secara alami dan tahan bau tanpa zat kimia sintetis.</p>',
                'material_info' => 'Upper wol merino Selandia Baru bersertifikasi ZQ. Sol SweetFoam® berbasis tebu. Tali sepatu dari poliester botol daur ulang.',
                'sustainability_note' => 'Jejak karbon: 5.42 kg CO2e. 100% material alami terbarukan.',
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
                'name' => "Sepatu Pria Canvas Cruiser Slip On",
                'slug' => 'mens-canvas-cruiser-slip-on',
                'short_description' => 'Slip-on klasik yang mudah dipakai dari kanvas katun organik kuat dan tahan lama.',
                'description' => '<p>Sepatu slip-on harian yang sangat fleksibel. Mudah dilepas dan dipakai, dilengkapi bantalan penyangga lengkung kaki serta kanvas sejuk yang semakin lembut setiap kali dipakai.</p>',
                'material_info' => 'Upper 100% kanvas katun organik, sol luar karet alam, insole EVA daur ulang.',
                'sustainability_note' => 'Jejak karbon: 4.10 kg CO2e.',
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
                'name' => "Sepatu Pria Runner NZ Slip On",
                'slug' => 'mens-runner-nz-slip-on',
                'short_description' => 'Sneaker slip-on rajut bertekstur memadukan kenyamanan kaus kaki dengan bantalan harian.',
                'description' => '<p>Langsung pakai dan melangkah. Runner NZ Slip On membalut kaki Anda dengan kerah rajut elastis yang pas dan sol tebu alami SweetFoam® untuk kenyamanan jalan tanpa tekanan.</p>',
                'material_info' => 'Upper rajut ribbed berteknologi tinggi dari serat eucalyptus dan nilon daur ulang.',
                'sustainability_note' => 'Jejak karbon: 4.60 kg CO2e.',
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
                'name' => "Sepatu Pria Wool Runner-up Mizzle",
                'slug' => 'mens-wool-runner-up-mizzle',
                'short_description' => 'Sneaker high-top tahan percikan air dari bahan wol merino ZQ pelindung genangan.',
                'description' => '<p>Jaga kaki tetap kering dan hangat dalam kondisi cuaca apapun. Dilengkapi teknologi bio-based Puddle Guard® penangkal air dan sol tapak karet alam anti-selip di segala medan.</p>',
                'material_info' => 'Upper wol merino ZQ dengan perlakuan ECO Puddle Guard®. Outsole karet alam bergerigi untuk segala cuaca.',
                'sustainability_note' => 'Jejak karbon: 6.80 kg CO2e.',
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
                'name' => "Sepatu Wanita Tree Lounger",
                'slug' => 'womens-tree-lounger',
                'short_description' => 'Sepatu slip-on serat eucalyptus yang sejuk, praktis tanpa kaus kaki, dan empuk.',
                'description' => '<p>Sepatu flat slip-on kasual terbaik untuk bepergian dan santai akhir pekan. Serat pohon eucalyptus yang selembut sutra menjaga kaki tetap sejuk dan segar sepanjang hari.</p>',
                'material_info' => 'Upper serat eucalyptus bersertifikasi FSC, sol tebu SweetFoam®, insole berlapisan wol merino lembut.',
                'sustainability_note' => 'Jejak karbon: 3.90 kg CO2e.',
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
                'name' => "Sepatu Lari Wanita Tree Dasher 2",
                'slug' => 'womens-tree-dasher-2',
                'short_description' => 'Sepatu lari performa tinggi dirancang dengan serat alami bernapas yang sejuk.',
                'description' => '<p>Dibuat untuk lari pagi, olahraga 5K, dan rutinitas aktif di perkotaan. Dilengkapi upper anatomis tanpa jahitan, bantalan tumit empuk, dan midsole SweetFoam® alami untuk daya pantul maksimal.</p>',
                'material_info' => 'Upper rajut serat pohon eucalyptus, midsole SweetFoam® berbahan tebu, outsole karet alam FSC.',
                'sustainability_note' => 'Jejak karbon: 6.90 kg CO2e.',
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
                'name' => "Sepatu Wanita Tree Runner Go",
                'slug' => 'womens-tree-runner-go',
                'short_description' => 'Sepatu jalan santai ringan harian dengan serat pohon eucalyptus yang sejuk bernapas.',
                'description' => '<p>Sepatu andalan untuk segala aktivitas. Empuk, selembut awan, dapat dicuci dengan mesin, dan dibuat secara berkelanjutan untuk kenyamanan kerja hingga akhir pekan.</p>',
                'material_info' => 'Serat eucalyptus FSC, midsole tebu alami SweetFoam®.',
                'sustainability_note' => 'Jejak karbon: 4.40 kg CO2e.',
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
                'name' => "Sepatu Wanita Canvas Cruiser Slip On",
                'slug' => 'womens-canvas-cruiser-slip-on',
                'short_description' => 'Slip-on kanvas katun organik bersih dan minimalis dengan bantalan penopang kaki.',
                'description' => '<p>Siluet slip-on klasik yang tampil modern dengan 100% kanvas katun organik, nyaman dipakai langsung tanpa masa penyesuaian. Kasual, bersih, dan membal.</p>',
                'material_info' => 'Upper 100% kanvas katun organik, insole SweetFoam®.',
                'sustainability_note' => 'Jejak karbon: 3.85 kg CO2e.',
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
                'name' => "Sepatu Wanita Cruiser Slip On",
                'slug' => 'womens-cruiser-slip-on',
                'short_description' => 'Sneaker slip-on rajut tanpa jahitan untuk kemudahan pemakaian dan kenyamanan ringan.',
                'description' => '<p>Siluet slip-on abadi dalam balutan warna putih Blizzard. Ringan, lentur, dan siap menemani langkah Anda ke mana pun hari membawa.</p>',
                'material_info' => 'Upper rajut engineered dengan sol tebu SweetFoam®.',
                'sustainability_note' => 'Jejak karbon: 4.15 kg CO2e.',
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
                'name' => "Sepatu Wanita Runner NZ Slip On",
                'slug' => 'womens-runner-nz-slip-on',
                'short_description' => 'Sneaker slip-on rajut bergaris dengan peredam kejut benturan premium.',
                'description' => '<p>Rasakan sensasi berjalan di atas awan dengan Runner NZ Slip On. Kerah rajut elastis yang fleksibel pas seperti kulit kedua sementara SweetFoam® meredam getaran langkah dengan mudah.</p>',
                'material_info' => 'Upper rajut bergaris dari serat pohon eucalyptus FSC.',
                'sustainability_note' => 'Jejak karbon: 4.50 kg CO2e.',
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
                'name' => "Kaos Pria Sea Tee Classic",
                'slug' => 'mens-sea-tee-classic',
                'short_description' => 'Kaos harian lembut dan sejuk dari perpaduan katun organik dan serat alami cangkang kepiting.',
                'description' => '<p>Kenalkan kaos alami paling inovatif di dunia. Dipadukan dengan Kitosan (serat terbarukan dari cangkang kepiting) dan katun Pima Peru organik agar tetap segar lebih lama secara alami.</p>',
                'material_info' => '65% Katun Pima Peru Organik, 35% SeaCell™ Lyocell dengan Kitosan.',
                'sustainability_note' => 'Jejak karbon: 6.30 kg CO2e. 100% alami dan bebas mikroplastik.',
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
                'name' => "Kaos Kaki Trino™ Tubers Crew",
                'slug' => 'trino-tubers-crew-socks',
                'short_description' => 'Kaos kaki crew harian yang bernapas dari perpaduan serat pohon eucalyptus dan wol merino.',
                'description' => '<p>Kaos kaki ternyaman di dunia. Dirancang dengan benang Trino™ eksklusif kami yang memadukan serat pohon eucalyptus sejuk dan wol merino ZQ lembut.</p>',
                'material_info' => '50% TENCEL™ Lyocell, 35% Wol Merino ZQ, 12% Nilon Daur Ulang, 3% Spandex.',
                'sustainability_note' => 'Jejak karbon: 1.20 kg CO2e.',
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
                'name' => "Tas fifa Anytime Tote Bag",
                'slug' => 'fifa-anytime-tote-bag',
                'short_description' => 'Tote bag kanvas katun organik kokoh untuk belanja, pantai, dan perjalanan harian.',
                'description' => '<p>Kapasitas lapang, tali bahu diperkuat, dan kantong internal untuk barang esensial Anda. Dibuat dari 100% kanvas katun organik tebal untuk menggantikan plastik sekali pakai selamanya.</p>',
                'material_info' => '100% Kanvas Katun Organik Tebal (14oz).',
                'sustainability_note' => 'Jejak karbon: 2.10 kg CO2e.',
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
