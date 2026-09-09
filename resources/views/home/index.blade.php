@extends('layouts.app')

@section('title', 'fifa — Material Alami, Koleksi Tas & Ransel Berkelanjutan')

@section('content')
    <!-- 1. Hero Editorial Lifestyle Banner (Inset Card matching fifa design) -->
    <section class="max-w-[1400px] mx-auto px-3 sm:px-6 pt-1 pb-4">
        <div class="relative w-full h-[700px] min-h-[650px] sm:h-[640px] lg:h-[680px] rounded-[24px] sm:rounded-[32px] overflow-hidden bg-[#2d2926] shadow-sm select-none">
            <!-- Hero Photography: Responsive Picture for Perfect Mobile & Desktop Rendering -->
            <picture class="w-full h-full block">
                <source media="(max-width: 767px)" srcset="{{ asset('images/home/hero-bag-mobile.jpg') }}">
                <img src="{{ asset('images/home/hero-bag.jpg') }}" 
                     alt="Koleksi Terbaru Tas & Ransel fifa" 
                     class="w-full h-full object-cover object-center">
            </picture>

            <!-- Subtle Shadow Overlay for Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 via-45% to-transparent pointer-events-none"></div>

            <!-- Vertical Ribbon Divider (FIFA BAGS repeating) - Desktop Only -->
            <div class="absolute top-0 bottom-0 left-[35%] lg:left-[36%] hidden md:flex flex-col justify-between items-center bg-[#252220]/95 text-white/95 text-[11px] lg:text-[12px] font-bold tracking-[0.25em] z-10 w-9 sm:w-11 border-x border-white/10 select-none py-6">
                <span class="[writing-mode:vertical-rl] rotate-180 uppercase">
                    FIFA BAGS &nbsp;&bull;&nbsp; FIFA BAGS &nbsp;&bull;&nbsp; FIFA BAGS &nbsp;&bull;&nbsp; FIFA BAGS &nbsp;&bull;&nbsp; FIFA BAGS
                </span>
            </div>

            <!-- Radiating Sketch Lines Doodle around Backpack -->
            <div class="absolute right-[4%] sm:right-[15%] md:right-[20%] top-[30%] sm:top-[20%] md:top-[22%] z-20 pointer-events-none select-none text-white/90 drop-shadow-md">
                <svg class="w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 transform -rotate-12" viewBox="0 0 160 160" fill="none" stroke="currentColor">
                    <path d="M70 12 L85 32" stroke-width="3" stroke-linecap="round"/>
                    <path d="M100 22 L112 48" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M125 45 L132 75" stroke-width="3" stroke-linecap="round"/>
                    <path d="M135 82 L146 112" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M124 118 L138 138" stroke-width="3" stroke-linecap="round"/>
                    <path d="M94 136 L104 156" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Doodle Annotation Scribble ("PERNAH MERASAKAN KEKUATAN KANVAS ALAMI?") - Desktop Only -->
            <div class="absolute left-[38%] md:left-[43%] lg:left-[45%] top-[40%] md:top-[38%] z-20 text-white select-none pointer-events-none hidden md:block">
                <p class="font-sans font-extrabold uppercase text-[12px] sm:text-[14px] lg:text-[16px] tracking-wider drop-shadow-md text-white max-w-[170px] sm:max-w-[200px] leading-tight">
                    PERNAH MERASAKAN KEKUATAN KANVAS ALAMI?
                </p>
                <!-- Hand-drawn curved arrow doodle SVG -->
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white drop-shadow-lg mt-1 transform -rotate-12" viewBox="0 0 80 80" fill="none" stroke="currentColor">
                    <path d="M12 18 C 30 45, 45 55, 66 65 M50 67 L68 65 L64 48" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Hero Editorial Headline & CTAs (Bottom Left on Mobile, Bottom Right on Desktop) -->
            <div class="absolute bottom-8 sm:bottom-12 left-4 right-4 sm:left-auto sm:right-8 lg:right-16 z-20 text-left sm:text-right max-w-xl">
                <span class="block text-[11px] sm:text-[12px] font-bold uppercase tracking-[0.2em] text-white/90 mb-2 drop-shadow">
                    KOLEKSI TERBARU FIFA BAGS
                </span>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-white drop-shadow-md mb-5 sm:mb-6 leading-tight">
                    Desain Tangguh.<br class="hidden sm:inline"> Sepenuhnya Alami.
                </h1>
                <div class="flex items-center justify-start sm:justify-end gap-3 sm:gap-4">
                    <a href="{{ route('categories.men') }}" 
                       class="flex-1 sm:flex-none text-center bg-white text-charcoal hover:bg-neutral-100 font-sans font-bold text-[11px] sm:text-[13px] uppercase tracking-wider sm:tracking-widest px-5 sm:px-9 py-3 sm:py-3.5 rounded-full shadow-lg transition">
                        TAS PRIA
                    </a>
                    <a href="{{ route('categories.women') }}" 
                       class="flex-1 sm:flex-none text-center bg-white text-charcoal hover:bg-neutral-100 font-sans font-bold text-[11px] sm:text-[13px] uppercase tracking-wider sm:tracking-widest px-5 sm:px-9 py-3 sm:py-3.5 rounded-full shadow-lg transition">
                        TAS WANITA
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. 4-Category Color Block Bag Cards with Swipeable Carousel & Dynamic Center-Snap Morphing Oval Effect -->
    <section class="max-w-[1400px] mx-auto px-3 sm:px-6 py-4 sm:py-8 lg:py-10">
        <div class="bg-[#ece7e1] rounded-[24px] sm:rounded-[32px] p-3 sm:p-5 lg:p-8" 
             x-data="{
                 activeCat: 1,
                 checkActive() {
                     if (window.innerWidth >= 1024) {
                         this.activeCat = null;
                         return;
                     }
                     const el = this.$refs.slider;
                     if (!el) return;
                     const center = el.scrollLeft + (el.clientWidth / 2);
                     let closest = 1;
                     let minDiff = Infinity;
                     const children = el.querySelectorAll('[data-cat-card]');
                     children.forEach((card) => {
                         const cardCenter = card.offsetLeft + (card.offsetWidth / 2);
                         const diff = Math.abs(center - cardCenter);
                         if (diff < minDiff) {
                             minDiff = diff;
                             closest = parseInt(card.getAttribute('data-cat-card'));
                         }
                     });
                     this.activeCat = closest;
                 }
             }"
             x-init="$nextTick(() => { checkActive(); window.addEventListener('resize', () => checkActive()); })">
            
            <div x-ref="slider"
                 @scroll.debounce.30ms="checkActive()"
                 class="flex lg:grid lg:grid-cols-4 overflow-x-auto lg:overflow-visible gap-3 sm:gap-4 lg:gap-6 no-scrollbar snap-x snap-mandatory scroll-smooth py-1">
                
                <!-- Card 1: Slate Blue (#5c778a) -> PRODUK BARU -->
                <div data-cat-card="1"
                     @click="if (window.innerWidth < 1024) activeCat = 1"
                     :class="activeCat === 1 ? 'rounded-full ring-2 ring-white/60 shadow-xl' : 'rounded-[24px] sm:rounded-[28px] lg:hover:rounded-full'"
                     class="group relative bg-[#5c778a] flex-none w-[76vw] max-w-[320px] sm:w-[280px] lg:w-auto h-[420px] sm:h-[450px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-4 sm:p-5 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none snap-center">
                    
                    <!-- Large Centered Bag Image -->
                    <div class="w-full flex-1 flex items-center justify-center relative px-2 my-auto overflow-hidden">
                        <img src="{{ asset('images/home/cat-blue-runner.png') }}" 
                             alt="Tas Produk Terbaru fifa" 
                             class="w-auto h-auto max-w-[95%] max-h-[175px] sm:max-h-[200px] lg:max-h-[230px] object-contain drop-shadow-xl scale-110 sm:scale-115 group-hover:scale-120 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div :class="activeCat === 1 ? 'opacity-0 scale-95' : 'opacity-100 scale-100 lg:group-hover:opacity-0 lg:group-hover:scale-95'"
                         class="absolute inset-0 flex items-center justify-center pointer-events-none transition-all duration-300 px-4">
                        <span class="border border-white text-white font-semibold text-[11px] sm:text-[12px] uppercase tracking-widest px-6 py-2.5 rounded-full text-center leading-none shadow-xs backdrop-blur-2xs bg-black/10">
                            PRODUK BARU
                        </span>
                    </div>

                    <!-- Hover / Active State Overlay (Dual Action Buttons) -->
                    <div :class="activeCat === 1 ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 lg:group-hover:opacity-100 lg:group-hover:scale-100 pointer-events-none lg:group-hover:pointer-events-auto'"
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 sm:gap-2.5 transition-all duration-300 z-20 p-3 bg-black/15">
                        <span class="text-white font-extrabold text-[11px] sm:text-[12px] uppercase tracking-widest text-center drop-shadow mb-0.5">
                            PRODUK BARU
                        </span>
                        <a href="{{ route('collections.show', 'new-arrivals') }}?gender=men" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TAS PRIA
                        </a>
                        <a href="{{ route('collections.show', 'new-arrivals') }}?gender=women" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TAS WANITA
                        </a>
                    </div>
                </div>

                <!-- Card 2: Mocha / Warm Espresso (#4d4341) -> PRIA -->
                <div data-cat-card="2"
                     @click="if (window.innerWidth < 1024) activeCat = 2"
                     :class="activeCat === 2 ? 'rounded-full ring-2 ring-white/60 shadow-xl' : 'rounded-[24px] sm:rounded-[28px] lg:hover:rounded-full'"
                     class="group relative bg-[#4d4341] flex-none w-[76vw] max-w-[320px] sm:w-[280px] lg:w-auto h-[420px] sm:h-[450px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-4 sm:p-5 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none snap-center">
                    
                    <!-- Large Centered Bag Image -->
                    <div class="w-full flex-1 flex items-center justify-center relative px-2 my-auto overflow-hidden">
                        <img src="{{ asset('images/home/cat-grey-sneaker.png') }}" 
                             alt="Tas Pria fifa" 
                             class="w-auto h-auto max-w-[95%] max-h-[175px] sm:max-h-[200px] lg:max-h-[230px] object-contain drop-shadow-xl scale-110 sm:scale-115 group-hover:scale-120 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div :class="activeCat === 2 ? 'opacity-0 scale-95' : 'opacity-100 scale-100 lg:group-hover:opacity-0 lg:group-hover:scale-95'"
                         class="absolute inset-0 flex items-center justify-center pointer-events-none transition-all duration-300 px-4">
                        <span class="border border-white text-white font-semibold text-[11px] sm:text-[12px] uppercase tracking-widest px-6 py-2.5 rounded-full text-center leading-none shadow-xs backdrop-blur-2xs bg-black/10">
                            PRIA
                        </span>
                    </div>

                    <!-- Hover / Active State Overlay (Dual Action Buttons) -->
                    <div :class="activeCat === 2 ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 lg:group-hover:opacity-100 lg:group-hover:scale-100 pointer-events-none lg:group-hover:pointer-events-auto'"
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 sm:gap-2.5 transition-all duration-300 z-20 p-3 bg-black/15">
                        <span class="text-white font-extrabold text-[11px] sm:text-[12px] uppercase tracking-widest text-center drop-shadow mb-0.5">
                            PRIA
                        </span>
                        <a href="{{ route('collections.show', 'men-briefcases') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TAS KERJA
                        </a>
                        <a href="{{ route('collections.show', 'men-backpacks') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            RANSEL
                        </a>
                    </div>
                </div>

                <!-- Card 3: Dusty Mauve (#9d7370) -> WANITA -->
                <div data-cat-card="3"
                     @click="if (window.innerWidth < 1024) activeCat = 3"
                     :class="activeCat === 3 ? 'rounded-full ring-2 ring-white/60 shadow-xl' : 'rounded-[24px] sm:rounded-[28px] lg:hover:rounded-full'"
                     class="group relative bg-[#9d7370] flex-none w-[76vw] max-w-[320px] sm:w-[280px] lg:w-auto h-[420px] sm:h-[450px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-4 sm:p-5 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none snap-center">
                    
                    <!-- Large Centered Bag Image -->
                    <div class="w-full flex-1 flex items-center justify-center relative px-2 my-auto overflow-hidden">
                        <img src="{{ asset('images/home/cat-pink-flat.png') }}" 
                             alt="Tas Wanita fifa" 
                             class="w-auto h-auto max-w-[95%] max-h-[175px] sm:max-h-[200px] lg:max-h-[230px] object-contain drop-shadow-xl scale-110 sm:scale-115 group-hover:scale-120 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div :class="activeCat === 3 ? 'opacity-0 scale-95' : 'opacity-100 scale-100 lg:group-hover:opacity-0 lg:group-hover:scale-95'"
                         class="absolute inset-0 flex items-center justify-center pointer-events-none transition-all duration-300 px-4">
                        <span class="border border-white text-white font-semibold text-[11px] sm:text-[12px] uppercase tracking-widest px-6 py-2.5 rounded-full text-center leading-none shadow-xs backdrop-blur-2xs bg-black/10">
                            WANITA
                        </span>
                    </div>

                    <!-- Hover / Active State Overlay (Dual Action Buttons) -->
                    <div :class="activeCat === 3 ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 lg:group-hover:opacity-100 lg:group-hover:scale-100 pointer-events-none lg:group-hover:pointer-events-auto'"
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 sm:gap-2.5 transition-all duration-300 z-20 p-3 bg-black/15">
                        <span class="text-white font-extrabold text-[11px] sm:text-[12px] uppercase tracking-widest text-center drop-shadow mb-0.5">
                            WANITA
                        </span>
                        <a href="{{ route('collections.show', 'women-shoulder-bags') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            SHOULDER BAG
                        </a>
                        <a href="{{ route('collections.show', 'women-tote-bags') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TOTE BAG
                        </a>
                    </div>
                </div>

                <!-- Card 4: Sage Green (#7d8d7e) -> TERLARIS -->
                <div data-cat-card="4"
                     @click="if (window.innerWidth < 1024) activeCat = 4"
                     :class="activeCat === 4 ? 'rounded-full ring-2 ring-white/60 shadow-xl' : 'rounded-[24px] sm:rounded-[28px] lg:hover:rounded-full'"
                     class="group relative bg-[#7d8d7e] flex-none w-[76vw] max-w-[320px] sm:w-[280px] lg:w-auto h-[420px] sm:h-[450px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-4 sm:p-5 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none snap-center">
                    
                    <!-- Large Centered Bag Image -->
                    <div class="w-full flex-1 flex items-center justify-center relative px-2 my-auto overflow-hidden">
                        <img src="{{ asset('images/home/cat-sage-runner.png') }}" 
                             alt="Tas Terlaris fifa" 
                             class="w-auto h-auto max-w-[95%] max-h-[175px] sm:max-h-[200px] lg:max-h-[230px] object-contain drop-shadow-xl scale-110 sm:scale-115 group-hover:scale-120 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div :class="activeCat === 4 ? 'opacity-0 scale-95' : 'opacity-100 scale-100 lg:group-hover:opacity-0 lg:group-hover:scale-95'"
                         class="absolute inset-0 flex items-center justify-center pointer-events-none transition-all duration-300 px-4">
                        <span class="border border-white text-white font-semibold text-[11px] sm:text-[12px] uppercase tracking-widest px-6 py-2.5 rounded-full text-center leading-none shadow-xs backdrop-blur-2xs bg-black/10">
                            TERLARIS
                        </span>
                    </div>

                    <!-- Hover / Active State Overlay (Dual Action Buttons) -->
                    <div :class="activeCat === 4 ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 lg:group-hover:opacity-100 lg:group-hover:scale-100 pointer-events-none lg:group-hover:pointer-events-auto'"
                         class="absolute inset-0 flex flex-col items-center justify-center gap-2 sm:gap-2.5 transition-all duration-300 z-20 p-3 bg-black/15">
                        <span class="text-white font-extrabold text-[11px] sm:text-[12px] uppercase tracking-widest text-center drop-shadow mb-0.5">
                            TERLARIS
                        </span>
                        <a href="{{ route('collections.show', 'best-sellers') }}?gender=men" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TAS PRIA
                        </a>
                        <a href="{{ route('collections.show', 'best-sellers') }}?gender=women" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-4 sm:px-5 py-2 rounded-full transition duration-150 min-w-[125px] sm:min-w-[135px] text-center leading-tight backdrop-blur-2xs bg-black/20 hover:bg-white shadow-md">
                            TAS WANITA
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. Best Sellers Carousel Grid (Interactive Smooth Scrolling Carousel) -->
    <section class="max-w-[1400px] mx-auto px-3 sm:px-6 py-6 sm:py-8" 
             x-data="{
                 canScrollLeft: false,
                 canScrollRight: true,
                 scroll(direction) {
                     const container = this.$refs.carousel;
                     const scrollAmount = container.clientWidth * 0.75;
                     container.scrollBy({
                         left: direction === 'left' ? -scrollAmount : scrollAmount,
                         behavior: 'smooth'
                     });
                 },
                 checkScroll() {
                     const el = this.$refs.carousel;
                     if (!el) return;
                     this.canScrollLeft = el.scrollLeft > 15;
                     this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - 15;
                 },
                 scrollLeft() {
                     const el = this.$refs.carousel;
                     if (!el) return;
                     const step = el.firstElementChild ? (el.firstElementChild.offsetWidth + 20) : 320;
                     el.scrollBy({ left: -step, behavior: 'smooth' });
                 },
                 scrollRight() {
                     const el = this.$refs.carousel;
                     if (!el) return;
                     const step = el.firstElementChild ? (el.firstElementChild.offsetWidth + 20) : 320;
                     el.scrollBy({ left: step, behavior: 'smooth' });
                 }
             }">
        <div class="bg-[#ece7e1] rounded-[28px] sm:rounded-[32px] p-5 sm:p-7 lg:p-8">
            <!-- Section Header Row -->
            <div class="flex items-center justify-between mb-5 sm:mb-7">
                <div>
                    <a href="{{ route('collections.show', 'best-sellers') }}" 
                       class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wider text-black border-b border-black pb-0.5 hover:opacity-75 transition inline-block">
                        PRODUK TERLARIS
                    </a>
                </div>

                <!-- Left / Right Carousel Controls -->
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            @click="scrollLeft()" 
                            :disabled="!canScrollLeft"
                            :class="canScrollLeft ? 'opacity-100 hover:bg-black hover:text-white cursor-pointer active:scale-95' : 'opacity-35 cursor-not-allowed'"
                            class="w-8 h-8 rounded-full border border-black/80 flex items-center justify-center text-black transition focus:outline-none shadow-2xs" 
                            aria-label="Produk Sebelumnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="scrollRight()" 
                            :disabled="!canScrollRight"
                            :class="canScrollRight ? 'opacity-100 hover:bg-black hover:text-white cursor-pointer active:scale-95' : 'opacity-35 cursor-not-allowed'"
                            class="w-8 h-8 rounded-full border border-black/80 flex items-center justify-center text-black transition focus:outline-none shadow-2xs" 
                            aria-label="Produk Selanjutnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Product Cards Carousel Container -->
            <div x-ref="carousel" class="flex gap-4 sm:gap-5 overflow-x-auto scrollbar-none pb-2 scroll-smooth snap-x snap-mandatory select-none">
                
                <!-- Card 1: WOMEN'S CLASSIC CANVAS TOTE -->
                <a href="{{ route('products.show', 'womens-classic-canvas-tote') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            BARU
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-canvas-cruiser.png') }}" 
                             alt="Tote Bag Wanita Classic Canvas" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            TOTE BAG WANITA CLASSIC CANVAS
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Krem Hangat (Warm Cream)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #f0ece1;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.190.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 2: MEN'S COMMUTER ROLLTOP BACKPACK -->
                <a href="{{ route('products.show', 'mens-commuter-rolltop-backpack') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            FAVORIT
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-cruiser-white.png') }}" 
                             alt="Ransel Commuter Rolltop Pria" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            RANSEL COMMUTER ROLLTOP 20L
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Biru Kabut (Mist Navy)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #2b3a4a;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.450.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 3: WOMEN'S FLAP CROSSBODY BAG -->
                <a href="{{ route('products.show', 'womens-soft-mauve-crossbody') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            BARU
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-runner-beige.png') }}" 
                             alt="Tas Selempang Wanita Flap Crossbody" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            FLAP CROSSBODY BAG WANITA
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Cokelat Honey (Honey Tan)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #b28659;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.490.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 4: MEN'S WEEKEND TRAVEL DUFFLE -->
                <a href="{{ route('products.show', 'mens-weekend-travel-duffle') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            TRAVEL
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-runner-charcoal.png') }}" 
                             alt="Tas Travel Duffle Pria 35L" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WEEKEND TRAVEL DUFFLE 35L
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Hitam Arang (Charcoal Black)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #282828;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.690.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 5: MEN'S SAGE DAYPACK -->
                <a href="{{ route('products.show', 'mens-sage-commuter-daypack') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            TERLARIS
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-sage-runner.png') }}" 
                             alt="Ransel Pria Sage Canvas Daypack" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            SAGE CANVAS DAYPACK 18L
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Hijau Sage (Sage Green)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #7d8d7e;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.350.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 6: MEN'S EXECUTIVE LEATHER BRIEFCASE -->
                <a href="{{ route('products.show', 'mens-executive-leather-briefcase') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            EKSEKUTIF
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-grey-sneaker.png') }}" 
                             alt="Tas Kerja Pria Executive Leather Briefcase" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            EXECUTIVE LEATHER BRIEFCASE
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Cokelat Moka (Mocha Brown)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #4d372c;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.850.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 7: WOMEN'S CRESCENT HOBO BAG -->
                <a href="{{ route('products.show', 'womens-crescent-shoulder-bag') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            IKONIK
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/products/crescent-shoulder-cream.png') }}" 
                             alt="Tas Bahu Wanita Crescent Hobo Bag" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            CRESCENT HOBO SHOULDER BAG
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Krem Oat (Oat Cream)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #ede7dc;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.590.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 8: MEN'S URBAN TECHNICAL SLING -->
                <a href="{{ route('products.show', 'mens-urban-sling-pack') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            RINGKAS
                        </span>
                    </div>

                    <!-- Centered Bag Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/products/urban-sling-black.png') }}" 
                             alt="Tas Selempang Pria Urban Technical Sling" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Details & Price -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            URBAN TECHNICAL SLING BAG
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Hitam Dof (Matte Black)
                        </p>
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #1a1a1a;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 890.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- 4. 3-Column Lifestyle Editorial Grid with Dual CTA Buttons -->
    <section class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
            
            <!-- Card 1: Travel Essentials -->
            <div class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300 select-none">
                <img src="{{ asset('images/home/travel-slides.jpg') }}" 
                     alt="Koleksi Esensial Liburan dan Bepergian" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                
                <!-- Center Title -->
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        Koleksi Esensial<br>Liburan & Travel
                    </h2>
                </div>

                <!-- Bottom Action Buttons -->
                <div class="absolute bottom-5 sm:bottom-6 inset-x-4 sm:inset-x-6 z-20 flex items-center justify-center gap-2 sm:gap-3">
                    <a href="{{ route('collections.show', 'men-duffle-travel') }}" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS PRIA
                    </a>
                    <a href="{{ route('collections.show', 'women-shoulder-bags') }}" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS WANITA
                    </a>
                </div>
            </div>

            <!-- Card 2: New Arrivals -->
            <div class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300 select-none">
                <img src="{{ asset('images/home/woman-swing.jpg') }}" 
                     alt="Koleksi Tas Terbaru" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                
                <!-- Center Title -->
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        Koleksi<br>Terbaru
                    </h2>
                </div>

                <!-- Bottom Action Buttons -->
                <div class="absolute bottom-5 sm:bottom-6 inset-x-4 sm:inset-x-6 z-20 flex items-center justify-center gap-2 sm:gap-3">
                    <a href="{{ route('collections.show', 'new-arrivals') }}?gender=men" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS PRIA
                    </a>
                    <a href="{{ route('collections.show', 'new-arrivals') }}?gender=women" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS WANITA
                    </a>
                </div>
            </div>

            <!-- Card 3: Fresh Colors / Eco Canvas -->
            <div class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300 select-none">
                <img src="{{ asset('images/home/summer-rocks.jpg') }}" 
                     alt="Pilihan Warna Alami Segar" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                
                <!-- Center Title -->
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        Warna Alami<br>Pilihan Musim Ini
                    </h2>
                </div>

                <!-- Bottom Action Buttons -->
                <div class="absolute bottom-5 sm:bottom-6 inset-x-4 sm:inset-x-6 z-20 flex items-center justify-center gap-2 sm:gap-3">
                    <a href="{{ route('collections.show', 'best-sellers') }}?gender=men" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS PRIA
                    </a>
                    <a href="{{ route('collections.show', 'best-sellers') }}?gender=women" 
                       class="border border-white/90 text-white hover:bg-white hover:text-charcoal font-sans font-bold text-[10px] sm:text-[11px] uppercase tracking-wider px-3 sm:px-5 py-2 sm:py-2.5 rounded-full transition duration-200 shadow-md backdrop-blur-2xs bg-black/15 hover:bg-white text-center flex-1 max-w-[150px]">
                        TAS WANITA
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. 3 Value Proposition Cards on Oat Milk Canvas -->
    <section class="bg-[#f5f4f0] py-16 sm:py-24 border-t border-[#e8e5dc]">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Value Card 1 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        ERGONOMIS & NYAMAN DI PUNDAK
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        Ringan, kuat, dan dirancang dengan bantalan empuk SweetFoam® yang mendistribusikan beban secara merata di bahu Anda sepanjang hari.
                    </p>
                </div>

                <!-- Value Card 2 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        KOMPARTEMEN MULTIFUNGSI TERORGANISIR
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        Dilengkapi slot laptop terproteksi, saku rahasia anti-pencurian, dan ritsleting weatherproof untuk perlindungan maksimal barang berharga Anda.
                    </p>
                </div>

                <!-- Value Card 3 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        MATERIAL DARI ALAM BUMI
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        Kami mengganti bahan sintetis konvensional dengan kanvas katun organik, kulit nabati bio-leather (AppleSkin™), dan serat daur ulang botol laut ramah lingkungan.
                    </p>
                </div>

            </div>
        </div>
    </section>
@endsection
