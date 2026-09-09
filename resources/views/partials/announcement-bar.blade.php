@php
    $rawAnnouncements = \App\Models\SiteSetting::get('announcement_text', 'Gratis ongkir untuk pesanan di atas Rp 500.000 | Garansi uji coba 30 hari untuk semua tas & ransel | 100% Bahan Alami & Ramah Lingkungan | Pengiriman cepat ke seluruh Indonesia');
    $messages = array_map('trim', explode('|', $rawAnnouncements));
@endphp

<div x-data="{
        messages: {{ json_encode($messages) }},
        activeIdx: 0,
        next() {
            this.activeIdx = (this.activeIdx + 1) % this.messages.length;
        },
        prev() {
            this.activeIdx = (this.activeIdx - 1 + this.messages.length) % this.messages.length;
        },
        init() {
            if (this.messages.length > 1) {
                setInterval(() => this.next(), 5000);
            }
        }
    }" 
    class="bg-black text-white py-2 px-4 select-none relative z-40 text-center">
    <div class="max-w-7xl mx-auto flex items-center justify-between min-h-[22px]">
        
        <!-- Prev arrow -->
        <button type="button" @click="prev()" class="text-white/60 hover:text-white p-1 focus:outline-none" aria-label="Previous announcement">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Messages text -->
        <div class="flex-1 overflow-hidden relative min-h-[20px] flex items-center justify-center">
            <template x-for="(msg, index) in messages" :key="index">
                <div x-show="activeIdx === index"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200 absolute"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="text-[12px] sm:text-[13px] font-medium tracking-tight text-white px-2">
                    <span x-text="msg"></span>
                </div>
            </template>
        </div>

        <!-- Next arrow -->
        <button type="button" @click="next()" class="text-white/60 hover:text-white p-1 focus:outline-none" aria-label="Next announcement">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>
