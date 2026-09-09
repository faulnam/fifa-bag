<!-- Global Floating AI Chatbot Widget (fifa Assistant) - Minimalist Neutral Brand Theme -->
<div x-data="fifaChatbot()" 
     class="fixed bottom-6 right-6 z-50 select-none font-sans"
     @keydown.escape.window="open = false">
    
    <!-- Chat Window Container -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-250 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         x-cloak
         class="w-[calc(100vw-32px)] sm:w-[380px] h-[530px] max-h-[82vh] bg-[#f9f8f6] rounded-[22px] shadow-xl border border-[#ded8cf] flex flex-col overflow-hidden mb-3">
        
        <!-- Minimalist Header (Solid Charcoal) -->
        <div class="bg-[#212121] text-white px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/10 border border-white/15 flex items-center justify-center font-display italic font-bold text-base text-white">
                    f
                </div>
                <div>
                    <h3 class="font-sans font-bold text-[13px] tracking-wider uppercase text-white leading-tight">
                        Asisten fifa
                    </h3>
                    <p class="text-[11px] text-white/60 mt-0.5">
                        Panduan Tas & Layanan Bantuan
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-1.5">
                <button @click="resetChat()" 
                        title="Mulai Ulang Percakapan"
                        class="text-white/60 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <button @click="open = false" 
                        class="text-white/60 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages Track -->
        <div x-ref="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-3.5 no-scrollbar bg-[#f9f8f6]">
            <!-- Subtle Badge Header -->
            <div class="text-center my-1">
                <span class="inline-block bg-[#eae5dc] text-[#554e45] text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                    Koleksi Tas Berkelanjutan fifa
                </span>
            </div>

            <!-- Messages List -->
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    <!-- Bot Message -->
                    <template x-if="msg.sender === 'bot'">
                        <div class="flex items-start gap-2.5 max-w-[92%]">
                            <div class="w-7 h-7 rounded-full bg-[#212121] text-white flex-shrink-0 flex items-center justify-center text-[11px] font-display italic font-bold mt-0.5">
                                f
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white border border-[#e5e0d8] p-3.5 rounded-2xl rounded-tl-xs text-[13px] text-[#212121] leading-relaxed shadow-xs">
                                    <p x-html="msg.text"></p>
                                </div>

                                <!-- Action Buttons / Links in Bot Message -->
                                <template x-if="msg.links && msg.links.length > 0">
                                    <div class="flex flex-wrap gap-1.5 pt-0.5">
                                        <template x-for="(link, lIdx) in msg.links" :key="lIdx">
                                            <a :href="link.url" 
                                               class="inline-flex items-center gap-1.5 bg-white hover:bg-[#212121] text-[#212121] hover:text-white border border-[#212121]/30 hover:border-[#212121] text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition duration-150">
                                                <span x-text="link.label"></span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- User Message -->
                    <template x-if="msg.sender === 'user'">
                        <div class="flex items-end justify-end">
                            <div class="bg-[#212121] text-white p-3.5 rounded-2xl rounded-tr-xs text-[13px] leading-relaxed max-w-[85%] shadow-xs">
                                <p x-text="msg.text"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex items-start gap-2.5 max-w-[85%]">
                <div class="w-7 h-7 rounded-full bg-[#212121] text-white flex-shrink-0 flex items-center justify-center text-[11px] font-display italic font-bold mt-0.5">
                    f
                </div>
                <div class="bg-white border border-[#e5e0d8] px-3.5 py-2.5 rounded-2xl rounded-tl-xs flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#737373] animate-bounce"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#737373] animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#737373] animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Quick Suggestions Chips -->
        <div class="px-3 py-2.5 bg-[#f2eee7] border-t border-[#e5e0d8] overflow-x-auto no-scrollbar flex items-center gap-1.5 flex-nowrap">
            <template x-for="(prompt, pIdx) in quickPrompts" :key="pIdx">
                <button @click="sendUserMessage(prompt.text)" 
                        class="flex-none bg-white hover:bg-[#212121] text-[#212121] hover:text-white border border-[#d6cfc2] hover:border-[#212121] text-[11px] font-medium px-3 py-1.5 rounded-full transition whitespace-nowrap shadow-2xs">
                    <span x-text="prompt.label"></span>
                </button>
            </template>
        </div>

        <!-- Input Box -->
        <div class="p-3 bg-white border-t border-[#e5e0d8]">
            <form @submit.prevent="handleSubmit()" class="flex items-center gap-2">
                <input type="text" 
                       x-model="userInput" 
                       placeholder="Ketik pertanyaan Anda tentang tas fifa..." 
                       class="flex-1 bg-[#f7f6f2] border border-[#dcd7cc] focus:border-[#212121] focus:bg-white text-[13px] text-[#212121] rounded-full px-4 py-2 outline-none transition placeholder:text-[#8c8278]">
                <button type="submit" 
                        :disabled="!userInput.trim()"
                        :class="userInput.trim() ? 'bg-[#212121] text-white hover:bg-black' : 'bg-[#e5e0d8] text-[#a8a095] cursor-not-allowed'"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition flex-shrink-0">
                    <svg class="w-3.5 h-3.5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Trigger Button -->
    <div class="flex items-center gap-3 justify-end">
        <!-- Minimal Tooltip -->
        <div x-show="!open && showTooltip" 
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="hidden sm:flex items-center bg-white text-[#212121] text-[12px] font-medium py-2 px-3.5 rounded-full shadow-md border border-[#ded8cf] gap-2">
            <span>Butuh panduan memilih tas fifa?</span>
            <button @click.stop="showTooltip = false" class="text-stone-400 hover:text-charcoal text-xs">✕</button>
        </div>

        <button @click="open = !open; if(open) { showTooltip = false; $nextTick(() => scrollBottom()); }"
                class="group relative w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-[#212121] text-white shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 border border-white/10 hover:bg-black">
            
            <!-- Chat / Close Icon -->
            <div class="relative w-5 h-5 flex items-center justify-center">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </button>
    </div>
</div>

<script>
function fifaChatbot() {
    return {
        open: false,
        showTooltip: true,
        userInput: '',
        isTyping: false,
        messages: [],
        quickPrompts: [
            { label: 'Rekomendasi Terlaris', text: 'Rekomendasi tas paling laris dan favorit' },
            { label: 'Panduan Kapasitas', text: 'Bagaimana cara memilih ukuran dan kapasitas tas laptop yang tepat?' },
            { label: 'Material Alami', text: 'Apa saja material alami dan daur ulang yang digunakan fifa?' },
            { label: 'Status Pengiriman', text: 'Berapa lama estimasi pengiriman dan batas gratis ongkir?' },
            { label: 'Garansi 30 Hari', text: 'Bagaimana ketentuan garansi uji coba 30 hari?' },
            { label: 'Tas Pria', text: 'Lihat koleksi tas untuk pria' },
            { label: 'Tas Wanita', text: 'Lihat koleksi tas untuk wanita' }
        ],

        init() {
            this.resetChat();
            setTimeout(() => {
                this.showTooltip = false;
            }, 7000);
        },

        resetChat() {
            this.messages = [
                {
                    sender: 'bot',
                    text: 'Halo, selamat datang di <strong>fifa Bags</strong>.<br><br>Saya asisten fifa, siap membantu Anda menemukan model tas & ransel yang sesuai, panduan kapasitas laptop, informasi material ramah lingkungan, atau status pesanan. Ada yang bisa dibantu?',
                    links: [
                        { label: 'Tas Pria', url: '{{ route('categories.men') }}' },
                        { label: 'Tas Wanita', url: '{{ route('categories.women') }}' },
                        { label: 'Produk Terlaris', url: '{{ route('collections.show', 'best-sellers') }}' }
                    ]
                }
            ];
        },

        handleSubmit() {
            if (!this.userInput.trim()) return;
            const text = this.userInput;
            this.userInput = '';
            this.sendUserMessage(text);
        },

        sendUserMessage(text) {
            this.messages.push({
                sender: 'user',
                text: text
            });

            this.scrollBottom();
            this.isTyping = true;

            setTimeout(() => {
                const response = this.generateBotResponse(text);
                this.isTyping = false;
                this.messages.push(response);
                this.scrollBottom();
            }, 450);
        },

        generateBotResponse(input) {
            const q = input.toLowerCase();

            // 1. Rekomendasi / Terlaris
            if (q.includes('terlaris') || q.includes('rekomendasi') || q.includes('favorit') || q.includes('populer') || q.includes('best seller')) {
                return {
                    sender: 'bot',
                    text: 'Berikut adalah model tas favorit pilihan pelanggan fifa:<br><br>' +
                          '&bull; <strong>Commuter Rolltop 20L</strong>: Ransel tahan cuaca dengan proteksi laptop 16 inci.<br>' +
                          '&bull; <strong>Classic Canvas Tote</strong>: Tote bag kanvas katun organik beraksen kulit nabati.<br>' +
                          '&bull; <strong>Executive Leather Briefcase</strong>: Tas kerja mewah dari kulit vegan AppleSkin™.',
                    links: [
                        { label: 'Lihat Semua Terlaris', url: '{{ route('collections.show', 'best-sellers') }}' },
                        { label: 'Koleksi Terbaru', url: '{{ route('collections.show', 'new-arrivals') }}' }
                    ]
                };
            }

            // 2. Kapasitas / Laptop / Ukuran
            if (q.includes('ukuran') || q.includes('kapasitas') || q.includes('laptop') || q.includes('liter') || q.includes('muat') || q.includes('size')) {
                return {
                    sender: 'bot',
                    text: '<strong>Panduan Memilih Kapasitas Tas fifa:</strong><br><br>' +
                          '&bull; <strong>Sling & Compact (4L - 8L)</strong>: Pas untuk iPad/tablet, dompet, kunci, dan smartphone.<br>' +
                          '&bull; <strong>Daypack (18L) & Tote (16L)</strong>: Muat laptop 13-14 inci dan keperluan harian kerja.<br>' +
                          '&bull; <strong>Commuter Rolltop (20L - 25L)</strong>: Kompartemen terisolasi khusus laptop 15-16 inci.<br>' +
                          '&bull; <strong>Travel Duffle (35L)</strong>: Kapasitas pakaian 2-4 hari dengan sekat sepatu terpisah.',
                    links: [
                        { label: 'Panduan Kapasitas Lengkap', url: '{{ route('pages.show', 'size-guide') }}' }
                    ]
                };
            }

            // 3. Material / Bahan Alami
            if (q.includes('material') || q.includes('bahan') || q.includes('alami') || q.includes('kulit') || q.includes('kanvas') || q.includes('vegan') || q.includes('ramah lingkungan')) {
                return {
                    sender: 'bot',
                    text: 'fifa menggunakan material ramah bumi berkualitas tinggi:<br><br>' +
                          '&bull; <strong>Organic Cotton Canvas (GOTS)</strong>: Kanvas katun kuat dan bertekstur alami.<br>' +
                          '&bull; <strong>Bio-based Vegan Leather</strong>: Kulit sintetis ramah lingkungan dari limbah apel dan kaktus.<br>' +
                          '&bull; <strong>Recycled Ocean Polyester (RPET)</strong>: Dibuat dari limbah botol plastik daur ulang.',
                    links: [
                        { label: 'Keberlanjutan fifa', url: '{{ route('pages.show', 'sustainability') }}' }
                    ]
                };
            }

            // 4. Pengiriman / Ongkir
            if (q.includes('ongkir') || q.includes('kirim') || q.includes('pengiriman') || q.includes('gratis') || q.includes('ekspedisi') || q.includes('resi')) {
                return {
                    sender: 'bot',
                    text: '<strong>Informasi Pengiriman fifa:</strong><br><br>' +
                          '&bull; <strong>Gratis Ongkir</strong> untuk setiap pesanan minimal <strong>Rp 500.000</strong> ke seluruh Indonesia.<br>' +
                          '&bull; Estimasi pengiriman pulau Jawa: 1-3 hari kerja.<br>' +
                          '&bull; Luar pulau Jawa: 3-5 hari kerja.<br>' +
                          '&bull; Resi pengiriman otomatis tercatat di akun Anda via Biteship.',
                    links: [
                        { label: 'Keranjang Belanja', url: '{{ route('cart.index') }}' },
                        { label: 'Status Pesanan', url: '{{ auth()->check() ? route('account.orders.index') : route('login') }}' }
                    ]
                };
            }

            // 5. Garansi / Retur
            if (q.includes('garansi') || q.includes('retur') || q.includes('kembali') || q.includes('tukar') || q.includes('30 hari') || q.includes('rusak')) {
                return {
                    sender: 'bot',
                    text: '<strong>Garansi Uji Coba 30 Hari:</strong><br><br>' +
                          'Setiap tas fifa dilindungi garansi uji coba selama <strong>30 hari</strong>. Jika produk tidak cocok atau ada kendala cacat produksi, Anda dapat mengajukan penukaran atau pengembalian dana 100%.',
                    links: [
                        { label: 'Kebijakan Garansi & Retur', url: '{{ route('pages.show', 'faq') }}' }
                    ]
                };
            }

            // 6. Koleksi Pria
            if (q.includes('pria') || q.includes('men') || q.includes('cowok')) {
                return {
                    sender: 'bot',
                    text: 'Koleksi tas pria fifa mencakup Ransel Rolltop 20L, Executive Leather Briefcase, Weekend Duffle 35L, dan Technical Sling Pack.',
                    links: [
                        { label: 'Tas Pria', url: '{{ route('categories.men') }}' }
                    ]
                };
            }

            // 7. Koleksi Wanita
            if (q.includes('wanita') || q.includes('women') || q.includes('cewek')) {
                return {
                    sender: 'bot',
                    text: 'Koleksi tas wanita fifa dirancang anggun dengan material bio-leather dan kanvas: Classic Tote Bag, Crescent Hobo Shoulder Bag, dan Flap Crossbody.',
                    links: [
                        { label: 'Tas Wanita', url: '{{ route('categories.women') }}' }
                    ]
                };
            }

            // 8. Perawatan Tas
            if (q.includes('cuci') || q.includes('rawat') || q.includes('bersih') || q.includes('kotor')) {
                return {
                    sender: 'bot',
                    text: '<strong>Panduan Perawatan Tas fifa:</strong><br><br>' +
                          '&bull; Bersihkan debu dengan sikat halus kering.<br>' +
                          '&bull; Gunakan kain microfiber basah dengan sabun lembut untuk noda kanvas/kulit.<br>' +
                          '&bull; Angin-anginkan di tempat teduh (jangan dimasukkan ke mesin pengering panas).',
                    links: [
                        { label: 'Panduan Perawatan', url: '{{ route('pages.show', 'shoe-care') }}' }
                    ]
                };
            }

            // Fallback default
            return {
                sender: 'bot',
                text: 'Saya dapat membantu Anda seputar rekomendasi tas, kapasitas laptop, info material ramah lingkungan, gratis ongkir, atau garansi 30 hari fifa. Silakan pilih topik di bawah atau ketik pertanyaan Anda.',
                links: [
                    { label: 'Tas Pria', url: '{{ route('categories.men') }}' },
                    { label: 'Tas Wanita', url: '{{ route('categories.women') }}' },
                    { label: 'FAQ', url: '{{ route('pages.show', 'faq') }}' }
                ]
            };
        },

        scrollBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        }
    };
}
</script>
