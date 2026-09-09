@props(['product'])

@php
    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $secondaryImage = $product->images->where('id', '!=', $primaryImage?->id)->first() ?? $primaryImage;
    $uniqueColors = $product->variants->unique('color_name');
    $isDiscounted = $product->compare_at_price && $product->compare_at_price > $product->base_price;
    $isNew = $product->collections->contains('slug', 'new-arrivals');
    
    // Group variants by color or take default color variants
    $defaultColor = $uniqueColors->first()?->color_name;
    $availableVariants = $defaultColor ? $product->variants->where('color_name', $defaultColor) : $product->variants;
    if ($availableVariants->isEmpty()) {
        $availableVariants = $product->variants;
    }
    $isWishlisted = auth()->check() ? auth()->user()->wishlists->contains('product_id', $product->id) : false;
@endphp

<div class="product-card-container group relative flex flex-col justify-between bg-canvas rounded-card transition duration-200"
     x-data="{
        isHovered: false,
        quickAddOpen: false,
        isWishlisted: {{ $isWishlisted ? 'true' : 'false' }},
        wishlistLoading: false,
        async toggleWishlist() {
            if (this.wishlistLoading) return;
            this.wishlistLoading = true;
            try {
                const res = await fetch('{{ route('wishlist.toggle', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                if (res.status === 401) {
                    const data = await res.json();
                    window.location.href = data.redirect_url || '{{ route('login') }}';
                    return;
                }
                const data = await res.json();
                if (data.success) {
                    this.isWishlisted = data.in_wishlist;
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            message: data.in_wishlist ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist',
                            type: 'info'
                        }
                    }));
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.wishlistLoading = false;
            }
        }
     }"
     @mouseenter="isHovered = true"
     @mouseleave="isHovered = false"
     @click.away="quickAddOpen = false">

    <!-- Image Area with Hover Swap & Floating Actions -->
    <div class="product-card-media relative block w-full aspect-square bg-[#f5f4f0] rounded-card overflow-hidden">
        
        <!-- Badges (Upper Left) -->
        <div class="absolute z-10 flex flex-col gap-1 pointer-events-none" style="top: 10px; left: 10px;">
            @if ($isDiscounted)
                <span class="inline-block bg-[#1f1f1f] text-white px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full shadow-xs leading-none">
                    DISKON
                </span>
            @elseif ($isNew)
                <span class="inline-block bg-[#e0dacf] text-[#212121] px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full shadow-xs leading-none">
                    BARU
                </span>
            @endif
        </div>

        <!-- Wishlist Button (Upper Right) -->
        <button type="button" 
                @click.stop.prevent="toggleWishlist()"
                :disabled="wishlistLoading"
                class="absolute z-20 p-2 rounded-full bg-white/95 hover:bg-white text-charcoal shadow-sm transition active:scale-125 min-w-[34px] min-h-[34px] flex items-center justify-center focus:outline-none cursor-pointer"
                style="top: 10px; right: 10px;"
                aria-label="Simpan ke Wishlist">
            <svg class="w-4 h-4 transition-transform duration-200" 
                 :class="isWishlisted ? 'fill-[#212121] text-[#212121] scale-110' : 'fill-none text-[#212121] hover:fill-[#e0dacf]'" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>

        <!-- Product Image Link -->
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full flex items-center justify-center p-3">
            <!-- Primary Image -->
            @if ($primaryImage)
                <img src="{{ $primaryImage->url }}" 
                     alt="{{ $product->name }}" 
                     loading="lazy"
                     class="w-full h-full object-contain object-center transition-all duration-300 group-hover:scale-105 {{ $secondaryImage && $secondaryImage->id !== $primaryImage->id ? 'lg:group-hover:opacity-0' : '' }}">
            @else
                <div class="w-full h-full flex items-center justify-center bg-sand/30 text-stone text-caption uppercase tracking-wide10">
                    Tanpa Gambar
                </div>
            @endif

            <!-- Secondary Image (Desktop Hover Swap Only) -->
            @if ($secondaryImage && $secondaryImage->id !== $primaryImage?->id)
                <img src="{{ $secondaryImage->url }}" 
                     alt="{{ $product->name }}" 
                     loading="lazy"
                     class="hidden lg:block absolute inset-0 w-full h-full object-contain object-center p-3 opacity-0 transition-all duration-300 lg:group-hover:opacity-100 lg:group-hover:scale-105">
            @endif
        </a>

        <!-- Quick Add Trigger Button (Appears on Hover on Desktop & Touch on Mobile) -->
        @if ($availableVariants->isNotEmpty())
            <div class="quick-add-wrap absolute inset-x-0 bottom-3.5 z-20 flex justify-center px-4"
                 :class="(isHovered || quickAddOpen) ? 'quick-add-visible' : ''">
                <button type="button" 
                        @click.stop.prevent="quickAddOpen = !quickAddOpen"
                        class="w-full max-w-[190px] py-2.5 px-4 text-center text-[11px] font-black uppercase tracking-wider rounded-full shadow-lg bg-white hover:bg-neutral-100 text-[#212121] border-2 border-[#212121] transition duration-150 flex items-center justify-center cursor-pointer select-none">
                    <span x-text="quickAddOpen ? '✕ TUTUP' : '+ TAMBAH CEPAT'">+ TAMBAH CEPAT</span>
                </button>
            </div>
        @endif

        <!-- Quick Add Size Selector Popover (Card Overlay with 4-Column Grid) -->
        @if ($availableVariants->isNotEmpty())
            <div x-show="quickAddOpen" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="quick-add-popover absolute inset-x-2.5 bottom-2.5 z-30 p-3.5 bg-white rounded-2xl border border-sand/90 shadow-2xl"
                 style="display: none;">
                <div class="flex items-center justify-between mb-2.5 pb-1 border-b border-sand/40">
                    <span class="text-[10px] font-black uppercase tracking-wider text-charcoal">PILIH UKURAN</span>
                    <button type="button" @click.stop.prevent="quickAddOpen = false" class="text-stone hover:text-charcoal text-base leading-none p-1 font-bold cursor-pointer">✕</button>
                </div>
                <div class="grid grid-cols-4 gap-1.5 max-h-36 overflow-y-auto">
                    @foreach ($availableVariants as $v)
                        <button type="button" 
                                @click.stop.prevent="$store.cart.addItem({{ $v->id }}, 1); quickAddOpen = false;"
                                {{ $v->stock_quantity <= 0 ? 'disabled' : '' }}
                                class="py-2.5 px-1 text-center text-xs font-bold rounded-lg border transition min-h-[38px] flex items-center justify-center cursor-pointer {{ $v->stock_quantity <= 0 ? 'bg-sand/30 text-stone border-sand/30 line-through cursor-not-allowed opacity-50' : 'bg-white text-charcoal border-sand/90 hover:border-charcoal hover:bg-charcoal hover:text-white shadow-xs active:scale-95' }}"
                                title="{{ $v->stock_quantity <= 0 ? 'Stok Habis' : 'Pilih ' . $v->size . ' (Stok: ' . $v->stock_quantity . ')' }}">
                            {{ $v->size }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- Product Info -->
    <div class="pt-3 pb-1 space-y-1.5 flex flex-col">
        
        <!-- Color Swatches Preview -->
        @if ($uniqueColors->isNotEmpty())
            <div class="flex items-center gap-1.5 py-1 overflow-hidden">
                @foreach ($uniqueColors->take(5) as $v)
                    <span class="w-3.5 h-3.5 rounded-full border border-stone/40 inline-block flex-shrink-0" 
                          style="background-color: {{ $v->color_hex }};"
                          title="{{ $v->color_name }}"></span>
                @endforeach
                @if ($uniqueColors->count() > 5)
                    <span class="text-[10px] text-stone">+{{ $uniqueColors->count() - 5 }}</span>
                @endif
            </div>
        @endif

        <!-- Title -->
        <a href="{{ route('products.show', $product->slug) }}" class="font-sans font-bold text-body-sm sm:text-body text-charcoal hover:underline leading-snug line-clamp-1">
            {{ $product->name }}
        </a>

        <!-- Category label -->
        <span class="text-caption text-iron line-clamp-1">
            {{ $product->category->name ?? 'Tas fifa' }}
        </span>

        <!-- Price -->
        <div class="flex items-center gap-2 pt-0.5">
            <span class="font-sans font-bold text-body-sm text-charcoal">
                Rp {{ number_format($product->base_price, 0, ',', '.') }}
            </span>
            @if ($isDiscounted)
                <span class="text-caption text-stone line-through">
                    Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}
                </span>
            @endif
        </div>

        <!-- Mobile Quick Add Button (Visible only on small screens) -->
        @if ($availableVariants->isNotEmpty())
            <div class="pt-2 lg:hidden">
                <button type="button" 
                        @click.stop.prevent="quickAddOpen = !quickAddOpen"
                        class="w-full py-2 px-3 border border-charcoal/80 rounded-full text-[11px] font-bold uppercase tracking-wider text-charcoal bg-white hover:bg-neutral-50 min-h-[38px] flex items-center justify-center cursor-pointer shadow-xs">
                    <span x-text="quickAddOpen ? '✕ Tutup Pilihan' : '+ Tambah Cepat'">+ Tambah Cepat</span>
                </button>
            </div>
        @endif

    </div>

</div>
