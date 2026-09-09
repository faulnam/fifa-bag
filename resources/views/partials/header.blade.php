@php
    $menCategory = \App\Models\Category::where('gender', 'men')->whereNull('parent_id')->with('children.children')->first();
    $womenCategory = \App\Models\Category::where('gender', 'women')->whereNull('parent_id')->with('children.children')->first();
    $collections = \App\Models\Collection::where('is_active', true)->take(4)->get();
@endphp

<header x-data="{
        isScrolled: false,
        activeMenu: null,
        searchOpen: false,
        searchQuery: '',
        searchResults: [],
        searchLoading: false,
        mobileCategoryTab: 'men',
        mobileAccordion: {
            menBags: false,
            menApparel: false,
            womenBags: false,
            womenApparel: false
        },
        async doLiveSearch() {
            const q = this.searchQuery.trim();
            if (q.length < 2) {
                this.searchResults = [];
                this.searchLoading = false;
                return;
            }
            this.searchLoading = true;
            try {
                const res = await fetch(`{{ route('search.live') }}?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                if (data.success) {
                    this.searchResults = data.products;
                }
            } catch (e) {
                console.error('Search error', e);
            } finally {
                this.searchLoading = false;
            }
        },
        selectTag(tag) {
            this.searchQuery = tag;
            this.doLiveSearch();
            this.$nextTick(() => { this.$refs.searchInput?.focus(); });
        }
    }" 
    @scroll.window="isScrolled = (window.pageYOffset > 10)"
    @keydown.window.escape="searchOpen = false"
    @keydown.window.ctrl.k.prevent="searchOpen = true; $nextTick(() => { $refs.searchInput?.focus(); })"
    @keydown.window.cmd.k.prevent="searchOpen = true; $nextTick(() => { $refs.searchInput?.focus(); })"
    @open-search.window="searchOpen = true; $nextTick(() => { $refs.searchInput?.focus(); })"
    class="sticky top-0 z-40 transition-all duration-200 px-3 sm:px-6 pt-2 pb-2 bg-transparent">

    <div class="max-w-[1400px] mx-auto bg-white/95 backdrop-blur-md rounded-2xl border border-sand/70 shadow-xs px-4 sm:px-6 h-14 sm:h-16 flex items-center justify-between">
        
        <!-- Left: Mobile Hamburger & Brand Logo -->
        <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
            <!-- Mobile Hamburger Button -->
            <button type="button" 
                    @click="mobileMenuOpen = true"
                    class="lg:hidden p-2 -ml-2 text-charcoal hover:text-black focus:outline-none min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer"
                    aria-label="Buka Menu Navigasi">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center text-charcoal hover:opacity-85 transition">
                <img src="{{ asset('images/fifa-logo.svg') }}" alt="fifa" class="h-6 sm:h-7 w-auto object-contain">
            </a>
        </div>

        <!-- Center: Desktop Navigation -->
        <nav class="hidden lg:flex items-center space-x-7" @mouseleave="activeMenu = null">
            <!-- NEW ARRIVALS -->
            <div>
                <a href="{{ route('collections.show', 'new-arrivals') }}" 
                   class="nav-label py-2 inline-block font-bold tracking-widest text-[12px] uppercase {{ request()->is('*new-arrivals*') ? 'border-b-2 border-charcoal' : '' }}">
                    PRODUK TERBARU
                </a>
            </div>

            <!-- SHOP ALL -->
            <div>
                <a href="{{ route('search.index') }}" 
                   class="nav-label py-2 inline-block font-bold tracking-widest text-[12px] uppercase {{ request()->is('search*') ? 'border-b-2 border-charcoal' : '' }}">
                    SEMUA PRODUK
                </a>
            </div>

            <!-- MEN Dropdown -->
            <div class="relative" @mouseenter="activeMenu = 'men'">
                <a href="{{ route('categories.men') }}" 
                   class="nav-label py-2 inline-block font-bold tracking-widest text-[12px] uppercase {{ request()->is('men*') ? 'border-b-2 border-charcoal' : '' }}">
                    PRIA
                </a>
            </div>

            <!-- WOMEN Dropdown -->
            <div class="relative" @mouseenter="activeMenu = 'women'">
                <a href="{{ route('categories.women') }}" 
                   class="nav-label py-2 inline-block font-bold tracking-widest text-[12px] uppercase {{ request()->is('women*') ? 'border-b-2 border-charcoal' : '' }}">
                    WANITA
                </a>
            </div>
        </nav>

        <!-- Right: Utility Icons & Actions -->
        <div class="flex items-center space-x-1 sm:space-x-2">
            <!-- Search Button (Opens Live Search Modal) -->
            <button type="button" 
                    @click="searchOpen = true; $nextTick(() => { $refs.searchInput?.focus(); })"
                    class="p-2 text-charcoal hover:text-black min-w-[40px] min-h-[40px] flex items-center justify-center transition cursor-pointer"
                    aria-label="Pencarian Produk">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>

            <!-- Account / Auth Link -->
            @auth
                <div class="relative" x-data="{ accountOpen: false }" @click.away="accountOpen = false">
                    <button @click="accountOpen = !accountOpen" 
                            class="p-2 text-charcoal hover:text-black min-w-[40px] min-h-[40px] flex items-center justify-center transition"
                            aria-label="Menu Akun">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    <div x-show="accountOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-canvas border border-sand rounded-card shadow-lg py-2 z-50">
                        <div class="px-4 py-2 border-b border-sand text-caption text-stone">
                            Masuk sebagai <span class="font-medium text-charcoal block truncate">{{ Auth::user()->name }}</span>
                        </div>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-body-sm text-charcoal hover:bg-sand/30">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-body-sm text-charcoal hover:bg-sand/30">
                                Akun Saya
                            </a>
                            <a href="{{ route('account.wishlist') }}" class="block px-4 py-2 text-body-sm text-charcoal hover:bg-sand/30">
                                Wishlist Saya
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-body-sm text-charcoal hover:bg-sand/30">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" 
                   class="p-2 text-charcoal hover:text-black min-w-[40px] min-h-[40px] flex items-center justify-center transition"
                   aria-label="Masuk ke Akun">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            @endauth

            <!-- Cart Button (Opens slide-in drawer) -->
            <button type="button" 
                    @click.prevent="$store.cart.open = true"
                    class="p-2 text-charcoal hover:text-black min-w-[40px] min-h-[40px] flex items-center justify-center relative transition"
                    aria-label="Keranjang Belanja">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span x-show="$store.cart.count > 0"
                      x-text="$store.cart.count" 
                      class="absolute top-1 right-0.5 bg-charcoal text-canvas text-[10px] font-bold min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center">
                </span>
            </button>
        </div>

    </div>

    <!-- Desktop Floating Mega Menus -->
    <div class="max-w-[1400px] mx-auto relative">
        <!-- Desktop Mega Menu: MEN -->
        <div x-show="activeMenu === 'men'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @mouseenter="activeMenu = 'men'"
             @mouseleave="activeMenu = null"
             class="hidden lg:block absolute left-0 right-0 top-2 bg-white rounded-2xl border border-sand shadow-2xl z-50 p-8">
            <div class="grid grid-cols-4 gap-8">
                <div>
                    <h3 class="nav-label font-bold text-charcoal border-b border-sand pb-2 mb-4">Tas Pria</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('collections.show', 'men-backpacks') }}" class="text-body-sm text-iron hover:text-charcoal transition">Ransel & Backpack</a></li>
                        <li><a href="{{ route('collections.show', 'men-briefcases') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tas Kerja & Briefcase</a></li>
                        <li><a href="{{ route('collections.show', 'men-sling-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tas Selempang & Sling</a></li>
                        <li><a href="{{ route('collections.show', 'men-duffle-travel') }}" class="text-body-sm text-iron hover:text-charcoal transition">Duffle & Travel Bag</a></li>
                        <li><a href="{{ route('collections.show', 'men-tote-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tote Bag Pria</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="nav-label font-bold text-charcoal border-b border-sand pb-2 mb-4">Aksesori & Dompet</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('collections.show', 'men-wallets') }}" class="text-body-sm text-iron hover:text-charcoal transition">Dompet & Cardholder</a></li>
                        <li><a href="{{ route('collections.show', 'men-pouches') }}" class="text-body-sm text-iron hover:text-charcoal transition">Pouch & Tech Organizer</a></li>
                        <li><a href="{{ route('collections.show', 'men-straps-accessories') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tali & Aksesori Tas</a></li>
                        <li><a href="{{ route('collections.show', 'urban-backpacks') }}" class="text-body-sm text-iron hover:text-charcoal transition">Koleksi Ransel Urban</a></li>
                    </ul>
                </div>

                <!-- Mega Menu Category Swatch Card 1: Men Commuter Rolltop Backpack -->
                <a href="{{ route('collections.show', 'men-backpacks') }}" 
                   class="group relative rounded-[20px] bg-[#8b9aa4]/15 hover:bg-[#8b9aa4]/25 p-5 flex flex-col justify-between overflow-hidden min-h-[220px] border border-[#8b9aa4]/30 transition duration-300">
                    <div class="z-10">
                        <span class="inline-block bg-white text-charcoal rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                            Commuter Rolltop
                        </span>
                        <p class="text-[12px] text-charcoal/80 font-medium mt-1.5 leading-snug">Ransel tahan air & kompartemen laptop 16 inci.</p>
                    </div>
                    
                    <!-- Product Image -->
                    <div class="my-auto py-1 flex items-center justify-center">
                        <img src="{{ asset('images/products/commuter-backpack-navy.png') }}" 
                             alt="Ransel Commuter Rolltop" 
                             class="h-24 w-auto object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <span class="text-[11px] uppercase tracking-wider font-bold text-charcoal underline underline-offset-4 group-hover:opacity-80 transition inline-block">
                        Beli Ransel Pria →
                    </span>
                </a>

                <!-- Mega Menu Category Swatch Card 2: Men Executive Briefcase -->
                <a href="{{ route('collections.show', 'men-briefcases') }}" 
                   class="group relative rounded-[20px] bg-[#8a7466]/15 hover:bg-[#8a7466]/25 p-5 flex flex-col justify-between overflow-hidden min-h-[220px] border border-[#8a7466]/30 transition duration-300">
                    <div class="z-10">
                        <span class="inline-block bg-white text-charcoal rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                            Executive Briefcase
                        </span>
                        <p class="text-[12px] text-charcoal/80 font-medium mt-1.5 leading-snug">Kemewahan kulit nabati bio-leather berkelas.</p>
                    </div>

                    <!-- Product Image -->
                    <div class="my-auto py-1 flex items-center justify-center">
                        <img src="{{ asset('images/products/executive-briefcase-brown.png') }}" 
                             alt="Executive Briefcase" 
                             class="h-24 w-auto object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <span class="text-[11px] uppercase tracking-wider font-bold text-charcoal underline underline-offset-4 group-hover:opacity-80 transition inline-block">
                        Beli Tas Kerja Pria →
                    </span>
                </a>
            </div>
        </div>

        <!-- Desktop Mega Menu: WOMEN -->
        <div x-show="activeMenu === 'women'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @mouseenter="activeMenu = 'women'"
             @mouseleave="activeMenu = null"
             class="hidden lg:block absolute left-0 right-0 top-2 bg-white rounded-2xl border border-sand shadow-2xl z-50 p-8">
            <div class="grid grid-cols-4 gap-8">
                <div>
                    <h3 class="nav-label font-bold text-charcoal border-b border-sand pb-2 mb-4">Tas Wanita</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('collections.show', 'women-tote-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tote Bag & Shopper</a></li>
                        <li><a href="{{ route('collections.show', 'women-shoulder-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tas Bahu & Shoulder Bag</a></li>
                        <li><a href="{{ route('collections.show', 'women-crossbody-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Tas Selempang & Crossbody</a></li>
                        <li><a href="{{ route('collections.show', 'women-handbags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Handbag & Satchel</a></li>
                        <li><a href="{{ route('collections.show', 'women-mini-backpacks') }}" class="text-body-sm text-iron hover:text-charcoal transition">Ransel Modis & Mini Backpack</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="nav-label font-bold text-charcoal border-b border-sand pb-2 mb-4">Aksesori & Dompet</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('collections.show', 'women-wallets') }}" class="text-body-sm text-iron hover:text-charcoal transition">Dompet & Clutches</a></li>
                        <li><a href="{{ route('collections.show', 'women-pouches') }}" class="text-body-sm text-iron hover:text-charcoal transition">Pouch & Makeup Case</a></li>
                        <li><a href="{{ route('collections.show', 'women-bag-charms') }}" class="text-body-sm text-iron hover:text-charcoal transition">Gantungan & Bag Charms</a></li>
                        <li><a href="{{ route('collections.show', 'eco-canvas') }}" class="text-body-sm text-iron hover:text-charcoal transition">Koleksi Eco Canvas</a></li>
                    </ul>
                </div>

                <!-- Mega Menu Category Swatch Card 1: Women Crescent Hobo -->
                <a href="{{ route('collections.show', 'women-shoulder-bags') }}" 
                   class="group relative rounded-[20px] bg-[#c4a4a4]/20 hover:bg-[#c4a4a4]/30 p-5 flex flex-col justify-between overflow-hidden min-h-[220px] border border-[#c4a4a4]/35 transition duration-300">
                    <div class="z-10">
                        <span class="inline-block bg-white text-charcoal rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                            Crescent Hobo Bag
                        </span>
                        <p class="text-[12px] text-charcoal/80 font-medium mt-1.5 leading-snug">Siluet bulan sabit anggun dari kulit nabati apel.</p>
                    </div>

                    <!-- Product Image -->
                    <div class="my-auto py-1 flex items-center justify-center">
                        <img src="{{ asset('images/products/crescent-shoulder-cream.png') }}" 
                             alt="Crescent Hobo Bag" 
                             class="h-24 w-auto object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <span class="text-[11px] uppercase tracking-wider font-bold text-charcoal underline underline-offset-4 group-hover:opacity-80 transition inline-block">
                        Beli Tas Bahu Wanita →
                    </span>
                </a>

                <!-- Mega Menu Category Swatch Card 2: Women Flap Crossbody -->
                <a href="{{ route('collections.show', 'women-crossbody-bags') }}" 
                   class="group relative rounded-[20px] bg-[#8a9a8c]/20 hover:bg-[#8a9a8c]/30 p-5 flex flex-col justify-between overflow-hidden min-h-[220px] border border-[#8a9a8c]/35 transition duration-300">
                    <div class="z-10">
                        <span class="inline-block bg-white text-charcoal rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                            Flap Crossbody Bag
                        </span>
                        <p class="text-[12px] text-charcoal/80 font-medium mt-1.5 leading-snug">Warna pastel mewah untuk acara santai & semi-formal.</p>
                    </div>

                    <!-- Product Image -->
                    <div class="my-auto py-1 flex items-center justify-center">
                        <img src="{{ asset('images/products/leather-crossbody-mauve.png') }}" 
                             alt="Flap Crossbody Bag" 
                             class="h-24 w-auto object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <span class="text-[11px] uppercase tracking-wider font-bold text-charcoal underline underline-offset-4 group-hover:opacity-80 transition inline-block">
                        Beli Crossbody Wanita →
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Desktop Mega Menu: SALE -->
    <div x-show="activeMenu === 'sale'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @mouseenter="activeMenu = 'sale'"
         @mouseleave="activeMenu = null"
         class="hidden lg:block absolute left-0 right-0 top-full bg-canvas border-b border-sand shadow-lg z-40 py-8">
        <div class="max-w-container mx-auto px-8">
            <div class="grid grid-cols-3 gap-8">
                <div>
                    <h3 class="nav-label font-bold text-charcoal border-b border-sand pb-2 mb-4">Diskon Berdasarkan Kategori</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('collections.show', 'men-backpacks') }}" class="text-body-sm text-iron hover:text-charcoal transition">Diskon Tas Pria</a></li>
                        <li><a href="{{ route('collections.show', 'women-crossbody-bags') }}" class="text-body-sm text-iron hover:text-charcoal transition">Diskon Tas Wanita</a></li>
                        <li><a href="{{ route('collections.sale') }}" class="text-body-sm text-iron hover:text-charcoal transition">Semua Produk Diskon</a></li>
                    </ul>
                </div>
                <div class="col-span-2 rounded-card bg-sand/30 p-6 flex flex-col justify-center border border-sand">
                    <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">Penawaran Waktu Terbatas</span>
                    <h4 class="font-display text-heading-sm font-normal text-charcoal mt-1">Koleksi Tas Pilihan Diskon Spesial</h4>
                    <p class="text-body-sm text-iron mt-2">Dapatkan penawaran menarik hingga 30% untuk pilihan tas ransel, tote, dan selempang ramah bumi.</p>
                    <div class="mt-4">
                        <a href="{{ route('collections.sale') }}" class="btn-pill-dark inline-block">
                            Belanja Semua Diskon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer (Full Height Slide-in) -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-50 lg:hidden"
         @click="mobileMenuOpen = false"
         style="display: none;">
    </div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 max-w-[340px] w-full bg-canvas shadow-2xl z-50 flex flex-col justify-between overflow-y-auto lg:hidden"
         style="display: none;">
        
        <div>
            <!-- Mobile Header Top with Close Button -->
            <div class="flex items-center justify-between p-4 border-b border-sand">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="flex items-center text-charcoal hover:opacity-85 transition">
                    <img src="{{ asset('images/fifa-logo.svg') }}" alt="fifa" class="h-6 w-auto object-contain">
                </a>
                <button type="button" 
                        @click="mobileMenuOpen = false" 
                        class="p-2 text-charcoal hover:text-black min-w-[44px] min-h-[44px] flex items-center justify-center"
                        aria-label="Tutup Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Search Trigger in Drawer -->
            <div class="p-3 border-b border-sand">
                <button type="button" 
                        @click="mobileMenuOpen = false; searchOpen = true; $nextTick(() => { $refs.searchInput?.focus(); })"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2.5 bg-sand/30 hover:bg-sand/50 rounded-xl text-body-sm text-stone border border-sand/60 transition text-left cursor-pointer">
                    <svg class="w-4 h-4 text-stone flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-charcoal/70">Cari tas fifa...</span>
                </button>
            </div>

            <!-- Mobile Gender Segment Switcher -->
            <div class="grid grid-cols-3 border-b border-sand bg-oatMilk/30">
                <button type="button" 
                        @click="mobileCategoryTab = 'men'"
                        :class="mobileCategoryTab === 'men' ? 'border-b-2 border-charcoal font-bold text-charcoal bg-canvas' : 'text-iron'"
                        class="py-3 text-caption font-medium uppercase tracking-wide10 min-h-[44px] transition">
                    Pria
                </button>
                <button type="button" 
                        @click="mobileCategoryTab = 'women'"
                        :class="mobileCategoryTab === 'women' ? 'border-b-2 border-charcoal font-bold text-charcoal bg-canvas' : 'text-iron'"
                        class="py-3 text-caption font-medium uppercase tracking-wide10 min-h-[44px] transition">
                    Wanita
                </button>
                <button type="button" 
                        @click="mobileCategoryTab = 'sale'"
                        :class="mobileCategoryTab === 'sale' ? 'border-b-2 border-charcoal font-bold text-charcoal bg-canvas' : 'text-iron'"
                        class="py-3 text-caption font-medium uppercase tracking-wide10 min-h-[44px] transition">
                    Diskon
                </button>
            </div>

            <!-- Mobile Tab Content: MEN -->
            <div x-show="mobileCategoryTab === 'men'" class="p-4 space-y-4">
                <!-- Bags Accordion -->
                <div class="border-b border-sand pb-3">
                    <button type="button" 
                            @click="mobileAccordion.menBags = !mobileAccordion.menBags" 
                            class="w-full flex items-center justify-between py-2 text-body-sm font-semibold text-charcoal min-h-[44px]">
                        <span>Tas Pria</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="mobileAccordion.menBags ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="mobileAccordion.menBags" x-collapse class="pl-4 space-y-2.5 pt-2">
                        <a href="{{ route('collections.show', 'men-backpacks') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Ransel & Backpack</a>
                        <a href="{{ route('collections.show', 'men-briefcases') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tas Kerja & Briefcase</a>
                        <a href="{{ route('collections.show', 'men-sling-bags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tas Selempang & Sling</a>
                        <a href="{{ route('collections.show', 'men-duffle-travel') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Duffle & Travel Bag</a>
                        <a href="{{ route('collections.show', 'men-tote-bags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tote Bag Pria</a>
                    </div>
                </div>

                <!-- Accessories Accordion -->
                <div class="border-b border-sand pb-3">
                    <button type="button" 
                            @click="mobileAccordion.menApparel = !mobileAccordion.menApparel" 
                            class="w-full flex items-center justify-between py-2 text-body-sm font-semibold text-charcoal min-h-[44px]">
                        <span>Aksesori & Dompet</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="mobileAccordion.menApparel ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="mobileAccordion.menApparel" x-collapse class="pl-4 space-y-2.5 pt-2">
                        <a href="{{ route('collections.show', 'men-wallets') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Dompet & Cardholder</a>
                        <a href="{{ route('collections.show', 'men-pouches') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Pouch & Tech Organizer</a>
                        <a href="{{ route('collections.show', 'urban-backpacks') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Koleksi Ransel Urban</a>
                    </div>
                </div>

                <a href="{{ route('categories.men') }}" @click="mobileMenuOpen = false" class="block py-2 text-body-sm font-bold text-charcoal underline underline-offset-4">
                    Lihat Semua Produk Pria →
                </a>
            </div>

            <!-- Mobile Tab Content: WOMEN -->
            <div x-show="mobileCategoryTab === 'women'" class="p-4 space-y-4">
                <!-- Bags Accordion -->
                <div class="border-b border-sand pb-3">
                    <button type="button" 
                            @click="mobileAccordion.womenBags = !mobileAccordion.womenBags" 
                            class="w-full flex items-center justify-between py-2 text-body-sm font-semibold text-charcoal min-h-[44px]">
                        <span>Tas Wanita</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="mobileAccordion.womenBags ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="mobileAccordion.womenBags" x-collapse class="pl-4 space-y-2.5 pt-2">
                        <a href="{{ route('collections.show', 'women-tote-bags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tote Bag & Shopper</a>
                        <a href="{{ route('collections.show', 'women-shoulder-bags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tas Bahu & Shoulder Bag</a>
                        <a href="{{ route('collections.show', 'women-crossbody-bags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Tas Selempang & Crossbody</a>
                        <a href="{{ route('collections.show', 'women-handbags') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Handbag & Satchel</a>
                        <a href="{{ route('collections.show', 'women-mini-backpacks') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Ransel Modis & Mini Backpack</a>
                    </div>
                </div>

                <!-- Accessories Accordion -->
                <div class="border-b border-sand pb-3">
                    <button type="button" 
                            @click="mobileAccordion.womenApparel = !mobileAccordion.womenApparel" 
                            class="w-full flex items-center justify-between py-2 text-body-sm font-semibold text-charcoal min-h-[44px]">
                        <span>Aksesori & Dompet</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="mobileAccordion.womenApparel ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="mobileAccordion.womenApparel" x-collapse class="pl-4 space-y-2.5 pt-2">
                        <a href="{{ route('collections.show', 'women-wallets') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Dompet & Clutches</a>
                        <a href="{{ route('collections.show', 'women-pouches') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Pouch & Makeup Case</a>
                        <a href="{{ route('collections.show', 'eco-canvas') }}" @click="mobileMenuOpen = false" class="block text-body-sm text-iron hover:text-charcoal py-1">Koleksi Eco Canvas</a>
                    </div>
                </div>

                <a href="{{ route('categories.women') }}" @click="mobileMenuOpen = false" class="block py-2 text-body-sm font-bold text-charcoal underline underline-offset-4">
                    Lihat Semua Produk Wanita →
                </a>
            </div>

            <!-- Mobile Tab Content: SALE -->
            <div x-show="mobileCategoryTab === 'sale'" class="p-4 space-y-3">
                <a href="{{ route('collections.show', 'men-backpacks') }}" @click="mobileMenuOpen = false" class="block py-2.5 text-body-sm text-charcoal font-medium border-b border-sand">Diskon Tas Pria</a>
                <a href="{{ route('collections.show', 'women-crossbody-bags') }}" @click="mobileMenuOpen = false" class="block py-2.5 text-body-sm text-charcoal font-medium border-b border-sand">Diskon Tas Wanita</a>
                <a href="{{ route('collections.sale') }}" @click="mobileMenuOpen = false" class="block py-2.5 text-body-sm font-bold text-charcoal">Semua Diskon →</a>
            </div>
        </div>

        <!-- Mobile Drawer Bottom Actions -->
        <div class="p-4 border-t border-sand bg-oatMilk/30 space-y-3">
            @auth
                <div class="flex items-center justify-between">
                    <span class="text-body-sm font-medium text-charcoal truncate">{{ Auth::user()->name }}</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('account.wishlist') }}" @click="mobileMenuOpen = false" class="btn-pill-light text-caption px-3 py-1.5 flex items-center gap-1">
                            <span>♥</span> Wishlist
                        </a>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-pill-dark text-caption px-3 py-1.5">Admin</a>
                        @else
                            <a href="{{ route('account.dashboard') }}" class="btn-pill-light text-caption px-3 py-1.5">Akun</a>
                        @endif
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center text-caption uppercase tracking-wide10 text-iron py-2">
                        Keluar
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="btn-pill-dark text-center w-full">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="btn-pill-light text-center w-full">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>

    </div>

    <!-- Live Search Overlay / Modal Dialog -->
    <div x-show="searchOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-charcoal/50 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-start justify-center"
         style="display: none;">
        
        <!-- Search Dialog Card -->
        <div @click.away="searchOpen = false" 
             x-show="searchOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-98"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-98"
             class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-sand overflow-hidden relative mt-2 sm:mt-6">
            
            <!-- Search Form Header -->
            <form action="{{ route('search.index') }}" method="GET" class="relative border-b border-sand">
                <div class="flex items-center px-4 sm:px-6 py-4">
                    <svg class="w-6 h-6 text-stone flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    
                    <input type="text" 
                           name="q" 
                           x-ref="searchInput"
                           x-model="searchQuery" 
                           @input.debounce.250ms="doLiveSearch()"
                           placeholder="Cari tas fifa (contoh: Rolltop, Briefcase, Tote Bag, Crossbody)..." 
                           class="w-full text-base sm:text-lg bg-transparent text-charcoal placeholder:text-stone/70 border-none outline-none focus:ring-0">
                    
                    <!-- Clear query button -->
                    <button type="button" 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; searchResults = []; $nextTick(() => { $refs.searchInput?.focus(); })"
                            class="p-1.5 text-stone hover:text-charcoal rounded-full hover:bg-sand/30 transition mr-2 cursor-pointer"
                            title="Hapus kata kunci">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Close modal button -->
                    <button type="button" 
                            @click="searchOpen = false" 
                            class="p-2 text-stone hover:text-charcoal rounded-full hover:bg-sand/30 transition text-caption font-bold cursor-pointer"
                            aria-label="Tutup Pencarian">
                        <span class="hidden sm:inline-block mr-1 text-[11px] uppercase tracking-wider text-stone font-semibold">ESC</span>
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Search Modal Body -->
            <div class="p-4 sm:p-6 max-h-[65vh] overflow-y-auto space-y-6">
                
                <!-- Quick Search / Trending Tags (Visible when query is short) -->
                <div x-show="searchQuery.length < 2">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-stone mb-3">
                        Pencarian Populer
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Ransel Rolltop', 'Briefcase', 'Tote Bag', 'Sling Bag', 'Crescent Bag', 'Pria', 'Wanita', 'Kulit Nabati', 'Kanvas Organik', 'Travel Duffle'] as $tag)
                            <button type="button" 
                                    @click="selectTag('{{ $tag }}')"
                                    class="px-3.5 py-1.5 rounded-full bg-sand/30 hover:bg-sand text-charcoal text-body-sm font-medium transition cursor-pointer border border-sand/70">
                                {{ $tag }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="searchLoading" class="py-8 text-center text-stone flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-charcoal" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-caption">Mencari produk...</span>
                </div>

                <!-- Live Results -->
                <div x-show="!searchLoading && searchQuery.length >= 2 && searchResults.length > 0">
                    <div class="flex items-center justify-between mb-3 border-b border-sand pb-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-stone">
                            Hasil Produk (<span x-text="searchResults.length"></span>)
                        </span>
                        <a :href="'{{ route('search.index') }}?q=' + encodeURIComponent(searchQuery)" 
                           class="text-[12px] font-bold text-charcoal hover:underline">
                            Lihat Semua Hasil →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <template x-for="item in searchResults" :key="item.id">
                            <a :href="item.url" 
                               @click="searchOpen = false"
                               class="group flex items-center gap-3.5 p-3 rounded-xl hover:bg-sand/25 border border-sand/50 transition">
                                <div class="w-16 h-16 bg-[#f5f4f0] rounded-lg flex items-center justify-center flex-shrink-0 p-1 overflow-hidden">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-contain group-hover:scale-110 transition duration-300">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-[10px] uppercase tracking-wider text-stone block truncate" x-text="item.category"></span>
                                    <h4 class="text-body-sm font-semibold text-charcoal group-hover:text-black truncate" x-text="item.name"></h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-caption font-bold text-charcoal" x-text="item.price_formatted"></span>
                                        <template x-if="item.compare_at_price_formatted">
                                            <span class="text-[10px] text-stone line-through" x-text="item.compare_at_price_formatted"></span>
                                        </template>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div class="mt-4 pt-4 border-t border-sand text-center">
                        <a :href="'{{ route('search.index') }}?q=' + encodeURIComponent(searchQuery)" 
                           class="btn-pill-dark inline-block px-6 py-2.5 text-center text-body-sm font-semibold">
                            Buka Semua Hasil di Halaman Katalog
                        </a>
                    </div>
                </div>

                <!-- Empty State -->
                <div x-show="!searchLoading && searchQuery.length >= 2 && searchResults.length === 0" 
                     class="py-8 text-center text-stone">
                    <p class="text-body-sm text-charcoal font-medium">Tidak ada produk yang cocok dengan "<span x-text="searchQuery"></span>".</p>
                    <p class="text-caption text-iron mt-1">Coba gunakan kata kunci lain seperti <em>Ransel</em>, <em>Briefcase</em>, atau <em>Tote Bag</em>.</p>
                </div>

            </div>

        </div>

    </div>

</header>
