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
                'meta_title' => 'Tentang Kami — Kisah & Filosofi Sepatu fifa',
                'meta_description' => 'Kami memulai perjalanan dengan pertanyaan sederhana: mengapa industri sepatu begitu bergantung pada bahan sintetis dan plastik?',
                'content' => '<p>fifa lahir dari visi untuk menciptakan sepatu ternyaman di dunia menggunakan material alami terbarukan. Didirikan untuk menghadirkan kenyamanan luar biasa dan gaya hidup modern.</p><h2>Kembali ke Alam</h2><p>Kami mengganti bahan sintetis berbasis minyak bumi dengan <strong>wol merino ZQ premium</strong>, serat pohon eucalyptus bersertifikasi FSC, dan sol busa SweetFoam™ dari tebu alami.</p><p>Hasilnya adalah sepatu yang luar biasa empuk, sejuk, dapat dicuci dengan mesin, dan memiliki jejak karbon minimal.</p>',
            ],
            [
                'title' => 'Keberlanjutan & Jejak Karbon',
                'slug' => 'sustainability',
                'meta_title' => 'Komitmen Keberlanjutan & Nol Emisi Karbon — fifa',
                'meta_description' => 'Pelajari bagaimana kami mengukur setiap kilogram emisi karbon pada produk kami dan berusaha menurunkannya hingga mendekati nol.',
                'content' => '<p>Di fifa, kami mencantumkan angka jejak karbon (carbon footprint) pada setiap pasang sepatu yang kami buat — sama seperti label nutrisi pada makanan.</p><h3>Tiga Pilar Keberlanjutan Kami:</h3><ul><li><strong>Material Alami Terbarukan:</strong> Wol Merino, Pohon Eucalyptus, dan Tebu Manis.</li><li><strong>Energi Bersih:</strong> 100% listrik ramah lingkungan di fasilitas produksi mitra kami.</li><li><strong>Desain Sirkular:</strong> Material yang mudah didaur ulang dan tahan lama.</li></ul>',
            ],
            [
                'title' => 'Tanya Jawab & Bantuan (FAQ)',
                'slug' => 'faq',
                'meta_title' => 'Pusat Bantuan & FAQ — fifa Indonesia',
                'meta_description' => 'Pertanyaan yang sering diajukan mengenai cara pemesanan, panduan fitting ukuran, perawatan sepatu, dan kebijakan garansi.',
                'content' => '<h3>Bagaimana cara mencuci sepatu fifa?</h3><p>Keluarkan insole dan tali sepatu, masukkan ke dalam laundry bag, dan cuci dengan mesin cuci menggunakan siklus lembut (delicate) dengan air dingin. Keringkan secara alami (jangan menggunakan mesin pengering panas).</p><h3>Berapa lama pengiriman pesanan?</h3><p>Pesanan dikirim langsung melalui integrasi logistik <strong>Biteship</strong> dengan opsi Instant (2-3 jam), Regular (1-3 hari), atau Express ke seluruh wilayah Indonesia.</p><h3>Apakah ada jaminan garansi penukaran ukuran?</h3><p>Ya! Kami memberikan garansi penukaran ukuran dalam 30 hari jika ukuran sepatu belum pas di kaki Anda.</p>',
            ],
            [
                'title' => 'Pengiriman & Pengembalian',
                'slug' => 'shipping-returns',
                'meta_title' => 'Kebijakan Pengiriman & Pengembalian — fifa Indonesia',
                'meta_description' => 'Informasi kurir ekspedisi, ongkos kirim, dan prosedur return 30 hari.',
                'content' => '<p>Kami bekerjasama dengan kurir terpercaya di Indonesia (JNE, SiCepat, J&T, GoSend, Grab) melalui platform logistik <strong>Biteship</strong>.</p><p>Gratis ongkir berlaku untuk seluruh transaksi dengan total belanja minimum Rp 500.000.</p>',
            ],
            [
                'title' => 'Panduan Ukuran (Size Guide)',
                'slug' => 'size-guide',
                'meta_title' => 'Panduan Ukuran Sepatu Pria & Wanita — fifa Indonesia',
                'meta_description' => 'Tabel konversi ukuran EU, US, UK, dan panjang sentimeter (cm) kaki Anda.',
                'content' => '<p>Sepatu fifa dirancang pas mengikuti lekuk alami kaki. Jika Anda biasanya mengenakan ukuran setengah (misal 41.5), kami menyarankan untuk memilih satu ukuran di atasnya (misal 42) untuk kenyamanan optimal.</p>',
            ],
            [
                'title' => 'Jejak Karbon (Carbon Footprint)',
                'slug' => 'carbon-footprint',
                'meta_title' => 'Transparansi Jejak Karbon Produk fifa',
                'meta_description' => 'Mengapa kami menghitung setiap gram CO2e pada setiap produk yang kami produksi.',
                'content' => '<p>Rata-rata sepasang sepatu standar industri menghasilkan 14 kg CO2e. Sepatu fifa Wool Runner memiliki jejak karbon hanya sekitar 7.15 kg CO2e — dan target kami adalah mencapai nol emisi mutlak.</p>',
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
                'title' => 'Mengapa Wol Merino Menjadi Masa Depan Sepatu Modern',
                'slug' => 'mengapa-wol-merino-menjadi-masa-depan-sepatu-modern',
                'excerpt' => 'Material wol merino asal Selandia Baru bukan hanya hangat saat dingin, tetapi sejuk dan bernapas saat cuaca tropis panas.',
                'cover_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&q=80',
                'content' => '<p>Banyak orang mengira bahan wol hanya cocok untuk pakaian musim dingin. Namun, serat wol merino memiliki struktur mikroskopis unik yang mengatur suhu dan menyerap kelembapan secara alami.</p><h2>Serat yang Mengatur Suhu Tubuh</h2><p>Saat kaki Anda mulai berkeringat di iklim tropis, serat wol merino melepaskan uap panas ke udara terbuka, menjaga kaki Anda tetap kering dan bebas bau sepanjang hari tanpa kaus kaki.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Dari Pohon Eucalyptus Menjadi Sepatu Lari yang Sejuk',
                'slug' => 'dari-pohon-eucalyptus-menjadi-sepatu-lari-yang-sejuk',
                'excerpt' => 'Inovasi Tree Fiber fifa menggunakan serat kayu eucalyptus yang membutuhkan 95% lebih sedikit air daripada katun tradisional.',
                'cover_image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1200&q=80',
                'content' => '<p>Koleksi Tree Runners dibuat dari serat lyocell pohon eucalyptus bersertifikasi FSC. Karakter serat ini sangat halus bak sutra dan menghasilkan sirkulasi udara maksimal untuk aktivitas aktif harian Anda.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Panduan Merawat Sepatu fifa Agar Awet Bertahun-tahun',
                'slug' => 'panduan-merawat-sepatu-fifa-agar-awet',
                'excerpt' => 'Langkah mudah mencuci sepatu berbahan wol dan pohon menggunakan mesin cuci di rumah tanpa merusak serat alaminya.',
                'cover_image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=1200&q=80',
                'content' => '<p>Kabar baiknya: semua sepatu fifa bisa dicuci dengan mesin cuci! Ikuti panduan praktis 3 langkah kami untuk menjaga kebersihan sepatu kesayangan Anda.</p>',
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
                'title' => 'Sepatu Paling Nyaman di Dunia dari Bahan Alami',
                'subtitle' => 'Dibuat dari wol merino alami dan serat pohon eucalyptus terbarukan.',
                'cta_text' => 'Sepatu Pria',
                'cta_link' => '/men',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=80',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'page' => 'home',
                'title' => 'Langkah Nyaman Terinspirasi Alam',
                'subtitle' => 'Ringan, empuk, dan ramah bumi untuk setiap langkah Anda.',
                'cta_text' => 'Sepatu Wanita',
                'cta_link' => '/women',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1600&q=80',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'page' => 'men',
                'title' => 'Koleksi Sepatu Alami Pria',
                'subtitle' => 'Koleksi sepatu pria fifa dengan kenyamanan tak tertandingi.',
                'cta_text' => 'Lihat Semua Pria',
                'cta_link' => '/men',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=80',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'page' => 'women',
                'title' => 'Koleksi Sepatu Alami Wanita',
                'subtitle' => 'Koleksi sepatu wanita fifa dengan material super lembut.',
                'cta_text' => 'Lihat Semua Wanita',
                'cta_link' => '/women',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1600&q=80',
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
                'code' => 'MERINO20',
                'type' => 'percent',
                'value' => 20,
                'min_purchase' => 1000000,
                'max_discount' => 250000,
                'starts_at' => now()->subDays(1),
                'expires_at' => now()->addMonths(1),
                'usage_limit' => 200,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
