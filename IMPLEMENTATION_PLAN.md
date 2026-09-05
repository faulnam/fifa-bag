# Rencana Implementasi "Allbirds Clone" — E-Commerce Sepatu & Apparel
### Laravel + MySQL + Tailwind CSS (Standalone CLI, Tanpa Vite/NPM Run Dev) + Biteship + Payment Gateway

> Blueprint teknis lengkap, belum berisi kode. Referensi UI/UX & fitur: **https://www.allbirds.com/**. Referensi warna/tipografi/komponen: file style guide Allbirds (md) yang sudah kamu berikan. Semua konten harus bisa di-CRUD oleh admin, ada 2 role (Admin & Customer), pembayaran online, dan pengiriman via **Biteship** (cek ongkir + request pickup + tracking).

---

## 1. Ringkasan Proyek

| Item | Detail |
|---|---|
| Tipe website | E-commerce sepatu, apparel, aksesoris (clone UI Allbirds) |
| Referensi UI | https://www.allbirds.com/ (Shopify) — direplikasi tampilan & layoutnya, bukan kode sumbernya |
| Referensi desain | Style guide Allbirds (tokens warna, tipografi, komponen — lihat Bagian 9) |
| Backend | Laravel 11 (PHP 8.2+) |
| Database | MySQL 8 |
| CSS | Tailwind CSS **standalone CLI binary** (tanpa Node/NPM/Vite/`npm run dev`) |
| JS | Vanilla JS + Alpine.js (CDN) untuk interaksi ringan; tanpa build step |
| Role | `customer` (belanja) & `admin`/`super_admin` (kelola toko) — 1 tabel `users` + kolom `role`, dipisah lewat middleware & guard |
| Payment gateway | **Midtrans Snap** (utama, umum dipakai bareng Biteship di Indonesia) — arsitektur dibuat multi-gateway supaya **Xendit** bisa ditambah belakangan tanpa ubah struktur |
| Pengiriman | **Biteship API** — cek ongkir realtime, buat order pengiriman, request pickup kurir, tracking status via webhook |
| Target hosting | VPS/shared hosting yang support PHP-FPM + MySQL (Node.js **tidak dibutuhkan** di server produksi) |

**Prinsip "tanpa Vite" sama seperti proyek sebelumnya:** Tailwind dikompilasi sekali (atau `--watch` saat dev) pakai binary CLI standalone, hasilnya file statis `public/css/app.css`, di-link biasa. Tidak ada `package.json` wajib di server produksi.

**Penting soal "100% sama persis":** Kita replikasi **tampilan, layout, alur, dan fitur** publik Allbirds (bukan menyalin source code/asset berhak cipta mereka). Font asli Allbirds (Self Modern & Geograph) adalah font berlisensi — kita pakai font pengganti visual-mirip yang legal dipakai gratis (Playfair Display sebagai pengganti Self Modern, Inter sebagai pengganti Geograph), sesuai catatan di style guide kamu. Foto produk juga wajib pakai foto produk kamu sendiri (upload lewat admin), bukan hotlink foto Allbirds.

---

## 2. Hasil Riset — Struktur & Fitur Allbirds.com

### 2.1 Peta Navigasi (Header)

```
[Announcement bar hitam, teks berjalan/rotasi — mis. "Free shipping over Rp X" / promo]
Logo (kiri) — Nav tengah: MEN | WOMEN | SALE — Kanan: Search, Account, Wishlist, Cart (badge qty)

Mega menu (hover/klik "MEN" / "WOMEN"):
 ├─ New Arrivals
 ├─ Shoes (Runners, Dashers, Loungers, Loafers/Flats, Slip-ons, Wool Shoes)
 ├─ Apparel (Tops, Bottoms, Outerwear)
 ├─ Accessories (Socks, Bags)
 ├─ Sale
 └─ Kartu kategori bergambar (kolom kanan mega menu) — mengarah ke collection tertentu
```
→ Dasar tabel `categories` (self-referencing, punya `gender` scope Men/Women/Unisex) + `collections` (kurasi lintas kategori: New Arrivals, Sale, Best Sellers, kolaborasi/limited edition).

### 2.2 Breakdown Halaman & Fitur

**A. Homepage (`/`)**
1. Announcement bar promo (berputar/rotasi teks, dismissable)
2. Hero editorial full-bleed (banner besar, headline, 1–2 tombol pill "Shop Men"/"Shop Women")
3. Grid kategori berwarna (4 kolom, tiap kartu = 1 warna solid + foto produk + label pill) → tautan ke koleksi
4. Carousel "New Arrivals" / "Best Sellers" (produk: foto, nama, harga, badge "New Color"/promo)
5. Split section lifestyle + produk (foto editorial kiri + panel warna produk kanan)
6. Section sustainability/values singkat (ikon minimal + teks, tanpa gradasi)
7. Ulasan/rating ringkas (jika ada widget review)
8. Newsletter signup (input email + submit, pill button)
9. Footer global (lihat 2.4)

