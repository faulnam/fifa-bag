@extends('layouts.app')

@section('title', 'fifa — Natural Materials, Sustainable Comfort')

@section('content')
    <!-- 1. Hero Editorial Lifestyle Banner (Inset Card matching fifa design) -->
    <section class="max-w-[1400px] mx-auto px-3 sm:px-6 pt-1 pb-4">
        <div class="relative w-full h-[500px] sm:h-[600px] lg:h-[680px] rounded-[24px] sm:rounded-[32px] overflow-hidden bg-[#2d2926] shadow-sm select-none">
            <!-- Hero Photography -->
            <img src="{{ asset('images/home/hero-dasher.jpg') }}" 
                 alt="All New Dasher NZ Collection" 
                 class="w-full h-full object-cover object-[62%_center] sm:object-center">

            <!-- Subtle Shadow Overlay for Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/15 to-transparent pointer-events-none"></div>

            <!-- Vertical Ribbon Divider (DASHER NZ repeating) -->
            <div class="absolute top-0 bottom-0 left-[35%] lg:left-[36%] hidden md:flex flex-col justify-between items-center bg-[#252220]/95 text-white/95 text-[11px] lg:text-[12px] font-bold tracking-[0.25em] z-10 w-9 sm:w-11 border-x border-white/10 select-none py-6">
                <span class="[writing-mode:vertical-rl] rotate-180 uppercase">
                    DASHER NZ &nbsp;&bull;&nbsp; DASHER NZ &nbsp;&bull;&nbsp; DASHER NZ &nbsp;&bull;&nbsp; DASHER NZ &nbsp;&bull;&nbsp; DASHER NZ &nbsp;&bull;&nbsp; DASHER NZ
                </span>
            </div>

            <!-- Doodle Annotation Scribble ("EVER HAVE A TREE HUG YOU BACK?") -->
            <div class="absolute left-[38%] md:left-[43%] lg:left-[45%] top-[40%] md:top-[38%] z-20 text-white select-none pointer-events-none">
                <p class="font-sans font-extrabold uppercase text-[12px] sm:text-[14px] lg:text-[16px] tracking-wider drop-shadow-md text-white max-w-[160px] sm:max-w-[190px] leading-tight">
                    EVER HAVE A TREE HUG YOU BACK?
                </p>
                <!-- Hand-drawn curved arrow doodle SVG -->
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white drop-shadow-lg mt-1 transform -rotate-12" viewBox="0 0 80 80" fill="none" stroke="currentColor">
                    <path d="M12 18 C 30 45, 45 55, 66 65 M50 67 L68 65 L64 48" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Hero Editorial Headline & CTAs (Bottom Right) -->
            <div class="absolute bottom-8 sm:bottom-12 right-4 sm:right-8 lg:right-16 z-20 text-right max-w-xl">
                <span class="block text-[11px] sm:text-[12px] font-bold uppercase tracking-[0.2em] text-white/90 mb-2 drop-shadow">
                    ALL NEW DASHER NZ COLLECTION
                </span>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-white drop-shadow-md mb-6 leading-tight">
                    Wildly Comfortable. Super Natural.
                </h1>
                <div class="flex items-center justify-end gap-3 sm:gap-4">
                    <a href="{{ route('categories.men') }}" 
                       class="bg-white text-charcoal hover:bg-neutral-100 font-sans font-bold text-[12px] sm:text-[13px] uppercase tracking-widest px-7 sm:px-9 py-3 sm:py-3.5 rounded-full shadow-lg transition">
                        SHOP MEN
                    </a>
                    <a href="{{ route('categories.women') }}" 
                       class="bg-white text-charcoal hover:bg-neutral-100 font-sans font-bold text-[12px] sm:text-[13px] uppercase tracking-widest px-7 sm:px-9 py-3 sm:py-3.5 rounded-full shadow-lg transition">
                        SHOP WOMEN
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. 4-Category Color Block Shoe Cards with Morphing Oval Hover Effect (Screenshot 2) -->
    <section class="max-w-[1400px] mx-auto px-3 sm:px-6 py-4 sm:py-8 lg:py-10">
        <div class="bg-[#ece7e1] rounded-[24px] sm:rounded-[32px] p-3 sm:p-6 lg:p-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 lg:gap-6">
                
                <!-- Card 1: Slate Blue (#5c778a) -> NEW ARRIVALS -->
                <div class="group relative bg-[#5c778a] rounded-[20px] sm:rounded-[28px] hover:rounded-full h-[240px] sm:h-[380px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-2.5 sm:p-4 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none">
                    <!-- Shoe Image (Clean Transparent PNG, Perfectly Centered & Unclipped) -->
                    <div class="w-full flex-1 flex items-center justify-center relative p-1 sm:p-2 my-auto">
                        <img src="{{ asset('images/home/cat-blue-runner.png') }}" 
                             alt="New Arrivals Shoes" 
                             class="w-auto h-auto max-w-[92%] max-h-[100px] sm:max-h-[160px] lg:max-h-[220px] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill (No blur, clean font-medium) -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:opacity-0 transition-opacity duration-300 px-2">
                        <span class="border border-white/90 text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider px-3 sm:px-5 lg:px-6 py-1 sm:py-1.5 lg:py-2 rounded-full text-center leading-none">
                            NEW ARRIVALS
                        </span>
                    </div>

                    <!-- Hover State Overlay (Reveals Title + Stacked Dual Pill Buttons) -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 sm:gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 pointer-events-auto p-2">
                        <!-- Category Title -->
                        <span class="text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider mb-0.5 sm:mb-1 text-center">
                            NEW ARRIVALS
                        </span>
                        <!-- Dual Pill Action Buttons -->
                        <a href="{{ route('collections.show', 'new-arrivals') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP MEN
                        </a>
                        <a href="{{ route('collections.show', 'new-arrivals') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP WOMEN
                        </a>
                    </div>
                </div>

                <!-- Card 2: Mocha / Warm Chocolate Espresso (#4d4341) -> MENS -->
                <div class="group relative bg-[#4d4341] rounded-[20px] sm:rounded-[28px] hover:rounded-full h-[240px] sm:h-[380px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-2.5 sm:p-4 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none">
                    <!-- Shoe Image (Clean Transparent PNG, Perfectly Centered & Unclipped) -->
                    <div class="w-full flex-1 flex items-center justify-center relative p-1 sm:p-2 my-auto">
                        <img src="{{ asset('images/home/cat-grey-sneaker.png') }}" 
                             alt="Men's Shoes" 
                             class="w-auto h-auto max-w-[92%] max-h-[100px] sm:max-h-[160px] lg:max-h-[220px] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:opacity-0 transition-opacity duration-300 px-2">
                        <span class="border border-white/90 text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider px-3 sm:px-5 lg:px-6 py-1 sm:py-1.5 lg:py-2 rounded-full text-center leading-none">
                            MENS
                        </span>
                    </div>

                    <!-- Hover State Overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 sm:gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 pointer-events-auto p-2">
                        <span class="text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider mb-0.5 sm:mb-1 text-center">
                            MENS
                        </span>
                        <a href="{{ route('categories.men') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP SHOES
                        </a>
                        <a href="{{ route('collections.show', 'men-tees-tops') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP APPAREL
                        </a>
                    </div>
                </div>

                <!-- Card 3: Dusty Mauve (#9d7370) -> WOMENS -->
                <div class="group relative bg-[#9d7370] rounded-[20px] sm:rounded-[28px] hover:rounded-full h-[240px] sm:h-[380px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-2.5 sm:p-4 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none">
                    <!-- Shoe Image (Clean Transparent PNG, Perfectly Centered & Unclipped) -->
                    <div class="w-full flex-1 flex items-center justify-center relative p-1 sm:p-2 my-auto">
                        <img src="{{ asset('images/home/cat-pink-flat.png') }}" 
                             alt="Women's Shoes" 
                             class="w-auto h-auto max-w-[92%] max-h-[100px] sm:max-h-[160px] lg:max-h-[220px] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:opacity-0 transition-opacity duration-300 px-2">
                        <span class="border border-white/90 text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider px-3 sm:px-5 lg:px-6 py-1 sm:py-1.5 lg:py-2 rounded-full text-center leading-none">
                            WOMENS
                        </span>
                    </div>

                    <!-- Hover State Overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 sm:gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 pointer-events-auto p-2">
                        <span class="text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider mb-0.5 sm:mb-1 text-center">
                            WOMENS
                        </span>
                        <a href="{{ route('categories.women') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP SHOES
                        </a>
                        <a href="{{ route('collections.show', 'women-tees-tops') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP APPAREL
                        </a>
                    </div>
                </div>

                <!-- Card 4: Sage Green (#7d8d7e) -> BEST SELLERS -->
                <div class="group relative bg-[#7d8d7e] rounded-[20px] sm:rounded-[28px] hover:rounded-full h-[240px] sm:h-[380px] lg:h-[490px] overflow-hidden flex flex-col items-center justify-center p-2.5 sm:p-4 lg:p-6 transition-all duration-500 ease-in-out cursor-pointer shadow-xs hover:shadow-xl select-none">
                    <!-- Shoe Image (Clean Transparent PNG, Perfectly Centered & Unclipped) -->
                    <div class="w-full flex-1 flex items-center justify-center relative p-1 sm:p-2 my-auto">
                        <img src="{{ asset('images/home/cat-sage-runner.png') }}" 
                             alt="Best Sellers Shoes" 
                             class="w-auto h-auto max-w-[92%] max-h-[100px] sm:max-h-[160px] lg:max-h-[220px] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Normal State Center Pill -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:opacity-0 transition-opacity duration-300 px-2">
                        <span class="border border-white/90 text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider px-3 sm:px-5 lg:px-6 py-1 sm:py-1.5 lg:py-2 rounded-full text-center leading-none">
                            BEST SELLERS
                        </span>
                    </div>

                    <!-- Hover State Overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 sm:gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 pointer-events-auto p-2">
                        <span class="text-white font-medium text-[9px] sm:text-[11px] lg:text-[12px] uppercase tracking-wider mb-0.5 sm:mb-1 text-center">
                            BEST SELLERS
                        </span>
                        <a href="{{ route('collections.show', 'best-sellers') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP MEN
                        </a>
                        <a href="{{ route('collections.show', 'best-sellers') }}" 
                           class="border border-white hover:bg-white text-white hover:text-charcoal font-medium text-[8px] sm:text-[10px] lg:text-[11px] uppercase tracking-wider px-2 sm:px-4 lg:px-5 py-1 sm:py-1.5 rounded-full transition duration-150 min-w-[76px] sm:min-w-[110px] lg:min-w-[130px] text-center leading-tight">
                            SHOP WOMEN
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
                        BEST SELLERS
                    </a>
                </div>

                <!-- Left / Right Carousel Controls -->
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            @click="scrollLeft()" 
                            :disabled="!canScrollLeft"
                            :class="canScrollLeft ? 'opacity-100 hover:bg-black hover:text-white cursor-pointer active:scale-95' : 'opacity-35 cursor-not-allowed'"
                            class="w-8 h-8 rounded-full border border-black/80 flex items-center justify-center text-black transition focus:outline-none shadow-2xs" 
                            aria-label="Previous Products">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            @click="scrollRight()" 
                            :disabled="!canScrollRight"
                            :class="canScrollRight ? 'opacity-100 hover:bg-black hover:text-white cursor-pointer active:scale-95' : 'opacity-35 cursor-not-allowed'"
                            class="w-8 h-8 rounded-full border border-black/80 flex items-center justify-center text-black transition focus:outline-none shadow-2xs" 
                            aria-label="Next Products">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Product Cards Carousel Container -->
            <div x-ref="carousel" class="flex gap-4 sm:gap-5 overflow-x-auto scrollbar-none pb-2 scroll-smooth snap-x snap-mandatory select-none">
                
                <!-- Card 1: WOMEN'S CANVAS CRUISER SLIP ON -->
                <a href="{{ route('collections.show', 'women-flats-loungers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            NEW
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-canvas-cruiser.png') }}" 
                             alt="Women's Canvas Cruiser Slip On" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WOMEN'S CANVAS CRUISER SLIP ON
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Warm White
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #e5dec5;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.190.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 2: WOMEN'S CRUISER SLIP ON -->
                <a href="{{ route('collections.show', 'women-flats-loungers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            NEW
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-cruiser-white.png') }}" 
                             alt="Women's Cruiser Slip On" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WOMEN'S CRUISER SLIP ON
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Blizzard
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block border border-[#d9d9d9]" style="background-color: #ffffff;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.590.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 3: WOMEN'S RUNNER NZ SLIP ON (Mushroom) -->
                <a href="{{ route('collections.show', 'new-arrivals') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            NEW
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-runner-beige.png') }}" 
                             alt="Women's Runner NZ Slip On" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WOMEN'S RUNNER NZ SLIP ON
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Mushroom
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #b2a290;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.690.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 4: WOMEN'S RUNNER NZ SLIP ON (Anthracite) -->
                <a href="{{ route('collections.show', 'new-arrivals') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            NEW
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/bs-runner-charcoal.png') }}" 
                             alt="Women's Runner NZ Slip On Charcoal" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WOMEN'S RUNNER NZ SLIP ON
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Anthracite
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #4a4744;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.690.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 5: MEN'S TREE DASHER 2 -->
                <a href="{{ route('collections.show', 'best-sellers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            TOP RATED
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-sage-runner.png') }}" 
                             alt="Men's Tree Dasher 2" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            MEN'S TREE DASHER 2
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Sage Green
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #7d8d7e;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 2.190.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 6: MEN'S TREE RUNNER -->
                <a href="{{ route('collections.show', 'best-sellers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            BEST SELLER
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-blue-runner.png') }}" 
                             alt="Men's Tree Runner" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            MEN'S TREE RUNNER
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Mist Blue
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #6c8299;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.550.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 7: MEN'S WOOL RUNNER 2 -->
                <a href="{{ route('collections.show', 'best-sellers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            CLASSIC
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-grey-sneaker.png') }}" 
                             alt="Men's Wool Runner 2" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            MEN'S WOOL RUNNER 2
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Natural Grey
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #5c5856;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.750.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Card 8: WOMEN'S TREE LOUNGER -->
                <a href="{{ route('collections.show', 'best-sellers') }}" 
                   class="group flex-none w-[270px] sm:w-[290px] lg:w-[305px] block bg-white rounded-[20px] p-4 sm:p-5 flex flex-col justify-between h-[390px] sm:h-[420px] shadow-2xs hover:shadow-md transition duration-300 relative select-none snap-start">
                    <!-- Top Badge -->
                    <div>
                        <span class="inline-flex items-center bg-[#f3eee8] border border-[#dfd7cc] text-[#554e45] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            ESSENTIAL
                        </span>
                    </div>

                    <!-- Centered Shoe Photography -->
                    <div class="flex-1 flex items-center justify-center my-2 overflow-hidden">
                        <img src="{{ asset('images/home/cat-pink-flat.png') }}" 
                             alt="Women's Tree Lounger" 
                             class="max-w-[95%] max-h-[170px] sm:max-h-[190px] object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Bottom Details & Price Row -->
                    <div class="pt-2">
                        <h3 class="font-sans font-bold text-[11px] sm:text-[12px] uppercase tracking-wider text-black leading-snug group-hover:underline">
                            WOMEN'S TREE LOUNGER
                        </h3>
                        <p class="text-[12px] sm:text-[13px] text-[#5c554e] font-normal mt-0.5">
                            Heather Pink
                        </p>
                        <!-- Swatch + Price Row -->
                        <div class="mt-3 pt-0.5 flex items-center justify-between">
                            <div class="w-5 h-5 rounded-full border border-[#8a8073] p-[2px] flex items-center justify-center">
                                <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: #9d7370;"></span>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-[12px] sm:text-[13px] text-black">
                                    Rp 1.490.000
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- 4. 3-Column Lifestyle Editorial Grid (Screenshot 4) -->
    <section class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
            
            <!-- Card 1: Summer Travel Essentials -->
            <a href="{{ route('collections.show', 'men-slip-ons-loungers') }}" 
               class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300">
                <img src="{{ asset('images/home/travel-slides.jpg') }}" 
                     alt="Summer Travel Essentials" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        Summer Travel<br>Essentials
                    </h2>
                </div>
            </a>

            <!-- Card 2: New Arrivals -->
            <a href="{{ route('collections.show', 'new-arrivals') }}" 
               class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300">
                <img src="{{ asset('images/home/woman-swing.jpg') }}" 
                     alt="New Arrivals Collection" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        New Arrivals
                    </h2>
                </div>
            </a>

            <!-- Card 3: Fresh Colors For Summer -->
            <a href="{{ route('collections.show', 'best-sellers') }}" 
               class="group relative rounded-[24px] sm:rounded-[28px] overflow-hidden aspect-[3/4] sm:h-[480px] lg:h-[540px] block shadow-sm hover:shadow-lg transition duration-300">
                <img src="{{ asset('images/home/summer-rocks.jpg') }}" 
                     alt="Fresh Colors For Summer" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/20 transition"></div>
                <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
                    <h2 class="font-display font-normal text-3xl sm:text-4xl text-white leading-tight drop-shadow-md">
                        Fresh Colors For<br>Summer
                    </h2>
                </div>
            </a>

        </div>
    </section>

    <!-- 5. 3 Value Proposition Cards on Oat Milk Canvas (Screenshot 5) -->
    <section class="bg-[#f5f4f0] py-16 sm:py-24 border-t border-[#e8e5dc]">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Value Card 1 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        WEAR ALL DAY COMFORT
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        Lightweight, bouncy, and wildly comfortable, fifa shoes make any outing feel effortless. Slip in, lace up, or slide them on and enjoy the comfy support.
                    </p>
                </div>

                <!-- Value Card 2 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        DESIGNED FOR EVERYDAY WEAR
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        Easy-to-wear styles made for daily routines, weekend plans, travel, and everything in between.
                    </p>
                </div>

                <!-- Value Card 3 -->
                <div class="bg-white rounded-[22px] p-8 sm:p-10 shadow-xs border border-black/5 flex flex-col justify-start">
                    <h3 class="font-sans font-bold text-[13px] sm:text-[14px] uppercase tracking-wide10 text-charcoal mb-4">
                        MATERIALS FROM THE EARTH
                    </h3>
                    <p class="text-body-sm text-iron leading-relaxed">
                        We replace petroleum-based synthetics with natural alternatives wherever we can. Like using wool, tree fiber, and sugarcane. They're soft, breathable, and better for the planet—win, win, win.
                    </p>
                </div>

            </div>
        </div>
    </section>
@endsection
