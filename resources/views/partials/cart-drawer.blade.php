<!-- Slide-over Cart Drawer Component (Alpine.js Store-driven) -->
<div x-cloak
     x-show="$store.cart.open"
     class="fixed inset-0 z-50 overflow-hidden" 
     aria-labelledby="cart-drawer-title" 
     role="dialog" 
     aria-modal="true"
     @keydown.window.escape="$store.cart.open = false">
    
    <!-- Backdrop Overlay -->
    <div x-show="$store.cart.open"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.open = false"
         class="fixed inset-0 bg-charcoal/50 backdrop-blur-xs transition-opacity" 
         aria-hidden="true"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex">
        <!-- Drawer Panel: Full width on mobile (375px), 420px on desktop -->
        <div x-show="$store.cart.open"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen w-full sm:max-w-md bg-canvas shadow-2xl flex flex-col justify-between">
            
            <!-- Drawer Header -->
            <div class="p-4 sm:p-6 border-b border-sand flex items-center justify-between bg-canvas">
                <div class="flex items-center gap-2">
                    <h2 id="cart-drawer-title" class="font-sans font-bold text-lg sm:text-xl text-charcoal">
                        Keranjang Belanja
                    </h2>
                    <span class="bg-sand text-charcoal text-caption font-bold px-2 py-0.5 rounded-pill" 
                          x-text="$store.cart.count + ' Item'"></span>
                </div>
                <button type="button" 
                        @click="$store.cart.open = false" 
                        class="p-2 -mr-2 text-stone hover:text-charcoal focus:outline-none min-w-[44px] min-h-[44px] flex items-center justify-center transition"
                        aria-label="Tutup Keranjang">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Free Shipping Progress Bar -->
            <div class="px-4 sm:px-6 py-3 bg-[#f7f6f2] border-b border-sand/70">
                <div class="text-caption font-medium text-charcoal flex items-center justify-between mb-1.5">
                    <template x-if="$store.cart.is_free_shipping">
                        <span class="text-charcoal font-bold flex items-center gap-1.5">
                            <span>🎉</span> Selamat! Anda Mendapatkan <strong>Gratis Ongkir</strong>
                        </span>
                    </template>
                    <template x-if="!$store.cart.is_free_shipping">
                        <span>
                            Kurang <strong class="text-charcoal" x-text="$store.cart.remaining_free_shipping_formatted"></strong> untuk <strong>Gratis Ongkir</strong>
                        </span>
                    </template>
                    <span class="text-[11px] text-stone" x-text="$store.cart.free_shipping_percent + '%'"></span>
                </div>
                <div class="w-full bg-sand/60 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-charcoal h-full transition-all duration-300 rounded-full" 
                         :style="'width: ' + $store.cart.free_shipping_percent + '%'"></div>
                </div>
            </div>

            <!-- Scrollable Items List / Empty State -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
                
                <!-- Loading State Indicator -->
                <div x-show="$store.cart.loading" class="text-center py-4 text-caption text-stone uppercase tracking-wide10 flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-charcoal" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memperbarui Keranjang...</span>
                </div>

                <!-- Empty Cart State -->
                <template x-if="$store.cart.items.length === 0">
                    <div class="text-center py-16 px-4 space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-sand/50 flex items-center justify-center text-charcoal">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="font-sans font-bold text-lg text-charcoal">Keranjang Anda Masih Kosong</h3>
                        <p class="text-body-sm text-iron max-w-xs mx-auto">
                            Temukan kenyamanan luar biasa dari bahan alami kami.
                        </p>
                        <div class="pt-4">
                            <a href="{{ route('collections.show', 'new-arrivals') }}" 
                               @click="$store.cart.open = false"
                               class="btn-pill-dark inline-block px-8 py-3 text-body-sm font-bold tracking-wide10">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </template>

                <!-- Cart Items List -->
                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="flex gap-4 p-3 bg-canvas border border-sand rounded-card relative transition">
                        
                        <!-- Product Thumbnail -->
                        <a :href="'/products/' + item.product_slug" class="w-20 h-20 sm:w-24 sm:h-24 bg-[#f5f4f0] rounded-sm overflow-hidden flex-shrink-0">
                            <img :src="item.image_url" :alt="item.product_name" class="w-full h-full object-cover object-center">
                        </a>

                        <!-- Details & Actions -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start gap-2">
                                    <a :href="'/products/' + item.product_slug" class="font-sans font-bold text-body-sm text-charcoal hover:underline line-clamp-1" x-text="item.product_name"></a>
                                    
                                    <!-- Remove Button -->
                                    <button type="button" 
                                            @click="$store.cart.removeItem(item.id)" 
                                            class="text-stone hover:text-charcoal p-1 min-w-[36px] min-h-[36px] flex items-center justify-center"
                                            title="Hapus item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Color & Size Info -->
                                <div class="flex items-center gap-2 text-caption text-iron mt-0.5">
                                    <span class="w-2.5 h-2.5 rounded-full border border-stone/40 inline-block" :style="'background-color: ' + item.color_hex"></span>
                                    <span x-text="item.color_name"></span>
                                    <span>•</span>
                                    <span>Ukuran <span x-text="item.size"></span> EU</span>
                                </div>
                            </div>

                            <!-- Price & Stepper -->
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-sand/50">
                                <span class="font-sans font-bold text-body-sm text-charcoal" x-text="item.subtotal_formatted"></span>
                                
                                <!-- Quantity Stepper (Min 44x44px clickable area) -->
                                <div class="flex items-center border border-sand rounded-pill overflow-hidden bg-sand/20">
                                    <button type="button" 
                                            @click="$store.cart.updateQty(item.id, item.qty - 1)" 
                                            class="w-8 h-8 flex items-center justify-center text-charcoal hover:bg-sand transition text-sm font-bold min-w-[36px] min-h-[36px]"
                                            aria-label="Kurangi kuantitas">
                                        -
                                    </button>
                                    <span class="w-7 text-center text-caption font-bold text-charcoal" x-text="item.qty"></span>
                                    <button type="button" 
                                            @click="$store.cart.updateQty(item.id, item.qty + 1)" 
                                            :disabled="item.qty >= item.stock_quantity"
                                            :class="item.qty >= item.stock_quantity ? 'opacity-40 cursor-not-allowed' : 'hover:bg-sand'"
                                            class="w-8 h-8 flex items-center justify-center text-charcoal transition text-sm font-bold min-w-[36px] min-h-[36px]"
                                            aria-label="Tambah kuantitas">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Max stock note -->
                            <template x-if="item.qty >= item.stock_quantity">
                                <span class="text-[10px] text-amber-700 font-medium mt-1">Maks. stok tercapai (<span x-text="item.stock_quantity"></span> pasang)</span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer (Sticky Bottom) -->
            <div x-show="$store.cart.items.length > 0" class="p-4 sm:p-6 border-t border-sand bg-canvas space-y-3">
                <!-- Subtotal -->
                <div class="flex items-center justify-between text-body-sm sm:text-body">
                    <span class="font-bold text-charcoal uppercase tracking-wide10 text-caption">Subtotal</span>
                    <span class="font-sans font-bold text-lg text-charcoal" x-text="$store.cart.subtotal_formatted"></span>
                </div>
                
                <p class="text-[11px] text-stone">
                    Ongkos kirim & diskon akan dihitung secara realtime saat checkout.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-1">
                    <a href="{{ route('checkout.index') }}" 
                       class="btn-pill-dark w-full py-4 text-center text-body-sm font-bold tracking-wide10 block min-h-[50px] shadow-sm">
                        Lanjut ke Checkout
                    </a>
                    <div class="text-center pt-1">
                        <a href="{{ route('cart.index') }}" 
                           @click="$store.cart.open = false"
                           class="text-caption font-bold uppercase tracking-wide10 text-charcoal hover:underline inline-block py-1">
                            Lihat Keranjang Lengkap →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