**B. Collection / Kategori (`/collections/{slug}`, `/men`, `/women`, `/sale`)**
1. Judul koleksi + deskripsi singkat
2. Sidebar/drawer **Filter** (kategori, warna, ukuran, harga, material) — mobile: drawer slide dari bawah/samping
3. **Sort** (Terbaru, Harga naik/turun, Terlaris)
4. Grid produk (kartu: foto utama, foto hover kedua/swap, nama, harga, badge diskon/new, quick-add ke cart)
5. Pagination / infinite scroll
6. Empty state saat filter tidak ada hasil

**C. Product Detail Page — PDP (`/products/{slug}`)**
1. Galeri foto (thumbnail + gambar besar, zoom on hover)
2. Nama produk, harga (+ harga coret jika diskon), rating ringkas
3. Pemilih **warna** (swatch bulat) — ganti galeri foto sesuai warna dipilih
4. Pemilih **ukuran** (grid tombol ukuran, disable jika stok habis) + link "Size Guide" (modal/drawer)
5. Tombol "Add to Bag" (pill hitam, micro-interaction loading→sukses) + tombol wishlist (ikon hati, toggle)
6. Info carbon footprint / sustainability produk (khas Allbirds)
7. Accordion: Deskripsi & Material, Cara Perawatan, Pengiriman & Pengembalian
8. Estimasi ongkir cepat (input kode pos → panggil Biteship rate check tanpa harus masuk checkout)
9. Section "You May Also Like" (produk terkait/koleksi sama)
10. Ulasan produk (list + form beri ulasan, hanya user yang login & sudah membeli — opsional gate)

**D. Cart (drawer slide-in dari kanan, + halaman `/cart` penuh)**
1. List item (foto, nama, varian warna/ukuran, qty stepper, subtotal, hapus)
2. Ringkasan: subtotal, estimasi diskon jika ada kupon, tombol "Checkout"
3. Free shipping progress bar (jika ada threshold di `site_settings`)
4. Rekomendasi produk tambahan (opsional)

**E. Checkout (`/checkout`, multi-step)**
1. Step 1 — Info kontak & alamat pengiriman (pilih alamat tersimpan / tambah baru, autocomplete area via Biteship)
2. Step 2 — Pilihan kurir & layanan (hasil **cek ongkir realtime Biteship**: nama kurir, layanan, estimasi hari, harga)
3. Step 3 — Metode pembayaran (Midtrans Snap popup — transfer bank/VA, kartu, e-wallet, QRIS — semua ditangani Snap)
4. Ringkasan pesanan (sticky di sisi kanan desktop)
5. Kode promo/kupon
6. Tombol "Place Order" → buat `orders` + redirect/inject Snap → setelah bayar sukses → halaman **Order Confirmation** (`/order/{order_number}/success`)

**F. Akun Customer (`/account/*`, butuh login)**
1. Dashboard ringkas (pesanan terakhir, alamat default)
2. Riwayat Pesanan (list + detail status + tracking pengiriman real-time dari Biteship)
3. Alamat Tersimpan (CRUD alamat)
4. Profil (nama, email, telepon, ganti password)
5. Wishlist
6. (Opsional) Ulasan yang pernah ditulis

**G. Auth (`/login`, `/register`, `/forgot-password`)**
- Form sederhana, style Allbirds (input inset-border, pill button)
- Guest checkout tetap didukung (opsional buat akun setelah checkout)

**H. Halaman Statis/CMS**
- `/pages/{slug}` — About Us, Sustainability, FAQ, Size Guide, Shipping & Returns, Accessibility, Careers (semua **editable dari admin**, bukan hardcode)
- `/stores` — daftar toko fisik (peta + alamat + jam buka)
- `/journal` (blog) + `/journal/{slug}` — index & detail artikel

**I. Search (`/search?q=`)**
- Search bar di header (modal/overlay), hasil produk realtime (Alpine + fetch, debounce)

### 2.3 Footer Global
- Logo
- Grid multi-kolom: Shop, Help/Support, Company/About, Legal
- Newsletter form
- Social icons monokrom (tanpa warna-warni)
- Pemilih negara/region (opsional untuk versi sederhana: skip, cukup 1 negara/ID)
- Copyright & payment method badges kecil (opsional)

### 2.4 Animasi & Micro-interaction yang Perlu Direplikasi
Semua dibuat dengan **Alpine.js + Tailwind transition utilities**, **tanpa** library animasi berat, **tanpa** gradient, ikon minimal & monokrom:

