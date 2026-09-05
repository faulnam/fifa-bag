@extends('layouts.app')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" 
     x-data="{
        step: 1,
        isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
        hasSavedAddresses: {{ $addresses->isNotEmpty() ? 'true' : 'false' }},
        useNewAddress: {{ $addresses->isEmpty() ? 'true' : 'false' }},
        selectedAddressId: {{ $defaultAddress ? $defaultAddress->id : 'null' }},
        
        // Address form state
        addressForm: {
            recipient_name: '{{ old('recipient_name', $defaultAddress->recipient_name ?? Auth::user()?->name ?? '') }}',
            phone: '{{ old('phone', $defaultAddress->phone ?? Auth::user()?->phone ?? '') }}',
            province: '{{ old('province', $defaultAddress->province ?? '') }}',
            city: '{{ old('city', $defaultAddress->city ?? '') }}',
            district: '{{ old('district', $defaultAddress->district ?? '') }}',
            postal_code: '{{ old('postal_code', $defaultAddress->postal_code ?? '') }}',
            address_line: '{{ old('address_line', $defaultAddress->address_line ?? '') }}',
            biteship_area_id: '{{ old('biteship_area_id', $defaultAddress->biteship_area_id ?? '') }}',
        },

        // Autocomplete search state
        areaSearchQuery: '{{ $defaultAddress ? ($defaultAddress->district . ', ' . $defaultAddress->city . ', ' . $defaultAddress->province) : '' }}',
        areaList: [],
        areaLoading: false,
        areaDropdownOpen: false,
        areaError: '',

        // Shipping rates state
        rates: [],
        selectedRate: null,
        ratesLoading: false,
        ratesError: null,
        ratesErrorCode: null,
        isFreeShipping: {{ $cartSummary['is_free_shipping'] ? 'true' : 'false' }},
        freeShippingThreshold: {{ $cartSummary['free_shipping_threshold'] }},

        // Order processing state
        isPlacingOrder: false,
        isSimulating: false,

        // Addresses list from backend
        savedAddresses: @js($addresses),

        init() {
            if (this.selectedAddressId && this.savedAddresses.length > 0) {
                const addr = this.savedAddresses.find(a => a.id === this.selectedAddressId);
                if (addr) {
                    this.setAddressFromSaved(addr);
                }
            }
        },

        setAddressFromSaved(addr) {
            this.selectedAddressId = addr.id;
            this.addressForm = {
                recipient_name: addr.recipient_name,
                phone: addr.phone,
                province: addr.province,
                city: addr.city,
                district: addr.district,
                postal_code: addr.postal_code,
                address_line: addr.address_line,
                biteship_area_id: addr.biteship_area_id || 'IDNP6IDNC148IDND843IDZ12250'
            };
            this.areaSearchQuery = `${addr.district}, ${addr.city}, ${addr.province}`;
        },

        async searchArea() {
            const q = this.areaSearchQuery.trim();
            if (q.length < 3) {
                this.areaList = [];
                this.areaDropdownOpen = false;
                this.areaError = q.length > 0 ? 'Ketik minimal 3 karakter...' : '';
                return;
            }

            this.areaLoading = true;
            this.areaError = '';
            try {
                const res = await fetch(`/shipping/areas?query=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success && data.areas.length > 0) {
                    this.areaList = data.areas;
                    this.areaDropdownOpen = true;
                } else {
                    this.areaList = [];
                    this.areaDropdownOpen = true;
                    this.areaError = 'Area tidak ditemukan. Silakan cek ejaan nama kecamatan atau kota.';
                }
            } catch (e) {
                this.areaError = 'Gagal memuat area. Silakan coba lagi.';
            } finally {
                this.areaLoading = false;
            }
        },

        selectArea(area) {
            this.addressForm.biteship_area_id = area.id;
            this.addressForm.province = area.province;
            this.addressForm.city = area.city;
            this.addressForm.district = area.district;
            this.addressForm.postal_code = area.postal_code || '';
            this.areaSearchQuery = `${area.district}, ${area.city}, ${area.province}`;
            this.areaDropdownOpen = false;
            this.areaList = [];
        },

        validateStep1() {
            if (!this.addressForm.recipient_name || !this.addressForm.phone || !this.addressForm.address_line) {
                alert('Silakan lengkapi nama penerima, nomor telepon, dan alamat lengkap.');
                return false;
            }
            if (!this.addressForm.province || !this.addressForm.city || !this.addressForm.district) {
                alert('Silakan pilih kecamatan/kota dari hasil pencarian Biteship.');
                return false;
            }
            return true;
        },

        async goToStep2() {
            if (!this.validateStep1()) return;
            this.step = 2;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            await this.fetchShippingRates();
        },

        async fetchShippingRates() {
            this.ratesLoading = true;
            this.ratesError = null;
            this.ratesErrorCode = null;
            this.rates = [];
            this.selectedRate = null;

            const areaId = this.addressForm.biteship_area_id || 'IDNP6IDNC148IDND859';

            try {
                const res = await fetch('/checkout/rates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ destination_area_id: areaId })
                });

                const data = await res.json();
                if (data.success && data.rates && data.rates.length > 0) {
                    this.rates = data.rates;
                    this.isFreeShipping = data.is_free_shipping;
                    // Auto select the first / cheapest courier rate
                    this.selectedRate = this.rates[0];
                } else {
                    this.ratesError = data.error || 'Tidak ada layanan kurir yang tersedia untuk area ini.';
                    this.ratesErrorCode = data.error_code || 'NO_COURIERS';
                }
            } catch (e) {
                this.ratesError = 'Terjadi kesalahan koneksi saat memuat tarif pengiriman. Silakan coba lagi.';
                this.ratesErrorCode = 'API_ERROR';
            } finally {
                this.ratesLoading = false;
            }
        },

        selectCourierRate(rate) {
            this.selectedRate = rate;
        },

        async goToStep3() {
            if (!this.selectedRate) {
                alert('Silakan pilih salah satu layanan kurir pengiriman.');
                return;
            }

            try {
                const res = await fetch('/checkout/save-shipping', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        address: this.addressForm,
                        shipping: this.selectedRate
                    })
                });

                const data = await res.json();
                if (data.success) {
                    this.step = 3;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (e) {
                alert('Gagal menyimpan pilihan pengiriman. Silakan coba lagi.');
            }
        },

        async placeOrderAndPay() {
            if (this.isPlacingOrder) return;
            if (!this.selectedRate) {
                alert('Opsi pengiriman belum dipilih.');
                return;
            }

            this.isPlacingOrder = true;

            try {
                const res = await fetch('/checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        address: this.addressForm,
                        shipping: this.selectedRate
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || 'Gagal membuat pesanan. Silakan periksa kembali keranjang belanja Anda.');
                    this.isPlacingOrder = false;
                    return;
                }

                // If Snap token is returned, trigger Snap popup
                if (data.snap_token && typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = data.success_url;
                        },
                        onPending: function(result) {
                            window.location.href = data.success_url;
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal atau dibatalkan.');
                            window.location.href = data.success_url;
                        },
                        onClose: function() {
                            // User closed popup without paying -> redirect to order confirmation page
                            window.location.href = data.success_url;
                        }
                    });
                } else {
                    // Direct redirect if snap script is offline or mock token
                    window.location.href = data.success_url;
                }
            } catch (e) {
                alert('Terjadi kesalahan saat memproses pesanan: ' + e.message);
                this.isPlacingOrder = false;
            }
        },

        async placeOrderAndSimulatePay() {
            if (this.isPlacingOrder || this.isSimulating) return;
            if (!this.selectedRate) {
                alert('Opsi pengiriman belum dipilih.');
                return;
            }

            this.isSimulating = true;

            try {
                const res = await fetch('/checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        address: this.addressForm,
                        shipping: this.selectedRate,
                        simulate_success: true
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    alert(data.message || 'Gagal memproses simulasi pesanan.');
                    this.isSimulating = false;
                    return;
                }

                window.location.href = data.success_url;
            } catch (e) {
                alert('Terjadi kesalahan saat simulasi pembayaran: ' + e.message);
                this.isSimulating = false;
            }
        },

        get effectiveShippingCost() {
            if (!this.selectedRate) return 0;
            if (this.isFreeShipping && (this.selectedRate.type === 'reguler' || this.selectedRate.courier_service_code.toLowerCase().includes('reg') || this.selectedRate.courier_service_code.toLowerCase().includes('siuntung') || this.selectedRate.courier_service_code.toLowerCase().includes('ez'))) {
                return 0;
            }
            return this.selectedRate.price;
        },

        get grandTotal() {
            const subtotal = {{ $cartSummary['subtotal'] }};
            return subtotal + this.effectiveShippingCost;
        },

        formatRupiah(val) {
            return 'Rp ' + (new Intl.NumberFormat('id-ID').format(val));
        }
     }">

    <!-- Checkout Top Progress Indicator -->
    <div class="mb-8 border-b border-sand pb-6">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between">
                
                <!-- Step 1: Alamat -->
                <button type="button" 
                        @click="if(step > 1 && !isPlacingOrder) step = 1"
                        :class="step >= 1 ? 'text-charcoal font-bold' : 'text-stone'"
                        class="flex items-center gap-2 focus:outline-none min-h-[44px]">
                    <span :class="step === 1 ? 'bg-charcoal text-canvas' : (step > 1 ? 'bg-green-700 text-white' : 'bg-sand text-stone')"
                          class="w-7 h-7 rounded-full flex items-center justify-center text-caption font-bold transition">
                        <template x-if="step > 1"><span>✓</span></template>
                        <template x-if="step <= 1"><span>1</span></template>
                    </span>
                    <span class="text-caption uppercase tracking-wide10 sm:inline-block">1. Alamat</span>
                </button>

                <div class="flex-1 h-[2px] mx-3" :class="step >= 2 ? 'bg-charcoal' : 'bg-sand'"></div>

                <!-- Step 2: Pengiriman -->
                <button type="button" 
                        @click="if(step > 2 && !isPlacingOrder) step = 2"
                        :class="step >= 2 ? 'text-charcoal font-bold' : 'text-stone'"
                        class="flex items-center gap-2 focus:outline-none min-h-[44px]">
                    <span :class="step === 2 ? 'bg-charcoal text-canvas' : (step > 2 ? 'bg-green-700 text-white' : 'bg-sand text-stone')"
                          class="w-7 h-7 rounded-full flex items-center justify-center text-caption font-bold transition">
                        <template x-if="step > 2"><span>✓</span></template>
                        <template x-if="step <= 2"><span>2</span></template>
                    </span>
                    <span class="text-caption uppercase tracking-wide10 sm:inline-block">2. Pengiriman</span>
                </button>

                <div class="flex-1 h-[2px] mx-3" :class="step >= 3 ? 'bg-charcoal' : 'bg-sand'"></div>

                <!-- Step 3: Pembayaran -->
                <button type="button" 
                        :class="step === 3 ? 'text-charcoal font-bold' : 'text-stone'"
                        class="flex items-center gap-2 focus:outline-none min-h-[44px]">
                    <span :class="step === 3 ? 'bg-charcoal text-canvas' : 'bg-sand text-stone'"
                          class="w-7 h-7 rounded-full flex items-center justify-center text-caption font-bold transition">
                        3
                    </span>
                    <span class="text-caption uppercase tracking-wide10 sm:inline-block">3. Pembayaran</span>
                </button>

            </div>
        </div>
    </div>

    <!-- Main Checkout 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start pb-28 lg:pb-8">
        
        <!-- Left Column: Multi-Step Interactive Form (8 Cols) -->
        <div class="lg:col-span-8">
            
            <!-- STEP 1: ALAMAT PENGIRIMAN -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-canvas border border-sand rounded-card p-5 sm:p-8 space-y-6 shadow-xs">
                    <div class="border-b border-sand pb-4">
                        <h2 class="font-sans font-bold text-xl text-charcoal">
                            1. Alamat Pengiriman
                        </h2>
                        <p class="text-body-sm text-iron mt-1">
                            Pilih alamat tersimpan atau masukkan alamat tujuan pengiriman pesanan Anda.
                        </p>
                    </div>

                    <!-- Option to pick from saved addresses if logged in -->
                    <template x-if="isLoggedIn && hasSavedAddresses">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">Pilih Alamat Tersimpan:</span>
                                <button type="button" 
                                        @click="useNewAddress = !useNewAddress" 
                                        class="text-caption font-bold uppercase tracking-wide10 text-charcoal hover:underline">
                                    <span x-text="useNewAddress ? '← Gunakan Alamat Tersimpan' : '+ Gunakan Alamat Baru'"></span>
                                </button>
                            </div>

                            <!-- Saved Addresses List -->
                            <div x-show="!useNewAddress" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="addr in savedAddresses" :key="addr.id">
                                    <div @click="setAddressFromSaved(addr)"
                                         :class="selectedAddressId === addr.id ? 'border-charcoal ring-2 ring-charcoal bg-sand/15' : 'border-sand hover:border-stone'"
                                         class="border rounded-card p-4 cursor-pointer transition flex flex-col justify-between relative">
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-sans font-bold text-body-sm text-charcoal" x-text="addr.label"></span>
                                                    <template x-if="addr.is_default">
                                                        <span class="bg-charcoal text-canvas text-[9px] uppercase tracking-wide10 font-bold px-1.5 py-0.5 rounded-pill">Utama</span>
                                                    </template>
                                                </div>
                                                <input type="radio" 
                                                       name="saved_address_radio" 
                                                       :value="addr.id" 
                                                       :checked="selectedAddressId === addr.id" 
                                                       class="text-charcoal focus:ring-0">
                                            </div>
                                            <p class="text-body-sm font-medium text-charcoal" x-text="addr.recipient_name"></p>
                                            <p class="text-caption text-iron mt-1 line-clamp-2" x-text="addr.address_line"></p>
                                            <p class="text-[11px] text-stone mt-1" x-text="addr.district + ', ' + addr.city + ' ' + addr.postal_code"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- New Address Form (Shown if guest, no saved address, or toggle clicked) -->
                    <div x-show="!isLoggedIn || !hasSavedAddresses || useNewAddress" class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       x-model="addressForm.recipient_name" 
                                       placeholder="Nama lengkap penerima" 
                                       class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       x-model="addressForm.phone" 
                                       placeholder="081234567890" 
                                       class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
                            </div>
                        </div>

                        <!-- Autocomplete Area Search -->
                        <div class="relative" @click.away="areaDropdownOpen = false">
                            <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                                Kecamatan, Kota, atau Provinsi <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="relative">
                                <input type="text" 
                                       x-model="areaSearchQuery" 
                                       @input.debounce.300ms="searchArea()"
                                       @focus="if(areaList.length > 0) areaDropdownOpen = true"
                                       placeholder="Ketik minimal 3 karakter (misal: Kebayoran, Bandung, Surabaya)..." 
                                       class="input-clean text-body-sm w-full pr-10 rounded-sm border-sand focus:border-charcoal focus:ring-0"
                                       autocomplete="off">

                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-stone">
                                    <template x-if="areaLoading">
                                        <svg class="animate-spin h-4 w-4 text-charcoal" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!areaLoading">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </template>
                                </div>
                            </div>

                            <!-- Autocomplete Dropdown -->
                            <div x-show="areaDropdownOpen" 
                                 x-transition 
                                 class="absolute left-0 right-0 top-full mt-1 bg-canvas border border-sand rounded-card shadow-lg z-50 max-h-60 overflow-y-auto divide-y divide-sand/50"
                                 style="display: none;">
                                <template x-if="areaError">
                                    <div class="p-3 text-caption text-stone text-center" x-text="areaError"></div>
                                </template>
                                <template x-for="area in areaList" :key="area.id">
                                    <button type="button" 
                                            @click="selectArea(area)" 
                                            class="w-full text-left p-3 hover:bg-sand/30 transition flex items-center justify-between group">
                                        <div>
                                            <div class="text-body-sm font-bold text-charcoal group-hover:underline" x-text="area.district + ', ' + area.city"></div>
                                            <div class="text-caption text-iron" x-text="area.province + (area.postal_code ? ' • Kode Pos ' + area.postal_code : '')"></div>
                                        </div>
                                        <span class="text-[10px] text-stone group-hover:text-charcoal font-bold uppercase tracking-wide10">Pilih →</span>
                                    </button>
                                </template>
                            </div>

                            <template x-if="addressForm.biteship_area_id">
                                <div class="mt-1 flex items-center gap-1.5 text-caption text-green-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Area tujuan terverifikasi Biteship</span>
                                </div>
                            </template>
                        </div>

                        <!-- Kode Pos & Alamat Lengkap -->
                        <div>
                            <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                                Kode Pos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="addressForm.postal_code" 
                                   placeholder="Contoh: 12190" 
                                   class="input-clean text-body-sm w-full sm:w-1/2 rounded-sm border-sand focus:border-charcoal focus:ring-0">
                        </div>

                        <div>
                            <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                                Alamat Lengkap & Patokan <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="addressForm.address_line" 
                                      rows="3" 
                                      placeholder="Nama jalan, nomor rumah/gedung, RT/RW, patokan" 
                                      class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0"></textarea>
                        </div>
                    </div>

                    <!-- Desktop Next Button (Step 1) -->
                    <div class="hidden lg:flex justify-end pt-4 border-t border-sand">
                        <button type="button" 
                                @click="goToStep2()" 
                                class="btn-pill-dark px-8 py-3.5 text-caption font-bold tracking-wide10">
                            Lanjut ke Pilihan Kurir →
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: PILIHAN KURIR & ONGKIR -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                <div class="bg-canvas border border-sand rounded-card p-5 sm:p-8 space-y-6 shadow-xs">
                    
                    <div class="flex items-center justify-between border-b border-sand pb-4">
                        <div>
                            <h2 class="font-sans font-bold text-xl text-charcoal">
                                2. Opsi Pengiriman
                            </h2>
                            <p class="text-body-sm text-iron mt-1">
                                Tarif kurir dihitung secara realtime dari Biteship ke <strong class="text-charcoal" x-text="addressForm.district + ', ' + addressForm.city"></strong>.
                            </p>
                        </div>
                        <button type="button" 
                                @click="step = 1" 
                                class="text-caption font-bold uppercase tracking-wide10 text-charcoal hover:underline">
                            ← Ubah Alamat
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div x-show="ratesLoading" class="text-center py-12 space-y-4">
                        <svg class="animate-spin h-8 w-8 text-charcoal mx-auto" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <p class="text-body-sm font-medium text-charcoal">Menghitung tarif ongkos kirim terbaik dari Biteship...</p>
                    </div>

                    <!-- Error Handling / Edge Case 1: Kurir Tidak Tersedia -->
                    <div x-show="!ratesLoading && ratesErrorCode === 'NO_COURIERS'" class="p-6 bg-sand/30 border border-sand rounded-card text-center space-y-4">
                        <div class="w-12 h-12 mx-auto rounded-full bg-sand flex items-center justify-center text-charcoal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="font-sans font-bold text-lg text-charcoal">Layanan Kurir Tidak Tersedia</h3>
                        <p class="text-body-sm text-iron max-w-md mx-auto" x-text="ratesError"></p>
                        <div class="pt-2">
                            <button type="button" @click="step = 1" class="btn-pill-dark px-6 py-2.5 text-caption">
                                ← Pilih Alamat Lain
                            </button>
                        </div>
                    </div>

                    <!-- Error Handling / Edge Case 2: API Error / Timeout -->
                    <div x-show="!ratesLoading && (ratesErrorCode === 'API_ERROR' || ratesErrorCode === 'TIMEOUT')" class="p-6 bg-red-50 border border-red-200 rounded-card text-center space-y-4">
                        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 flex items-center justify-center text-red-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-sans font-bold text-lg text-red-800">Gagal Memuat Tarif Kurir</h3>
                        <p class="text-body-sm text-red-700 max-w-md mx-auto" x-text="ratesError"></p>
                        <div class="pt-2 flex justify-center gap-3">
                            <button type="button" @click="fetchShippingRates()" class="btn-pill-dark px-6 py-2.5 text-caption flex items-center gap-2">
                                <span>↻</span> Coba Lagi (Retry)
                            </button>
                            <button type="button" @click="step = 1" class="btn-pill-light px-6 py-2.5 text-caption">
                                Ubah Alamat
                            </button>
                        </div>
                    </div>

                    <!-- Courier Selection List -->
                    <div x-show="!ratesLoading && rates.length > 0" class="space-y-3">
                        
                        <!-- Free Shipping Promotion Banner -->
                        <template x-if="isFreeShipping">
                            <div class="p-3.5 bg-green-50 border border-green-200 rounded-sm text-green-900 text-body-sm flex items-center gap-2">
                                <span>🎉</span>
                                <span>Selamat! Pesanan Anda memenuhi syarat <strong>Gratis Ongkir</strong> untuk layanan reguler.</span>
                            </div>
                        </template>

                        <div class="grid grid-cols-1 gap-3">
                            <template x-for="rate in rates" :key="rate.courier_code + '_' + rate.courier_service_code">
                                <div @click="selectCourierRate(rate)"
                                     :class="selectedRate && selectedRate.courier_code === rate.courier_code && selectedRate.courier_service_code === rate.courier_service_code ? 'border-charcoal ring-2 ring-charcoal bg-sand/15' : 'border-sand hover:border-stone bg-canvas'"
                                     class="border rounded-card p-4 cursor-pointer transition flex items-center justify-between">
                                    
                                    <div class="flex items-center gap-3.5">
                                        <input type="radio" 
                                               name="courier_radio" 
                                               :checked="selectedRate && selectedRate.courier_code === rate.courier_code && selectedRate.courier_service_code === rate.courier_service_code"
                                               class="text-charcoal focus:ring-0">

                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-sans font-bold text-body-sm text-charcoal" x-text="rate.courier_name"></span>
                                                <span class="text-caption text-iron font-medium" x-text="rate.courier_service_name"></span>
                                                <span class="bg-sand text-charcoal text-[9px] uppercase tracking-wide10 font-bold px-1.5 py-0.5 rounded-pill" x-text="rate.type"></span>
                                            </div>
                                            <p class="text-caption text-stone mt-0.5">
                                                Estimasi tiba: <strong class="text-iron" x-text="rate.duration"></strong>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Price / Free Shipping display -->
                                    <div class="text-right">
                                        <template x-if="isFreeShipping && (rate.type === 'reguler' || rate.courier_service_code.toLowerCase().includes('reg') || rate.courier_service_code.toLowerCase().includes('siuntung') || rate.courier_service_code.toLowerCase().includes('ez'))">
                                            <div>
                                                <span class="text-caption text-stone line-through block" x-text="rate.price_formatted"></span>
                                                <span class="text-body-sm font-bold text-green-700 uppercase">GRATIS</span>
                                            </div>
                                        </template>
                                        <template x-if="!(isFreeShipping && (rate.type === 'reguler' || rate.courier_service_code.toLowerCase().includes('reg') || rate.courier_service_code.toLowerCase().includes('siuntung') || rate.courier_service_code.toLowerCase().includes('ez')))">
                                            <span class="font-sans font-bold text-body text-charcoal" x-text="rate.price_formatted"></span>
                                        </template>
                                    </div>

                                </div>
                            </template>
                        </div>

                        <!-- Desktop Next Button (Step 2) -->
                        <div class="hidden lg:flex justify-between items-center pt-6 border-t border-sand">
                            <button type="button" @click="step = 1" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal">
                                ← Kembali ke Alamat
                            </button>
                            <button type="button" 
                                    @click="goToStep3()" 
                                    :disabled="!selectedRate"
                                    :class="!selectedRate ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="btn-pill-dark px-8 py-3.5 text-caption font-bold tracking-wide10">
                                Lanjut ke Pembayaran →
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <!-- STEP 3: PEMBAYARAN & ORDER REVIEW -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                <div class="bg-canvas border border-sand rounded-card p-5 sm:p-8 space-y-6 shadow-xs">
                    
                    <div class="flex items-center justify-between border-b border-sand pb-4">
                        <div>
                            <h2 class="font-sans font-bold text-xl text-charcoal">
                                3. Konfirmasi & Pembayaran
                            </h2>
                            <p class="text-body-sm text-iron mt-1">
                                Tinjau pesanan Anda sebelum melanjutkan ke gerbang pembayaran online Midtrans Snap.
                            </p>
                        </div>
                        <button type="button" 
                                @click="step = 2" 
                                :disabled="isPlacingOrder"
                                class="text-caption font-bold uppercase tracking-wide10 text-charcoal hover:underline">
                            ← Ubah Kurir
                        </button>
                    </div>

                    <!-- Summary of Address & Courier chosen -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-sand/20 border border-sand rounded-card p-4 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">Alamat Pengiriman</span>
                                <button type="button" @click="if(!isPlacingOrder) step = 1" class="text-[11px] font-bold text-charcoal underline">Ubah</button>
                            </div>
                            <p class="text-body-sm font-medium text-charcoal" x-text="addressForm.recipient_name + ' (' + addressForm.phone + ')'"></p>
                            <p class="text-caption text-iron" x-text="addressForm.address_line"></p>
                            <p class="text-caption text-stone" x-text="addressForm.district + ', ' + addressForm.city + ', ' + addressForm.province + ' ' + addressForm.postal_code"></p>
                        </div>

                        <div class="bg-sand/20 border border-sand rounded-card p-4 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">Metode Pengiriman</span>
                                <button type="button" @click="if(!isPlacingOrder) step = 2" class="text-[11px] font-bold text-charcoal underline">Ubah</button>
                            </div>
                            <template x-if="selectedRate">
                                <div>
                                    <p class="text-body-sm font-medium text-charcoal" x-text="selectedRate.courier_name + ' - ' + selectedRate.courier_service_name"></p>
                                    <p class="text-caption text-iron" x-text="'Estimasi Tiba: ' + selectedRate.duration"></p>
                                    <p class="text-caption font-bold text-charcoal pt-1" x-text="effectiveShippingCost === 0 ? 'GRATIS ONGKIR' : formatRupiah(effectiveShippingCost)"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Supported Payment Methods Grid -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between border-b border-sand pb-2">
                            <div>
                                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal block">Metode Pembayaran Yang Didukung</span>
                                <p class="text-caption text-iron mt-0.5">Semua transaksi diproses secara aman & terverifikasi otomatis via Midtrans Gateway</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-pill border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Online & Aktif
                            </span>
                        </div>

                        <!-- Category 1: Virtual Account -->
                        <div class="space-y-2">
                            <span class="text-[11px] font-bold uppercase tracking-wide10 text-stone block">1. Virtual Account (Transfer Bank)</span>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2.5">
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA Virtual Account" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">BCA VA</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri Bill Payment" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">Mandiri</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/bni.svg') }}" alt="BNI Virtual Account" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">BNI VA</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/bri.svg') }}" alt="BRI BRIVA" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">BRIVA</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/permata.svg') }}" alt="Permata Virtual Account" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">Permata</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/cimb.svg') }}" alt="CIMB Niaga VA" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">CIMB Niaga</span>
                                </div>
                            </div>
                        </div>

                        <!-- Category 2: E-Wallet & QRIS -->
                        <div class="space-y-2 pt-1">
                            <span class="text-[11px] font-bold uppercase tracking-wide10 text-stone block">2. E-Wallet & QRIS (Instan & Bebas Biaya)</span>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/qris.svg') }}" alt="QRIS Semua Bank & E-Wallet" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">QRIS Semua Aplikasi</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/gopay.svg') }}" alt="GoPay" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">GoPay</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/shopeepay.svg') }}" alt="ShopeePay" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">ShopeePay</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/ovo.svg') }}" alt="OVO" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">OVO</span>
                                </div>
                                <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                    <img src="{{ asset('images/payments/dana.svg') }}" alt="DANA" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                    <span class="text-[10px] font-bold text-charcoal">DANA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Category 3 & 4: Cards & Retail -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                            <div class="space-y-2">
                                <span class="text-[11px] font-bold uppercase tracking-wide10 text-stone block">3. Kartu Kredit / Debit (3D Secure)</span>
                                <div class="grid grid-cols-3 gap-2.5">
                                    <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                        <img src="{{ asset('images/payments/visa.svg') }}" alt="Visa" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                        <span class="text-[10px] font-bold text-charcoal">Visa</span>
                                    </div>
                                    <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                        <img src="{{ asset('images/payments/mastercard.svg') }}" alt="Mastercard" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                        <span class="text-[10px] font-bold text-charcoal">Mastercard</span>
                                    </div>
                                    <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                        <img src="{{ asset('images/payments/jcb.svg') }}" alt="JCB" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                        <span class="text-[10px] font-bold text-charcoal">JCB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <span class="text-[11px] font-bold uppercase tracking-wide10 text-stone block">4. Gerai Retail / Minimarket</span>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                        <img src="{{ asset('images/payments/indomaret.svg') }}" alt="Indomaret" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                        <span class="text-[10px] font-bold text-charcoal">Indomaret</span>
                                    </div>
                                    <div class="bg-canvas border border-sand hover:border-charcoal/40 rounded-card p-2.5 flex flex-col items-center justify-center gap-1.5 transition text-center shadow-2xs group">
                                        <img src="{{ asset('images/payments/alfamart.svg') }}" alt="Alfamart" class="h-6 w-auto object-contain group-hover:scale-105 transition">
                                        <span class="text-[10px] font-bold text-charcoal">Alfamart</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Security & Simulation Card -->
                    <div class="p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-card space-y-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span class="text-caption font-bold uppercase tracking-wide10 text-emerald-900">Enkripsi Aman & Fitur Simulasi Pembayaran</span>
                        </div>
                        <p class="text-body-sm text-emerald-950">
                            Untuk pengujian / simulasi instan tanpa perlu memasukkan saldo nyata, Anda dapat langsung mengklik tombol <strong>"⚡ Simulasi Bayar Berhasil"</strong> di bawah ini. Pesanan akan otomatis berstatus <em>Lunas (Paid)</em>.
                        </p>
                    </div>

                    <!-- Desktop Action Buttons (Step 3) -->
                    <div class="hidden lg:flex justify-between items-center pt-6 border-t border-sand gap-4">
                        <button type="button" 
                                @click="step = 2" 
                                :disabled="isPlacingOrder || isSimulating"
                                class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal transition">
                            ← Kembali ke Pengiriman
                        </button>
                        
                        <div class="flex items-center gap-3">
                            <!-- Simulation Success Button -->
                            <button type="button" 
                                    @click="placeOrderAndSimulatePay()"
                                    :disabled="isPlacingOrder || isSimulating"
                                    :class="isSimulating ? 'opacity-75 cursor-not-allowed' : ''"
                                    class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-4 rounded-pill text-body-sm font-bold tracking-wide10 shadow-sm transition flex items-center gap-2">
                                <template x-if="isSimulating">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </template>
                                <span>⚡ Simulasi Bayar Berhasil</span>
                            </button>

                            <!-- Midtrans Real Snap Button -->
                            <button type="button" 
                                    @click="placeOrderAndPay()"
                                    :disabled="isPlacingOrder || isSimulating"
                                    :class="isPlacingOrder ? 'opacity-75 cursor-not-allowed' : ''"
                                    class="btn-pill-dark px-8 py-4 text-body-sm font-bold tracking-wide10 shadow-sm flex items-center gap-2">
                                <template x-if="isPlacingOrder">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </template>
                                <span>Bayar Sekarang (<span x-text="formatRupiah(grandTotal)"></span>)</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary Sidebar (4 Cols, Desktop Sticky) -->
        <div class="lg:col-span-4 lg:sticky lg:top-24">
            <div class="bg-[#fcfbf9] border border-sand rounded-card p-5 sm:p-6 space-y-6 shadow-xs">
                
                <div class="flex items-center justify-between border-b border-sand pb-4">
                    <h2 class="font-sans font-bold text-lg text-charcoal">
                        Ringkasan Pesanan
                    </h2>
                    <span class="text-caption font-bold text-stone" x-text="{{ $cartSummary['total_qty'] }} + ' Item'"></span>
                </div>

                <!-- Products list preview in summary -->
                <div class="space-y-3 max-h-56 overflow-y-auto divide-y divide-sand/50 pr-1">
                    @foreach ($cartSummary['items'] as $item)
                        <div class="pt-3 first:pt-0 flex items-center justify-between gap-3 text-body-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-sm bg-[#f5f4f0] overflow-hidden flex-shrink-0">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-sans font-bold text-charcoal line-clamp-1 text-[13px]">{{ $item['product_name'] }}</div>
                                    <div class="text-[11px] text-stone">
                                        {{ $item['color_name'] }} • Size {{ $item['size'] }} EU (x{{ $item['qty'] }})
                                    </div>
                                </div>
                            </div>
                            <span class="font-sans font-bold text-charcoal text-[13px] whitespace-nowrap">{{ $item['subtotal_formatted'] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Price Calculations -->
                <div class="border-t border-sand pt-4 space-y-2.5 text-body-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-iron">Subtotal Produk</span>
                        <span class="font-bold text-charcoal">{{ $cartSummary['subtotal_formatted'] }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-iron">Ongkos Kirim</span>
                        <span class="font-bold text-charcoal">
                            <template x-if="!selectedRate">
                                <span class="text-stone font-normal text-caption">Dihitung di Step 2</span>
                            </template>
                            <template x-if="selectedRate">
                                <span>
                                    <template x-if="effectiveShippingCost === 0">
                                        <span class="text-green-700 font-bold">GRATIS</span>
                                    </template>
                                    <template x-if="effectiveShippingCost > 0">
                                        <span x-text="formatRupiah(effectiveShippingCost)"></span>
                                    </template>
                                </span>
                            </template>
                        </span>
                    </div>

                    <template x-if="selectedRate && effectiveShippingCost === 0 && isFreeShipping">
                        <div class="flex items-center justify-between text-[11px] text-green-700">
                            <span>Diskon Gratis Ongkir</span>
                            <span>- <span x-text="selectedRate.price_formatted"></span></span>
                        </div>
                    </template>

                    <div class="border-t border-sand pt-3 flex items-center justify-between text-body">
                        <span class="font-bold text-charcoal">Total Akhir</span>
                        <span class="font-sans font-bold text-xl text-charcoal" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <!-- Guarantee badges -->
                <div class="border-t border-sand pt-4 space-y-2 text-[11px] text-stone">
                    <div class="flex items-center gap-2">
                        <span class="text-charcoal font-bold">✓</span>
                        <span>Garansi 30 hari tukar ukuran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-charcoal font-bold">✓</span>
                        <span>Integrasi pengiriman terpercaya Biteship</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- MOBILE STICKY BOTTOM BAR (Accessible with one thumb on 375px screens) -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-canvas/95 backdrop-blur-md border-t border-sand shadow-2xl z-40 lg:hidden">
        <div class="max-w-container mx-auto flex items-center justify-between gap-3">
            <div class="flex-1">
                <span class="text-[10px] text-stone uppercase tracking-wide10 block">Total Pembayaran</span>
                <span class="font-sans font-bold text-lg text-charcoal" x-text="formatRupiah(grandTotal)"></span>
            </div>

            <!-- Sticky CTA Step 1 -->
            <template x-if="step === 1">
                <button type="button" 
                        @click="goToStep2()" 
                        class="btn-pill-dark py-3.5 px-6 text-caption font-bold tracking-wide10 shadow-sm flex-1 text-center min-h-[48px]">
                    Lanjut ke Ongkir →
                </button>
            </template>

            <!-- Sticky CTA Step 2 -->
            <template x-if="step === 2">
                <button type="button" 
                        @click="goToStep3()" 
                        :disabled="!selectedRate || ratesLoading"
                        :class="!selectedRate || ratesLoading ? 'opacity-50 cursor-not-allowed' : ''"
                        class="btn-pill-dark py-3.5 px-6 text-caption font-bold tracking-wide10 shadow-sm flex-1 text-center min-h-[48px]">
                    Lanjut ke Bayar →
                </button>
            </template>

            <!-- Sticky CTA Step 3 -->
            <template x-if="step === 3">
                <div class="flex items-center gap-2 flex-1">
                    <button type="button" 
                            @click="placeOrderAndSimulatePay()"
                            :disabled="isPlacingOrder || isSimulating"
                            :class="isSimulating ? 'opacity-75 cursor-not-allowed' : ''"
                            class="bg-emerald-700 hover:bg-emerald-800 text-white py-3.5 px-3 rounded-pill text-[11px] font-bold tracking-wide10 shadow-sm flex-1 text-center min-h-[48px] flex items-center justify-center gap-1.5 transition">
                        <template x-if="isSimulating">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span>⚡ Simulasi Bayar</span>
                    </button>

                    <button type="button" 
                            @click="placeOrderAndPay()"
                            :disabled="isPlacingOrder || isSimulating"
                            :class="isPlacingOrder ? 'opacity-75 cursor-not-allowed' : ''"
                            class="btn-pill-dark py-3.5 px-3 text-[11px] font-bold tracking-wide10 shadow-sm flex-1 text-center min-h-[48px] flex items-center justify-center gap-1.5">
                        <template x-if="isPlacingOrder">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span>Bayar Snap</span>
                    </button>
                </div>
            </template>
        </div>
    </div>

</div>

<!-- Midtrans Snap.js CDN Script -->
@php
    $snapJsUrl = config('services.midtrans.snap_js', 'https://app.sandbox.midtrans.com/snap/snap.js');
    $clientKey = config('services.midtrans.client_key', '');
@endphp
<script type="text/javascript" 
        src="{{ $snapJsUrl }}" 
        data-client-key="{{ $clientKey }}"></script>
@endsection
