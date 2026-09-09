<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Coupon;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\StoreLocation;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Static Pages
        $pages = [
            [
                'title' => 'Kisah fifa (Our Story)',
                'slug' => 'our-story',
                'meta_title' => 'Tentang Kami — Kisah & Filosofi Tas fifa',
                'meta_description' => 'Kami memulai perjalanan dengan pertanyaan sederhana: mengapa industri tas begitu bergantung pada plastik sintetis murni dan merusak alam?',
                'content' => '<p><strong>fifa</strong> lahir dari visi untuk menciptakan tas dan perlengkapan harian terbaik di dunia menggunakan material alami terbarukan dan poliester daur ulang bersertifikasi.</p><h2>Harmoni Desain & Alam</h2><p>Kami mengganti bahan sintetis konvensional dengan <strong>kanvas katun organik GOTS</strong>, kulit nabati bio-leather (AppleSkin™ & Cactus Leather), serta anyaman tali dari botol plastik daur ulang.</p><p>Hasilnya adalah rangkaian tas, ransel, dan tote bag yang tangguh, elegan, tahan cuaca, dan memiliki jejak karbon minimal.</p>',
            ],
            [
                'title' => 'Keberlanjutan & Jejak Karbon',
                'slug' => 'sustainability',
                'meta_title' => 'Komitmen Keberlanjutan & Nol Emisi Karbon — fifa Bags',
                'meta_description' => 'Pelajari bagaimana kami mengukur setiap kilogram emisi karbon pada setiap tas kami dan berusaha menurunkannya hingga nol mutlak.',
                'content' => '<p>Di fifa, kami mencantumkan angka jejak karbon (carbon footprint) pada setiap tas dan aksesori yang kami produksi — sama transparan seperti label nutrisi pada makanan.</p><h3>Tiga Pilar Keberlanjutan Kami:</h3><ul><li><strong>Material Berkelanjutan:</strong> Katun Kanvas Organik, Bio-based Vegan Leather, dan Daur Ulang Plastik Laut (RPET).</li><li><strong>Etika & Keadilan:</strong> 100% fasilitas produksi tersertifikasi standar kerja yang adil dan aman.</li><li><strong>Desain Sirkular & Tahan Lama:</strong> Kami merancang tas yang awet bertahun-tahun dengan garansi servis reparasi.</li></ul>',
            ],
            [
                'title' => 'Tanya Jawab & Bantuan (FAQ)',
                'slug' => 'faq',
                'meta_title' => 'Pusat Bantuan & FAQ — fifa Indonesia',
                'meta_description' => 'Pertanyaan yang sering diajukan mengenai cara pemesanan, panduan kapasitas tas, perawatan material, dan kebijakan garansi.',
                'content' => '<h3>Bagaimana cara membersihkan dan merawat tas fifa?</h3><p>Untuk tas berbahan kanvas dan kulit nabati, cukup bersihkan noda menggunakan kain lembap lembut dengan sabun berbusa ringan. Hindari merendam tas ke dalam mesin cuci agar struktur bentuk dan lapisan pelindung tahan air tetap terjaga optimal.</p><h3>Berapa lama pengiriman pesanan?</h3><p>Pesanan dikirim langsung melalui integrasi logistik <strong>Biteship</strong> dengan opsi Instant (2-3 jam), Regular (1-3 hari), atau Express ke seluruh wilayah Indonesia.</p><h3>Apakah ada jaminan garansi dan pengembalian 30 hari?</h3><p>Ya! Kami memberikan garansi pengembalian dan penukaran dalam 30 hari jika produk tidak sesuai dengan ekspektasi Anda.</p>',
            ],
            [
                'title' => 'Pengiriman & Pengembalian',
                'slug' => 'shipping-returns',
                'meta_title' => 'Kebijakan Pengiriman & Pengembalian — fifa Indonesia',
                'meta_description' => 'Informasi kurir ekspedisi, ongkos kirim, dan prosedur return 30 hari.',
                'content' => '<p>Kami bekerjasama dengan kurir terpercaya di Indonesia (JNE, SiCepat, J&T, GoSend, Grab) melalui platform logistik <strong>Biteship</strong>.</p><p>Gratis ongkir berlaku untuk seluruh transaksi dengan total belanja minimum Rp 500.000.</p>',
            ],
            [
                'title' => 'Panduan Kapasitas & Ukuran (Bag Size Guide)',
                'slug' => 'size-guide',
                'meta_title' => 'Panduan Kapasitas Tas Pria & Wanita — fifa Indonesia',
                'meta_description' => 'Panduan memilih ukuran tas, volume liter (L), dan kompatibilitas kompartemen laptop.',
                'content' => '<p>Setiap tas fifa dirancang dengan kompartemen fungsional:</p><ul><li><strong>Compact / Sling (4L - 8L):</strong> Muat untuk smartphone, dompet, kunci, passport, dan iPad mini.</li><li><strong>Daypack / Medium (12L - 18L):</strong> Muat untuk laptop 13-14 inci, charger, buku, botol minum, dan jaket tipis.</li><li><strong>Commuter / Large (20L - 25L):</strong> Muat untuk laptop hingga 16 inci, pakaian ganti, berkas kerja, dan perlengkapan harian penuh.</li><li><strong>Travel Duffle (35L - 45L):</strong> Kapasitas bepergian akhir pekan 2-4 hari dengan kompartemen sepatu terpisah.</li></ul>',
            ],
            [
                'title' => 'Perawatan Tas (Bag Care)',
                'slug' => 'shoe-care',
                'meta_title' => 'Panduan Perawatan Tas & Aksesori — fifa Indonesia',
                'meta_description' => 'Cara merawat tas kanvas organik dan kulit nabati agar awet bertahun-tahun.',
                'content' => '<p>Tas fifa dirancang untuk daya tahan maksimal. Ikuti petunjuk sederhana ini:</p><ol><li>Bersihkan debu harian dengan sikat lembut berbulu halus.</li><li>Untuk noda membandel, seka dengan kain microfiber basah bersabun lembut.</li><li>Keringkan di tempat teduh dengan sirkulasi udara baik (hindari paparan sinar matahari terik langsung berkepanjangan).</li></ol>',
            ],
            [
                'title' => 'Jejak Karbon (Carbon Footprint)',
                'slug' => 'carbon-footprint',
                'meta_title' => 'Transparansi Jejak Karbon Produk fifa',
                'meta_description' => 'Mengapa kami menghitung setiap gram CO2e pada setiap produk tas yang kami produksi.',
                'content' => '<p>Rata-rata sebuah tas konvensional di pasaran menghasilkan jejak karbon 8-12 kg CO2e. Tas fifa diproduksi dengan jejak karbon rata-rata hanya 2.5 kg CO2e — dan target kami adalah mencapai nol emisi mutlak melalui inovasi rantai pasok hijau.</p>',
            ],
            [
                'title' => 'Hubungi Kami',
                'slug' => 'contact',
                'meta_title' => 'Hubungi Tim Layanan Pelanggan fifa',
                'meta_description' => 'Layanan bantuan customer service via WhatsApp dan Email.',
                'content' => '<p>Tim Customer Support kami siap membantu Anda setiap hari kerja (Senin - Jumat, 09:00 - 18:00 WIB).</p><p>Email: <strong>support@fifa.co.id</strong><br>WhatsApp: <strong>0812-3456-7890</strong></p>',
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Store Locations
        $stores = [
            [
                'name' => 'fifa Flagship Senayan City',
                'address' => 'Senayan City Mall Lt. 1 Unit 1-28, Jl. Asia Afrika Lot 19, Gelora, Tanah Abang',
                'city' => 'Jakarta Pusat',
                'phone' => '(021) 7278-1234',
                'opening_hours' => 'Setiap hari 10:00 - 22:00 WIB',
                'latitude' => -6.2271230,
                'longitude' => 106.7974560,
                'is_active' => true,
            ],
            [
                'name' => 'fifa Grand Indonesia',
                'address' => 'Grand Indonesia West Mall Lt. 2, Jl. M.H. Thamrin No. 1, Menteng',
                'city' => 'Jakarta Pusat',
                'phone' => '(021) 2358-5678',
                'opening_hours' => 'Setiap hari 10:00 - 22:00 WIB',
                'latitude' => -6.1951230,
                'longitude' => 106.8214560,
                'is_active' => true,
            ],
            [
                'name' => 'fifa Paris Van Java Bandung',
                'address' => 'Paris Van Java Mall Resort Level, Jl. Sukajadi No. 131-139, Cipedes',
                'city' => 'Kota Bandung',
                'phone' => '(022) 8206-3456',
                'opening_hours' => 'Setiap hari 10:00 - 22:00 WIB',
                'latitude' => -6.8891230,
                'longitude' => 107.5964560,
                'is_active' => true,
            ],
            [
                'name' => 'fifa Tunjungan Plaza Surabaya',
                'address' => 'Tunjungan Plaza 6 Lt. 3, Jl. Embong Malang No. 21-31, Kedungdoro',
                'city' => 'Kota Surabaya',
                'phone' => '(031) 5345-6789',
                'opening_hours' => 'Setiap hari 10:00 - 22:00 WIB',
                'latitude' => -7.2621230,
                'longitude' => 112.7384560,
                'is_active' => true,
            ],
            [
                'name' => 'fifa Beachwalk Kuta Bali',
                'address' => 'Beachwalk Shopping Center Lt. 1, Jl. Pantai Kuta, Badung',
                'city' => 'Bali',
                'phone' => '(0361) 8464-1234',
                'opening_hours' => 'Setiap hari 10:00 - 23:00 WITA',
                'latitude' => -8.7181230,
                'longitude' => 115.1694560,
                'is_active' => true,
            ],
        ];

        foreach ($stores as $s) {
            StoreLocation::updateOrCreate(['name' => $s['name']], $s);
        }

        // 3. Blog Posts
        $posts = [
            [
                'title' => 'Inovasi Kulit Nabati: Masa Depan Tas Modern yang Bebas Emisi',
                'slug' => 'inovasi-kulit-nabati-masa-depan-tas-modern',
                'excerpt' => 'Mengapa material kulit nabati dari limbah apel dan kaktus menjadi standar baru industri mode mewah ramah lingkungan.',
                'cover_image' => '/images/home/woman-swing.jpg',
                'content' => '<p>Banyak konsumen modern menginginkan keanggunan tas kulit tanpa beban kerusakan lingkungan dan eksploitasi hewani. Melalui teknologi bio-materials terkini, kulit nabati menghadirkan tekstur mewah yang lentur, tahan gores, dan tahan air alami.</p><h2>Kekuatan Serat Alami Berkelanjutan</h2><p>Material AppleSkin™ dan Cactus Leather yang kami gunakan menghemat hingga 80% penggunaan air dan menghasilkan 75% lebih sedikit emisi karbon dibanding kulit hewan konvensional.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Tips Memilih Ransel Kerja Ergonomis untuk Mobilitas Urban',
                'slug' => 'tips-memilih-ransel-kerja-ergonomis',
                'excerpt' => 'Panduan memilih kapasitas ransel, proteksi kompartemen laptop, dan distribusi bobot yang nyaman di pundak.',
                'cover_image' => '/images/home/travel-slides.jpg',
                'content' => '<p>Ransel kerja bukan sekadar wadah laptop, melainkan partner penunjang postur tubuh Anda setiap hari. Pilihlah ransel dengan bantalan tali busa terdistribusi rata dan ritsleting weatherproof untuk perlindungan maksimal perangkat elektronik Anda.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Panduan Merawat Tas Kanvas Organik Agar Awet Bertahun-tahun',
                'slug' => 'panduan-merawat-tas-kanvas-organik-agar-awet',
                'excerpt' => 'Langkah mudah membersihkan noda pada tas kanvas dan menjaga ketahanan serat alaminya.',
                'cover_image' => '/images/home/summer-rocks.jpg',
                'content' => '<p>Karakteristik kanvas katun organik semakin menawan seiring waktu pemakaian (patina alami). Cukup bersihkan noda dengan kain lembap lembut dan simpan di tempat berventilasi baik agar tas kesayangan Anda tetap prima.</p>',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        // 4. Hero Slides
        $slides = [
            [
                'page' => 'home',
                'title' => 'Koleksi Tas & Ransel Ergonomis dari Material Alami',
                'subtitle' => 'Dibuat dari kanvas katun organik murni dan kulit sintetis nabati premium.',
                'cta_text' => 'Tas Pria',
                'cta_link' => '/men',
                'image' => '/images/home/hero-dasher.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'page' => 'home',
                'title' => 'Desain Minimalis Elegan untuk Setiap Perjalanan',
                'subtitle' => 'Ringan, kuat, dan ramah bumi untuk menyempurnakan gaya harian Anda.',
                'cta_text' => 'Tas Wanita',
                'cta_link' => '/women',
                'image' => '/images/home/woman-swing.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'page' => 'men',
                'title' => 'Koleksi Tas & Ransel Pria',
                'subtitle' => 'Ransel commuter, tas kerja briefcase, dan sling bag tangguh multifungsi.',
                'cta_text' => 'Lihat Semua Pria',
                'cta_link' => '/men',
                'image' => '/images/home/travel-slides.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'page' => 'women',
                'title' => 'Koleksi Tas Wanita Elegan',
                'subtitle' => 'Tote bag, shoulder bag, dan crossbody bernuansa minimalis mewah.',
                'cta_text' => 'Lihat Semua Wanita',
                'cta_link' => '/women',
                'image' => '/images/home/woman-swing.jpg',
                'order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate([
                'page' => $slide['page'],
                'order' => $slide['order'],
            ], $slide);
        }

        // 5. Coupons
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
                'min_purchase' => 300000,
                'max_discount' => 100000,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addMonths(6),
                'usage_limit' => 1000,
                'used_count' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'FIFA50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_purchase' => 500000,
                'max_discount' => null,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(3),
                'usage_limit' => 500,
                'used_count' => 12,
                'is_active' => true,
            ],
            [
                'code' => 'BAGLOVER20',
                'type' => 'percent',
                'value' => 20,
                'min_purchase' => 1000000,
                'max_discount' => 250000,
                'starts_at' => now()->subDays(1),
                'expires_at' => now()->addMonths(1),
                'usage_limit' => 200,
                'used_count' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
