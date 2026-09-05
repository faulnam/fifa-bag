<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-card text-body-sm space-y-1">
            <span class="font-bold block">Terdapat kesalahan pada formulir:</span>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Nama Gerai / Toko <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $store->name ?? '') }}" 
                   required 
                   placeholder="Misal: fifa Flagship Store Senayan City" 
                   class="input-clean w-full font-bold text-body @error('name') border-red-500 @enderror">
        </div>

        <!-- City -->
        <div>
            <label for="city" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Kota / Wilayah <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="city" 
                   id="city" 
                   value="{{ old('city', $store->city ?? '') }}" 
                   required 
                   placeholder="Misal: Jakarta Pusat, Bandung, Surabaya, Bali" 
                   class="input-clean w-full @error('city') border-red-500 @enderror">
        </div>

        <!-- Address -->
        <div class="md:col-span-2">
            <label for="address" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Alamat Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea name="address" 
                      id="address" 
                      rows="2" 
                      required 
                      placeholder="Misal: Senayan City Mall Lt. 1, Jl. Asia Afrika Lot 19, Gelora, Tanah Abang" 
                      class="input-clean w-full text-body-sm @error('address') border-red-500 @enderror">{{ old('address', $store->address ?? '') }}</textarea>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Nomor Telepon / WhatsApp
            </label>
            <input type="text" 
                   name="phone" 
                   id="phone" 
                   value="{{ old('phone', $store->phone ?? '') }}" 
                   placeholder="Misal: (021) 7278-1234 atau 0812-xxxx-xxxx" 
                   class="input-clean w-full text-body-sm @error('phone') border-red-500 @enderror">
        </div>

        <!-- Opening Hours -->
        <div>
            <label for="opening_hours" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Jam Operasional
            </label>
            <input type="text" 
                   name="opening_hours" 
                   id="opening_hours" 
                   value="{{ old('opening_hours', $store->opening_hours ?? 'Senin - Minggu: 10:00 - 22:00 WIB') }}" 
                   placeholder="Misal: Setiap hari 10:00 - 22:00" 
                   class="input-clean w-full text-body-sm @error('opening_hours') border-red-500 @enderror">
        </div>

        <!-- Latitude -->
        <div>
            <label for="latitude" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Latitude (Opsional)
            </label>
            <input type="number" 
                   name="latitude" 
                   id="latitude" 
                   step="any" 
                   value="{{ old('latitude', $store->latitude ?? '') }}" 
                   placeholder="Misal: -6.227123" 
                   class="input-clean w-full text-body-sm font-mono @error('latitude') border-red-500 @enderror">
        </div>

        <!-- Longitude -->
        <div>
            <label for="longitude" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Longitude (Opsional)
            </label>
            <input type="number" 
                   name="longitude" 
                   id="longitude" 
                   step="any" 
                   value="{{ old('longitude', $store->longitude ?? '') }}" 
                   placeholder="Misal: 106.797456" 
                   class="input-clean w-full text-body-sm font-mono @error('longitude') border-red-500 @enderror">
        </div>
    </div>

    <!-- Active Status -->
    <div class="pt-4 border-t border-sand">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" 
                   name="is_active" 
                   value="1" 
                   {{ old('is_active', $store->is_active ?? true) ? 'checked' : '' }} 
                   class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
            <span class="text-body-sm font-medium text-charcoal">Status toko aktif / buka untuk dikunjungi pelanggan</span>
        </label>
    </div>

    <!-- Submit CTA -->
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-sand">
        <a href="{{ route('admin.stores.index') }}" class="btn-pill-light text-caption px-6 py-2.5">
            Batal
        </a>
        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
            {{ isset($store) ? 'Simpan Perubahan' : 'Tambah Toko Fisik' }}
        </button>
    </div>
</div>