| Elemen | Perilaku |
|---|---|
| Announcement bar | Rotasi teks otomatis (fade/slide tiap beberapa detik) |
| Header | Transparan di atas hero → jadi solid + shadow tipis saat scroll (`@scroll.window`) |
| Mega menu | Fade + slight slide-down saat hover/klik nav item |
| Kartu produk | Swap gambar utama → gambar ke-2 saat hover (`opacity` transition, tanpa crossfade berat) |
| Add to Bag | Tombol berubah state: teks → spinner tipis → checklist singkat → balik normal |
| Cart drawer | Slide-in dari kanan + backdrop fade (`x-transition`) |
| Wishlist heart | Toggle outline↔filled dengan transisi scale kecil |
| Filter drawer (mobile) | Slide dari bawah (bottom sheet) atau kanan |
| Accordion PDP | Expand/collapse height dengan transisi halus (Alpine `x-collapse` custom via CSS max-height) |
| Size guide / quick view | Modal fade + scale-in kecil |
| Toast notification | "Ditambahkan ke keranjang" muncul dari bawah/atas, auto-hilang 3 detik |
| Skeleton loading | Placeholder abu-abu berdenyut halus saat grid produk memuat ulang (filter/sort) |
| Newsletter popup | Muncul sekali per sesi (timer/exit-intent), bisa dimatikan dari `site_settings` |

---

## 3. Setup Tailwind CSS Tanpa Vite / NPM Run Dev

(Sama seperti proyek company-profile sebelumnya — reuse persis, hanya tokennya diganti sesuai Bagian 9.)

### 3.1 Instalasi
```bash
curl -sLO https://github.com/tailwindlabs/tailwindcss/releases/latest/download/tailwindcss-linux-x64
chmod +x tailwindcss-linux-x64
mv tailwindcss-linux-x64 tailwindcss
./tailwindcss init
```

### 3.2 `tailwind.config.js` (token dari style guide Allbirds)
```js
module.exports = {
  content: ["./resources/views/**/*.blade.php"],
  theme: {
    extend: {
      colors: {
        canvas: "#ffffff",
        charcoal: "#212121",
        black: "#000000",
        sand: "#e0dacf",
        oliveChar: "#222519",
        iron: "#525252",
        stone: "#737373",
        slateBorder: "#6a6767",
        oatMilk: "#ece9e2",
        mist: "#bdbab5",
      },
      fontFamily: {
        display: ["Playfair Display", "serif"],   // pengganti Self Modern
        sans: ["Inter", "sans-serif"],             // pengganti Geograph
      },
      borderRadius: {
        nav: "12px",
        card: "16px",
        input: "4px",
        pill: "9999px",
        surface: "20px",
      },
      letterSpacing: {
        wide10: "0.10em",
      },
    },
  },
  plugins: [],
}
```

