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
                        Layanan Bantuan & Panduan Belanja
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
                    Kenyamanan Alami fifa
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

                                <!-- Action Buttons / Links in Bot Message (Clean Monochrome Outline) -->
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

            <!-- Typing Indicator (Minimalist Monochrome) -->
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

        <!-- Quick Suggestions Chips (Clean Monochrome Pills) -->
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
                       placeholder="Ketik pertanyaan Anda..." 
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

    <!-- Floating Trigger Button (Minimalist Solid Charcoal) -->
    <div class="flex items-center gap-3 justify-end">
        <!-- Minimal Tooltip (shows when closed) -->
        <div x-show="!open && showTooltip" 
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="hidden sm:flex items-center bg-white text-[#212121] text-[12px] font-medium py-2 px-3.5 rounded-full shadow-md border border-[#ded8cf] gap-2">
            <span>Butuh bantuan seputar produk fifa?</span>
            <button @click.stop="showTooltip = false" class="text-stone-400 hover:text-charcoal text-xs">✕</button>
        </div>

        <button @click="open = !open; if(open) { showTooltip = false; $nextTick(() => scrollBottom()); }"
                class="group relative w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-[#212121] text-white shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 border border-white/10 hover:bg-black">
            
            <!-- Minimalist Chat / Close Icon -->
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
            { label: 'Rekomendasi Terlaris', text: 'Rekomendasi sepatu paling laris dan favorit' },
            { label: 'Panduan Ukuran', text: 'Bagaimana cara memilih ukuran sepatu yang tepat?' },
            { label: 'Material Alami', text: 'Apa saja material alami yang digunakan fifa?' },
            { label: 'Status Pengiriman', text: 'Berapa lama estimasi pengiriman dan biaya ongkir?' },
            { label: 'Garansi 30 Hari', text: 'Bagaimana ketentuan garansi uji coba 30 hari?' },
            { label: 'Koleksi Pria', text: 'Lihat koleksi sepatu untuk pria' },
            { label: 'Koleksi Wanita', text: 'Lihat koleksi sepatu untuk wanita' }
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
                    text: 'Halo, selamat datang di <strong>fifa</strong>.<br><br>Saya asisten fifa, siap membantu Anda menemukan model sepatu yang sesuai, panduan ukuran, informasi bahan alami, atau status pesanan. Ada yang bisa dibantu?',
                    links: [
                        { label: 'Sepatu Pria', url: '{{ route('categories.men') }}' },
                        { label: 'Sepatu Wanita', url: '{{ route('categories.women') }}' },
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

            // 1. Rekomendasi / Terlaris / Best Sellers
            if (q.includes('terlaris') || q.includes('rekomendasi') || q.includes('favorit') || q.includes('populer') || q.includes('best seller')) {
                return {
                    sender: 'bot',
                    text: 'Berikut adalah model sepatu favorit pilihan pelanggan fifa:<br><br>' +
                          '&bull; <strong>Tree Dasher 2</strong>: Sepatu lari responsif dan sejuk dari serat pohon eukaliptus.<br>' +
                          '&bull; <strong>Wool Runner 2</strong>: Sneakers kasual harian dari wol ZQ Merino alami.<br>' +
                          '&bull; <strong>Tree Lounger</strong>: Model slip-on santai yang sangat ringan dan praktis.',
                    links: [
                        { label: 'Lihat Semua Terlaris', url: '{{ route('collections.show', 'best-sellers') }}' },
                        { label: 'Koleksi Terbaru', url: '{{ route('collections.show', 'new-arrivals') }}' }
                    ]
                };
            }

            // 2. Ukuran / Sizing / Size Guide
            if (q.includes('ukuran') || q.includes('size') || q.includes('sempit') || q.includes('kebesaran') || q.includes('pas')) {
                return {
                    sender: 'bot',
                    text: '<strong>Panduan Memilih Ukuran fifa:</strong><br><br>' +
                          '&bull; Sebagian besar sepatu fifa berukuran standar (<em>True to Size</em>).<br>' +
                          '&bull; Untuk kaki yang lebih lebar atau berada di antara dua ukuran, disarankan untuk <strong>naik 1 ukuran</strong> (misalnya dari 41.5 ke 42).<br>' +
                          '&bull; Material wol Merino kami akan sedikit menyesuaikan dengan bentuk kaki Anda seiring pemakaian.',
                    links: [
                        { label: 'Panduan Ukuran Lengkap', url: '{{ route('pages.show', 'size-guide') }}' }
                    ]
                };
            }

            // 3. Material / Bahan Alami / Keberlanjutan
            if (q.includes('material') || q.includes('bahan') || q.includes('alami') || q.includes('wol') || q.includes('pohon') || q.includes('eukaliptus') || q.includes('tebu') || q.includes('ramah lingkungan')) {
                return {
                    sender: 'bot',
                    text: 'fifa menggunakan material alami terbarukan untuk menggantikan bahan sintetis berbasis plastik:<br><br>' +
                          '&bull; <strong>ZQ Merino Wool</strong>: Wol alami lembut, nyaman, dan tidak menimbulkan gatal.<br>' +
                          '&bull; <strong>Tree Fiber (Eukaliptus)</strong>: Serat pohon sejuk bernapas dan halus.<br>' +
                          '&bull; <strong>SweetFoam™</strong>: Sol empuk berbahan dasar tebu manis ramah lingkungan.',
                    links: [
                        { label: 'Keberlanjutan fifa', url: '{{ route('pages.show', 'sustainability') }}' }
                    ]
                };
            }

            // 4. Pengiriman / Ongkir / Estimasi
            if (q.includes('ongkir') || q.includes('kirim') || q.includes('pengiriman') || q.includes('gratis') || q.includes('ekspedisi') || q.includes('resi')) {
                return {
                    sender: 'bot',
                    text: '<strong>Informasi Pengiriman fifa:</strong><br><br>' +
                          '&bull; <strong>Gratis Ongkir</strong> untuk setiap pesanan minimal <strong>Rp 500.000</strong> ke seluruh Indonesia.<br>' +
                          '&bull; Estimasi pengiriman pulau Jawa: 1-3 hari kerja.<br>' +
                          '&bull; Luar pulau Jawa: 3-5 hari kerja.<br>' +
                          '&bull; Resi otomatis tercatat di akun setelah paket diproses.',
                    links: [
                        { label: 'Keranjang Belanja', url: '{{ route('cart.index') }}' },
                        { label: 'Status Pesanan', url: '{{ auth()->check() ? route('account.orders.index') : route('login') }}' }
                    ]
                };
            }

            // 5. Garansi / Retur / Pengembalian 30 Hari
            if (q.includes('garansi') || q.includes('retur') || q.includes('kembali') || q.includes('tukar') || q.includes('30 hari') || q.includes('uji coba')) {
                return {
                    sender: 'bot',
                    text: '<strong>Garansi Uji Coba 30 Hari:</strong><br><br>' +
                          'Nikmati garansi uji coba selama <strong>30 hari</strong>. Jika ukuran tidak sesuai atau kurang nyaman, Anda dapat mengajukan penukaran atau pengembalian dengan mudah.',
                    links: [
                        { label: 'Kebijakan Garansi & Retur', url: '{{ route('pages.show', 'faq') }}' }
                    ]
                };
            }

            // 6. Koleksi Pria
            if (q.includes('pria') || q.includes('men') || q.includes('cowok')) {
                return {
                    sender: 'bot',
                    text: 'Koleksi sepatu pria fifa mencakup sepatu lari (Tree Dasher), kasual wol (Wool Runner), serta model slip-on santai (Tree Lounger).',
                    links: [
                        { label: 'Sepatu Pria', url: '{{ route('categories.men') }}' }
                    ]
                };
            }

            // 7. Koleksi Wanita
            if (q.includes('wanita') || q.includes('women') || q.includes('cewek')) {
                return {
                    sender: 'bot',
                    text: 'Koleksi sepatu wanita fifa dirancang ringan dan fleksibel dengan palet warna alami elegan: sepatu lari, sneakers wol, dan flat slip-on.',
                    links: [
                        { label: 'Sepatu Wanita', url: '{{ route('categories.women') }}' }
                    ]
                };
            }

            // 8. Lokasi Toko
            if (q.includes('toko') || q.includes('outlet') || q.includes('store') || q.includes('lokasi') || q.includes('offline')) {
                return {
                    sender: 'bot',
                    text: 'Kunjungi toko resmi fifa untuk mencoba langsung sepatu berbahan alami kami.',
                    links: [
                        { label: 'Lokasi Toko fifa', url: '{{ route('stores.index') }}' }
                    ]
                };
            }

            // 9. Perawatan Sepatu
            if (q.includes('cuci') || q.includes('rawat') || q.includes('bersih') || q.includes('laundry')) {
                return {
                    sender: 'bot',
                    text: '<strong>Panduan Perawatan Sepatu:</strong><br><br>' +
                          '&bull; Lepaskan tali dan insole sebelum mencuci.<br>' +
                          '&bull; Dapat dicuci mesin (siklus lembut air dingin).<br>' +
                          '&bull; Cukup angin-anginkan di tempat teduh (hindari pengering panas).',
                    links: [
                        { label: 'FAQ Perawatan', url: '{{ route('pages.show', 'faq') }}' }
                    ]
                };
            }

            // Fallback default
            return {
                sender: 'bot',
                text: 'Saya dapat membantu Anda seputar rekomendasi sepatu, panduan ukuran, bahan alami, info gratis ongkir, atau garansi 30 hari fifa. Silakan pilih topik di bawah atau ketik pertanyaan Anda.',
                links: [
                    { label: 'Sepatu Pria', url: '{{ route('categories.men') }}' },
                    { label: 'Sepatu Wanita', url: '{{ route('categories.women') }}' },
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
