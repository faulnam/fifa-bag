<div x-data="{
    searchQuery: '{{ old('city', $address->city ?? '') ? (old('district', $address->district ?? '') . ', ' . old('city', $address->city ?? '') . ', ' . old('province', $address->province ?? '')) : '' }}',
    biteshipAreaId: '{{ old('biteship_area_id', $address->biteship_area_id ?? '') }}',
    province: '{{ old('province', $address->province ?? '') }}',
    city: '{{ old('city', $address->city ?? '') }}',
    district: '{{ old('district', $address->district ?? '') }}',
    postalCode: '{{ old('postal_code', $address->postal_code ?? '') }}',
    areas: [],
    loading: false,
    isOpen: false,
    errorMessage: '',

    async searchArea() {
        const q = this.searchQuery.trim();
        if (q.length < 3) {
            this.areas = [];
            this.isOpen = false;
            this.errorMessage = q.length > 0 ? 'Ketik minimal 3 karakter...' : '';
            return;
        }

        this.loading = true;
        this.errorMessage = '';
        try {
            const res = await fetch(`/shipping/areas?query=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success && data.areas.length > 0) {
                this.areas = data.areas;
                this.isOpen = true;
            } else {
                this.areas = [];
                this.isOpen = true;
                this.errorMessage = 'Area tidak ditemukan. Silakan cek ejaan nama kecamatan/kota.';
            }
        } catch (e) {
            this.errorMessage = 'Gagal memuat area. Silakan coba lagi.';
        } finally {
            this.loading = false;
        }
    },

    selectArea(area) {
        this.biteshipAreaId = area.id;
        this.province = area.province;
        this.city = area.city;
        this.district = area.district;
        this.postalCode = area.postal_code || '';
        this.searchQuery = `${area.district}, ${area.city}, ${area.province}`;
        this.isOpen = false;
        this.areas = [];
    }
}" class="space-y-6">

    <input type="hidden" name="biteship_area_id" :value="biteshipAreaId">
    <input type="hidden" name="province" :value="province">
    <input type="hidden" name="city" :value="city">
    <input type="hidden" name="district" :value="district">

    <!-- Label Alamat -->
    <div>
        <label for="label" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
            Label Alamat
        </label>
        <input type="text" 
               id="label" 
               name="label" 
               value="{{ old('label', $address->label ?? 'Rumah') }}" 
               placeholder="Contoh: Rumah, Kantor, Apartemen" 
               class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
        @error('label')
            <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nama Penerima & No Telepon -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="recipient_name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                Nama Penerima <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="recipient_name" 
                   name="recipient_name" 
                   required
                   value="{{ old('recipient_name', $address->recipient_name ?? Auth::user()->name) }}" 
                   placeholder="Nama lengkap penerima" 
                   class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
            @error('recipient_name')
                <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
            </label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   required
                   value="{{ old('phone', $address->phone ?? Auth::user()->phone) }}" 
                   placeholder="081234567890" 
                   class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
            @error('phone')
                <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Biteship Autocomplete Area (Kecamatan / Kota) -->
    <div class="relative" @click.away="isOpen = false">
        <label for="area_search" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
            Kecamatan, Kota, atau Provinsi <span class="text-red-500">*</span>
        </label>
        
        <div class="relative">
            <input type="text" 
                   id="area_search"
                   x-model="searchQuery" 
                   @input.debounce.300ms="searchArea()"
                   @focus="if(areas.length > 0) isOpen = true"
                   placeholder="Ketik minimal 3 karakter (misal: Kebayoran, Bandung, Surabaya)..." 
                   class="input-clean text-body-sm w-full pr-10 rounded-sm border-sand focus:border-charcoal focus:ring-0"
                   autocomplete="off">

            <!-- Loading Spinner / Search Icon -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-stone">
                <template x-if="loading">
                    <svg class="animate-spin h-4 w-4 text-charcoal" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </template>
                <template x-if="!loading">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </template>
            </div>
        </div>

        <!-- Autocomplete Dropdown List -->
        <div x-show="isOpen" 
             x-transition 
             class="absolute left-0 right-0 top-full mt-1 bg-canvas border border-sand rounded-card shadow-lg z-50 max-h-60 overflow-y-auto divide-y divide-sand/50"
             style="display: none;">
            
            <template x-if="errorMessage">
                <div class="p-3 text-caption text-stone text-center" x-text="errorMessage"></div>
            </template>

            <template x-for="area in areas" :key="area.id">
                <button type="button" 
                        @click="selectArea(area)" 
                        class="w-full text-left p-3 hover:bg-sand/30 transition flex items-center justify-between group">
                    <div>
                        <div class="text-body-sm font-bold text-charcoal group-hover:underline" x-text="area.district + ', ' + area.city"></div>
                        <div class="text-caption text-iron" x-text="area.province + (area.postal_code ? ' • Kode Pos ' + area.postal_code : '')"></div>
                    </div>
                    <span class="text-caption text-stone group-hover:text-charcoal font-bold uppercase tracking-wide10 text-[10px]">
                        Pilih →
                    </span>
                </button>
            </template>
        </div>

        <template x-if="biteshipAreaId">
            <div class="mt-1 flex items-center gap-1.5 text-caption text-green-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Area pengiriman terverifikasi Biteship</span>
            </div>
        </template>

        @error('province')
            <p class="text-caption text-red-600 mt-1">Area tujuan wajib dipilih dari hasil pencarian.</p>
        @enderror
    </div>

    <!-- Kode Pos -->
    <div>
        <label for="postal_code" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
            Kode Pos <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="postal_code" 
               name="postal_code" 
               required
               x-model="postalCode"
               placeholder="Contoh: 12190" 
               class="input-clean text-body-sm w-full sm:w-1/2 rounded-sm border-sand focus:border-charcoal focus:ring-0">
        @error('postal_code')
            <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Alamat Lengkap (Jalan, No Rumah, RT/RW, Patokan) -->
    <div>
        <label for="address_line" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
            Alamat Lengkap & Patokan <span class="text-red-500">*</span>
        </label>
        <textarea id="address_line" 
                  name="address_line" 
                  rows="3" 
                  required
                  placeholder="Nama jalan, nomor rumah/gedung, RT/RW, patokan dekat lokasi" 
                  class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">{{ old('address_line', $address->address_line ?? '') }}</textarea>
        @error('address_line')
            <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Jadikan Alamat Utama Checkbox -->
    <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" 
               id="is_default" 
               name="is_default" 
               value="1" 
               {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}
               class="rounded border-sand text-charcoal focus:ring-0 w-4 h-4">
        <label for="is_default" class="text-body-sm text-charcoal cursor-pointer">
            Jadikan sebagai alamat pengiriman utama
        </label>
    </div>

</div>