### 3.3 `resources/css/app.css`
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
  .btn-pill-dark  { @apply inline-flex items-center justify-center rounded-pill bg-charcoal text-white text-xs font-medium uppercase tracking-wide10 px-6 py-3 hover:opacity-90 transition; }
  .btn-pill-light { @apply inline-flex items-center justify-center rounded-pill bg-white text-charcoal text-xs font-medium uppercase tracking-wide10 px-6 py-3 border border-charcoal hover:bg-charcoal hover:text-white transition; }
  .input-inset    { @apply rounded-input bg-white text-sm placeholder-iron px-4 py-3 shadow-[inset_0_0_0_1px_#575757,0_0_0_1px_#000]; }
  .nav-label      { @apply text-xs font-medium uppercase tracking-wide10 text-charcoal; }
  .section-title  { @apply text-center font-sans font-bold uppercase tracking-wide10 text-base border-b-2 border-charcoal inline-block pb-1; }
}
```

Build: `./tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify` (dev: tambah `--watch`).

### 3.4 JS Tanpa Build Step
```blade
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
```
Slider carousel produk pakai **Swiper.js via CDN**, modal/lightbox pakai Alpine murni. Ikon pakai **inline SVG** (heroicons outline, tempel manual sebagai partial Blade) — bukan icon-font, biar tetap "minim ikon & tanpa gradasi".

---

## 4. Desain Database (MySQL)

### 4.1 Diagram Relasi (ringkas)
```
users (role: customer/admin/super_admin)
  ├─< addresses
  ├─< carts ──< cart_items >── product_variants
  ├─< wishlists >── products
  ├─< reviews >── products
  └─< orders ──< order_items >── product_variants
                ├─ 1 payments
                └─ 1 shipments ──< shipment_trackings

categories (self-referencing, gender scope)
  └─< products ──< product_variants ──< product_images
products >─< collections (pivot: collection_product)
coupons ──< orders (nullable)
hero_slides / banners / pages / blog_posts / store_locations / site_settings (independen)
newsletter_subscribers (independen)
```

### 4.2 Detail Tabel

**`users`**
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| name, email(unique), phone nullable | varchar |
| password | varchar |
| role | enum('customer','admin','super_admin') default 'customer' |
| email_verified_at | timestamp nullable |
| timestamps | |

**`addresses`**
| id, user_id FK, label, recipient_name, phone, province, city, district, postal_code, address_line, biteship_area_id, latitude, longitude nullable, is_default(boolean), timestamps |

**`categories`**
| id, parent_id nullable FK→categories, gender enum('men','women','unisex'), name, slug unique, description nullable, image nullable, order int, is_active, timestamps |

**`collections`** (New Arrivals, Sale, Best Sellers, Pantone Collab, dst — kurasi manual)
| id, title, slug unique, description, banner_image, is_active, order, timestamps |

**`collection_product`** (pivot)
| collection_id, product_id |

**`products`**
| id, category_id FK, name, slug unique, short_description, description(longtext), material_info(text), sustainability_note(text), base_price(decimal), compare_at_price nullable(decimal), is_active, is_featured, weight_grams(int, utk hitung ongkir), meta_title/meta_description nullable, timestamps |

**`product_variants`**
| id, product_id FK, sku unique, color_name, color_hex, size, stock(int), price_override nullable(decimal), is_active, timestamps |

**`product_images`**
| id, product_id FK, variant_id nullable FK (gambar spesifik warna), image_path, order int, is_primary(boolean), timestamps |

**`carts`** | id, user_id nullable FK, session_id nullable (guest), timestamps |
**`cart_items`** | id, cart_id FK, product_variant_id FK, qty int, timestamps |

**`wishlists`** | id, user_id FK, product_id FK, timestamps | (unique user_id+product_id)

**`reviews`** | id, product_id FK, user_id FK, order_item_id nullable, rating(1-5), title, comment, is_approved(boolean default false), timestamps |

**`coupons`** | id, code unique, type enum('percent','fixed'), value(decimal), min_purchase nullable, max_discount nullable, starts_at, expires_at, usage_limit nullable, used_count default 0, is_active, timestamps |

**`orders`**
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| order_number | varchar unique (mis. `AB-20260905-0001`) |
| user_id | bigint FK nullable (support guest checkout) |
| guest_email, guest_name, guest_phone | varchar nullable |
| status | enum('pending_payment','paid','processing','ready_to_ship','shipped','delivered','completed','cancelled','refunded') default 'pending_payment' |
| subtotal, discount, shipping_cost, total | decimal |
| coupon_id | bigint FK nullable |
| shipping_address_snapshot | json |
| courier_company, courier_type | varchar nullable |
| notes | text nullable |
| timestamps | |

**`order_items`**
| id, order_id FK, product_variant_id FK nullable, product_name_snapshot, variant_snapshot(json: warna/ukuran), price(decimal), qty(int), subtotal(decimal), timestamps |

**`payments`**
| id, order_id FK, gateway enum('midtrans','xendit'), gateway_reference varchar, payment_method varchar nullable (va/credit_card/qris/ewallet), status enum('pending','success','failed','expired','refunded'), amount(decimal), paid_at nullable, raw_payload(json), timestamps |

**`shipments`**
| id, order_id FK, biteship_order_id varchar nullable, courier_company, courier_type, tracking_id nullable, waybill_id nullable, status enum('pending','requested','picked_up','on_process','delivered','cancelled') default 'pending', rate_snapshot(json), pickup_scheduled_at nullable, timestamps |

**`shipment_trackings`** (histori status dari webhook Biteship)
| id, shipment_id FK, status, note nullable, occurred_at datetime, timestamps |

**`hero_slides`** | id, page enum('home','men','women','sale', dst), image, title, subtitle, cta_text, cta_link, order, is_active, timestamps |

**`pages`** (CMS statis) | id, title, slug unique, content(longtext), meta_title/meta_description nullable, timestamps |

**`blog_posts`** | id, title, slug unique, excerpt, content(longtext), cover_image, is_published, published_at nullable, timestamps |

**`store_locations`** | id, name, address, city, phone, opening_hours(text/json), latitude, longitude, is_active, timestamps |

**`site_settings`** (key-value)
| key | contoh |
|---|---|
| store_name, store_email, store_phone | ... |
| origin_address, origin_postal_code, origin_biteship_area_id | alamat gudang/toko utk hitung ongkir & pickup |
| free_shipping_threshold | "500000" |
| announcement_text | teks berjalan atas |
| payment_gateway_active | "midtrans" |
| social_instagram/facebook/tiktok | url |
| newsletter_popup_enabled | "1" |

**`newsletter_subscribers`** | id, email unique, subscribed_at, is_active |

---

## 5. Routing Plan

### 5.1 Storefront (Public + Customer)
```
GET  /                                   HomeController@index
GET  /men | /women | /sale               CategoryController@show (gender/scope khusus)
GET  /collections/{slug}                 CollectionController@show   (filter+sort via query string)
GET  /products/{slug}                    ProductController@show
GET  /search                             SearchController@index
GET  /cart                               CartController@index
POST /cart/items                         CartController@store
PATCH/DELETE /cart/items/{id}            CartController@update/destroy
GET  /checkout                           CheckoutController@index
POST /checkout/shipping-rate             CheckoutController@shippingRate   (panggil Biteship)
POST /checkout                           CheckoutController@store          (buat order + trigger Snap)
GET  /order/{order_number}/success       CheckoutController@success
GET  /pages/{slug}                       PageController@show
GET  /stores                             StoreController@index
GET  /journal | /journal/{slug}          BlogController@index/show
POST /newsletter/subscribe               NewsletterController@store
POST /wishlist/toggle                    WishlistController@toggle          (auth)
POST /reviews                            ReviewController@store             (auth)

--- Auth ---
GET/POST /login , /register              Auth\AuthController
POST /logout                             Auth\AuthController@logout
GET/POST /forgot-password, /reset-password

--- Akun Customer (middleware auth) ---
GET  /account                            Account\DashboardController
GET  /account/orders                     Account\OrderController@index
GET  /account/orders/{order}             Account\OrderController@show
Resource /account/addresses              Account\AddressController
GET/PUT /account/profile                 Account\ProfileController

--- Webhooks (exclude CSRF) ---
POST /webhooks/midtrans                  Webhook\MidtransController
POST /webhooks/xendit                    Webhook\XenditController
POST /webhooks/biteship                  Webhook\BiteshipController
```

### 5.2 Admin (`/admin`, middleware `auth`, `role:admin,super_admin`)
```
GET/POST /admin/login , POST /admin/logout        Admin\AuthController
GET      /admin                                    Admin\DashboardController  (ringkasan penjualan, pesanan baru, stok menipis)

Resource /admin/categories
Resource /admin/collections
Resource /admin/products               (+ nested: variants, images)
Resource /admin/products/{id}/variants
Resource /admin/products/{id}/images
Resource /admin/coupons
Resource /admin/hero-slides
Resource /admin/pages
Resource /admin/blog-posts
Resource /admin/store-locations
GET      /admin/orders                  (list + filter status)
GET      /admin/orders/{order}          (detail, ubah status, catat tracking)
POST     /admin/orders/{order}/request-pickup     Admin\ShipmentController@requestPickup  (call Biteship)
GET      /admin/payments                (log transaksi)
GET      /admin/reviews                 (approve/reject)
GET/PUT  /admin/settings                (site_settings, grouped by section)
GET      /admin/subscribers  (+export CSV)
Resource /admin/customers               (read + detail riwayat order, tanpa hapus password dsb)
Resource /admin/users                   (khusus super_admin — kelola akun admin)
GET      /admin/reports/sales           (grafik penjualan sederhana)
```

---

## 6. Modul Admin (Ringkasan CRUD)

| Modul | List | Form |
|---|---|---|
| Products | Tabel + search/filter kategori/status + thumbnail | Nama, slug(auto), kategori, deskripsi, material, sustainability note, harga, compare-at, berat, featured toggle, meta SEO |
| Variants (nested di Product) | Tabel varian per produk | Warna (nama+hex color picker), ukuran, SKU, stok, harga override |
| Images (nested) | Grid, drag-reorder, tandai "primary" per varian warna | Upload multi-gambar, pilih varian terkait |
| Categories | Tree (parent→child) + gender filter | Nama, slug, gender, parent, gambar, urutan |
| Collections | Tabel | Judul, slug, banner, pilih produk (multi-select/search) |
| Coupons | Tabel + status aktif/kadaluarsa | Kode, tipe, nilai, min. belanja, kuota, tanggal berlaku |
| Orders | Tabel + filter status + search order number/nama | Detail read-only item, form ubah status manual, tombol "Request Pickup Biteship", catatan internal |
| Payments | Tabel log (readonly) | Detail payload gateway, tombol "Cek Status Ulang" |
| Reviews | Tabel pending/approved | Approve/reject, hapus |
| Hero Slides | List per halaman + reorder | Gambar, judul, subjudul, CTA, aktif |
| Pages (CMS) | Tabel | Judul, slug, konten (rich text via Quill CDN) |
| Blog Posts | Tabel + filter publish | Judul, excerpt, konten, cover, jadwal publish |
| Store Locations | Tabel | Nama, alamat, jam buka, koordinat (peta sederhana) |
| Site Settings | 1 form panjang grouped | Semua key di 4.2, termasuk pilih gateway aktif |
| Subscribers | Tabel + export CSV | readonly, hapus |
| Customers | Tabel + detail | Read-only profil & riwayat order |
| Users (admin) | Tabel | Nama, email, password, role — khusus super_admin |
| Reports | Grafik/summary | Penjualan per hari/bulan, produk terlaris |

Rich text editor pakai **Quill.js via CDN** (untuk `description`, `content` di Products/Pages/Blog) — tanpa NPM.

---

## 7. Integrasi Payment Gateway (Midtrans, dibuat siap-multi-gateway)

### 7.1 Arsitektur
- Interface `PaymentGatewayContract` dengan implementasi `MidtransGateway` (default) dan `XenditGateway` (opsional/nanti). Gateway aktif ditentukan dari `site_settings.payment_gateway_active`, di-resolve lewat Service Container binding.
- Kredensial (Server Key, Client Key, mode sandbox/production) disimpan di `.env`, **bukan** di database.

### 7.2 Alur Midtrans Snap
1. Customer klik "Place Order" di `/checkout` → `OrderService` buat record `orders` (status `pending_payment`) + `order_items`.
2. Backend panggil Midtrans **Snap API** (`/snap/v1/transactions`) → dapat `snap_token`.
3. Frontend load `https://app.sandbox.midtrans.com/snap/snap.js` (CDN resmi Midtrans, tanpa NPM) dan panggil `snap.pay(snap_token, {onSuccess, onPending, onError})`.
4. Midtrans kirim **notification webhook** ke `POST /webhooks/midtrans` → verifikasi signature key → update tabel `payments` & `orders.status` jadi `paid`.
5. Setelah `paid`, sistem otomatis siapkan draft `shipments` (status `pending`) menunggu admin proses & request pickup.

### 7.3 Keamanan
- Verifikasi signature key tiap notifikasi Midtrans (`sha512(order_id+status_code+gross_amount+ServerKey)`).
- Idempotency: cek `gateway_reference` sudah diproses atau belum sebelum update status, hindari double-processing.
- Endpoint webhook di-exclude dari CSRF (`VerifyCsrfToken` except list) tapi tetap validasi signature.

---

## 8. Integrasi Biteship (Cek Ongkir, Order, Pickup, Tracking)

### 8.1 Cek Ongkir Realtime (saat checkout & di PDP)
- `POST https://api.biteship.com/v1/rates/couriers` dengan `origin_area_id` (dari `site_settings.origin_biteship_area_id`), `destination_area_id` (dari alamat customer), berat total (`sum(product.weight_grams * qty)`), daftar kurir yang diaktifkan.
- Autocomplete area alamat pakai `GET /v1/maps/areas?countries=ID&input=...` saat customer mengetik kota/kecamatan (dipanggil via Alpine + fetch, debounce).
- Hasil rate ditampilkan sebagai pilihan radio (nama kurir, layanan, estimasi, harga) di Step 2 checkout.

### 8.2 Setelah Order Dibayar → Buat Order Pengiriman
- Admin (atau otomatis saat status `processing`→`ready_to_ship`) memicu `POST /v1/orders` ke Biteship: origin, destination, item, kurir yang dipilih customer saat checkout → dapat `biteship_order_id`.
- Simpan ke tabel `shipments`.

### 8.3 Request Pickup Kurir
- Tombol admin "Request Pickup" → `POST /v1/orders/{biteship_order_id}/pickup` (atau endpoint pickup request sesuai dokumentasi Biteship terbaru) → jadwalkan penjemputan → update `shipments.status = 'requested'` & `pickup_scheduled_at`.

### 8.4 Webhook Tracking
- Biteship kirim update status ke `POST /webhooks/biteship` (courier_status: `confirmed`, `picking_up`, `on_process`, `delivered`, dll) → simpan ke `shipment_trackings` + update `shipments.status` + `orders.status` (mis. jadi `shipped`/`delivered`) → (opsional) kirim email/notifikasi ke customer.
- Customer bisa lihat histori tracking ini di `/account/orders/{order}`.

> **Catatan implementasi:** Nama endpoint & payload persis harus dicek ulang ke dokumentasi resmi Biteship (https://biteship.com/docs) saat development karena API bisa berubah versi — Claude Code sebaiknya `web_fetch` dokumentasi tsb sebelum menulis `BiteshipService`.

---

## 9. Design System (dari Style Guide Allbirds — Bagian ini WAJIB diikuti pas styling)

| Token | Nilai |
|---|---|
| Warna dasar | Canvas `#ffffff`, Charcoal `#212121`, Black `#000000` |
| Warna sekunder | Warm Sand `#e0dacf` (hero/section band), Oat Milk `#ece9e2` (alt background) |
| Warna teks | Body `#212121`/`#525252` di atas putih — **tidak pernah** abu terlalu terang |
| Radius | Tombol **pill** (`9999px`) selalu, card `16px`, input `4px` |
| Tombol utama | `bg-charcoal text-white uppercase text-xs tracking-wide10 px-6 py-3 rounded-pill`, hover → invert warna |
| Tipografi | Display: Playfair Display (pengganti Self Modern, hanya 1–2 heading besar per halaman); Body/UI: Inter (pengganti Geograph), uppercase+tracking lebar untuk label/nav |
| Section spacing | 40–56px antar section, max-width konten ~1200px |
| Kartu kategori | Background warna solid flat (bukan foto sebagai background, foto produk di-center di atasnya), label pill putih kecil pojok kiri-atas |
| Kartu produk | Background putih, tanpa border/shadow berlebih, radius 16px pada gambar |
| **Larangan eksplisit (sesuai instruksi user)** | **Tidak ada gradient** di manapun (tombol, card, background). **Ikon seminimal mungkin**, monokrom (hitam/charcoal), tanpa icon pack berwarna-warni. Tanpa drop-shadow berat — kalau perlu separasi, pakai warna flat / border tipis 1px. |

---

## 10. Struktur Folder Laravel (Final)

```
app/
 ├─ Http/Controllers/
 │   ├─ HomeController, CategoryController, CollectionController, ProductController,
 │   │  SearchController, CartController, CheckoutController, PageController,
 │   │  StoreController, BlogController, NewsletterController, WishlistController,
 │   │  ReviewController
 │   ├─ Auth/AuthController
 │   ├─ Account/{DashboardController, OrderController, AddressController, ProfileController}
 │   ├─ Webhook/{MidtransController, XenditController, BiteshipController}
 │   └─ Admin/
 │       ├─ AuthController, DashboardController
 │       ├─ ProductController, ProductVariantController, ProductImageController
 │       ├─ CategoryController, CollectionController, CouponController
 │       ├─ OrderController, ShipmentController, PaymentController
 │       ├─ ReviewController, HeroSlideController, PageController, BlogPostController
 │       ├─ StoreLocationController, SettingController, SubscriberController
 │       ├─ CustomerController, UserController, ReportController
 ├─ Services/
 │   ├─ Payment/{PaymentGatewayContract, MidtransGateway, XenditGateway, PaymentService}
 │   ├─ Shipping/BiteshipService.php
 │   ├─ CartService.php
 │   └─ OrderService.php
 ├─ Models/ (semua model sesuai Bagian 4)
 └─ Http/Middleware/{EnsureCustomer.php, EnsureAdmin.php}

resources/
 ├─ css/app.css
 └─ views/
     ├─ layouts/{app.blade.php, account.blade.php, admin.blade.php}
     ├─ partials/{announcement-bar, header, mega-menu, footer, cart-drawer,
     │             product-card, hero-slider, size-guide-modal, toast}
     ├─ home/, categories/, collections/, products/, cart/, checkout/,
     │   pages/, stores/, blog/, auth/, account/
     └─ admin/ (index/create/edit per modul, sesuai Bagian 6)

public/css/app.css
storage -> ../storage/app/public

database/{migrations, seeders}   (1 migration per tabel Bagian 4; seeder: CategorySeeder,
                                   ProductSeeder+Variants+Images dummy, SiteSettingSeeder,
                                   AdminUserSeeder, PageSeeder — About/FAQ/Sustainability)

routes/web.php
tailwind.config.js
tailwindcss (binary)
.env  (MIDTRANS_SERVER_KEY, MIDTRANS_CLIENT_KEY, MIDTRANS_IS_PRODUCTION,
        BITESHIP_API_KEY, BITESHIP_ORIGIN_AREA_ID)
```

---

## 11. Autentikasi & Role (Dual Role Tanpa Breeze/Vite)

1. Satu tabel `users` + kolom `role` (`customer`/`admin`/`super_admin`).
2. Storefront auth pakai `Auth::attempt()` bawaan Laravel di guard `web` — semua yang register lewat `/register` otomatis `role = customer`.
3. Admin login **terpisah** di `/admin/login` (form & controller sendiri) tapi tetap query ke tabel `users` dengan tambahan cek `role IN ('admin','super_admin')` — kalau customer coba login lewat form admin, ditolak dengan pesan jelas.
4. Middleware `EnsureAdmin` melindungi semua route `/admin/*` kecuali `/admin/login`.
5. Middleware `EnsureCustomer`/`auth` standar melindungi `/account/*`.
6. Seeder `AdminUserSeeder` bikin 1 akun `super_admin` default saat instalasi.

---

## 12. Rencana Tahapan Pengerjaan (Sprints)

**Sprint 1 — Fondasi**
Laravel + MySQL + Tailwind CLI setup (Bagian 3), semua migration+model (Bagian 4), layout dasar (header dengan mega menu + announcement bar, footer), auth dual-role (Bagian 11), seeder dasar (kategori, admin user, site settings).

**Sprint 2 — Katalog Produk**
Admin CRUD Products+Variants+Images+Categories+Collections. Halaman publik: Collection (filter+sort+drawer mobile) & PDP (galeri, pilih warna/ukuran, accordion, sustainability info).

**Sprint 3 — Cart & Wishlist**
Cart drawer + halaman cart penuh (guest cart via session + merge saat login), wishlist toggle, quick-add dari kartu produk.

**Sprint 4 — Checkout Bagian 1: Alamat & Ongkir**
CRUD alamat customer, integrasi Biteship area autocomplete + cek ongkir realtime (Bagian 8.1) di step checkout.

**Sprint 5 — Checkout Bagian 2: Pembayaran**
`PaymentGatewayContract` + `MidtransGateway`, Snap integration, buat order, webhook `/webhooks/midtrans` (Bagian 7), halaman order success.

**Sprint 6 — Fulfillment & Tracking**
Admin order detail + ubah status, `BiteshipService` buat order pengiriman + request pickup (Bagian 8.2–8.3), webhook `/webhooks/biteship` update tracking (8.4), customer lihat tracking di `/account/orders/{order}`.

**Sprint 7 — Admin Panel Lanjutan**
CRUD Coupons, Reviews moderation, Hero Slides, Pages (CMS), Blog Posts, Store Locations, Site Settings (form grouped), Subscribers+export, Reports/dashboard ringkas.

**Sprint 8 — Akun Customer**
Dashboard, riwayat pesanan+detail+tracking, alamat, profil, ulasan yang pernah ditulis.

**Sprint 9 — Polish**
Semua animasi & micro-interaction (Bagian 2.4), responsive check, SEO (meta dinamis, sitemap), keamanan (rate limit, validasi upload), optimasi gambar, compile Tailwind `--minify`, siapkan deployment.

---

## 13. SEO, Performa & Keamanan (Checklist)

- [ ] Meta title/description dinamis per produk/koleksi/halaman
- [ ] Slug otomatis (`Str::slug`) + validasi unik
- [ ] Sitemap.xml (produk, koleksi, blog, halaman statis)
- [ ] Gambar produk di-resize/compress saat upload (Intervention Image)
- [ ] Lazy-loading gambar & `srcset` untuk galeri produk
- [ ] CSRF di semua form publik; webhook di-exclude tapi wajib verifikasi signature
- [ ] Rate limiting: checkout, login, newsletter, review (`throttle`)
- [ ] Validasi stok real-time (cegah overselling saat checkout bersamaan — gunakan DB transaction + lock saat kurangi stok)
- [ ] Enkripsi/hash password bawaan Laravel; jangan log data kartu/pembayaran mentah
- [ ] `.env` untuk semua kredensial (Midtrans, Biteship, DB, mail) — tidak di-commit
- [ ] Backup database berkala

---

## 14. Deployment

1. Upload project (tanpa `node_modules`) via Git/FTP
2. `composer install --no-dev --optimize-autoloader`
3. Setup `.env` (DB, `APP_URL`, kredensial Midtrans **production**, kredensial Biteship, `APP_ENV=production`)
4. `php artisan migrate --seed`
5. `php artisan storage:link`
6. Compile Tailwind final di lokal (`--minify`), commit `public/css/app.css`
7. `php artisan config:cache && route:cache && view:cache`
8. Daftarkan URL webhook production ke dashboard Midtrans & Biteship (`https://domainmu.com/webhooks/midtrans`, `/webhooks/biteship`)
9. Arahkan document root ke `/public`
10. Test transaksi end-to-end di mode sandbox dulu sebelum switch ke production key

---

## 15. Checklist Fitur Final

- [x] Header + mega menu 2 level + announcement bar + sticky-on-scroll
- [x] Homepage dinamis (hero slides, kategori grid, carousel produk, semua dari DB)
- [x] Katalog: kategori, koleksi, filter, sort, search
- [x] PDP lengkap: varian warna/ukuran, galeri, wishlist, review, cek ongkir cepat
- [x] Cart (drawer + halaman), guest cart + merge login
- [x] Checkout 3 langkah: alamat → ongkir (Biteship) → pembayaran (Midtrans Snap)
- [x] Order management admin + request pickup Biteship + webhook tracking
- [x] Akun customer: dashboard, order history+tracking, alamat, profil, wishlist
- [x] Dual role (customer/admin) dalam 1 tabel users
- [x] CMS: pages statis, blog/journal, store locations, hero slides — semua CRUD
- [x] Coupons, reviews moderation, subscribers+export, reports ringkas
- [x] Tailwind compile tanpa Vite/NPM run dev, tanpa Node di server produksi
- [x] Tanpa gradient di UI, ikon minimal & monokrom sesuai instruksi

---

## 16. Langkah Selanjutnya

Setelah plan ini disetujui, eksekusi disarankan urut **Sprint 1 → 9** (Bagian 12), pakai prompt di file `PROMPT_EKSEKUSI.md`. Beri tahu mana yang mau dikerjakan lebih dulu, atau saya bisa langsung mulai Sprint 1.
