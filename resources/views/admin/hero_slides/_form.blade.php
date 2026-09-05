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
        <!-- Target Page -->
        <div>
            <label for="page" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Halaman Penempatan <span class="text-red-500">*</span>
            </label>
            <select name="page" id="page" required class="input-clean w-full @error('page') border-red-500 @enderror">
                <option value="home" {{ old('page', $heroSlide->page ?? 'home') === 'home' ? 'selected' : '' }}>Homepage (Beranda Utama)</option>
                <option value="men" {{ old('page', $heroSlide->page ?? '') === 'men' ? 'selected' : '' }}>Katalog Pria (/men)</option>
                <option value="women" {{ old('page', $heroSlide->page ?? '') === 'women' ? 'selected' : '' }}>Katalog Wanita (/women)</option>
                <option value="sale" {{ old('page', $heroSlide->page ?? '') === 'sale' ? 'selected' : '' }}>Halaman Promo (/sale)</option>
            </select>
        </div>

        <!-- Order / Sort Sequence -->
        <div>
            <label for="order" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Urutan Tampil
            </label>
            <input type="number" 
                   name="order" 
                   id="order" 
                   value="{{ old('order', $heroSlide->order ?? 0) }}" 
                   min="0"
                   class="input-clean w-full @error('order') border-red-500 @enderror">
        </div>

        <!-- Headline Title -->
        <div class="md:col-span-2">
            <label for="title" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Headline / Judul Banner <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $heroSlide->title ?? '') }}" 
                   required 
                   placeholder="Misal: We Made The World's Most Comfortable Shoes" 
                   class="input-clean w-full text-body font-bold @error('title') border-red-500 @enderror">
        </div>

        <!-- Subtitle / Tagline -->
        <div class="md:col-span-2">
            <label for="subtitle" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Sub-headline / Deskripsi Pendek
            </label>
            <input type="text" 
                   name="subtitle" 
                   id="subtitle" 
                   value="{{ old('subtitle', $heroSlide->subtitle ?? '') }}" 
                   placeholder="Misal: Dibuat dari material wol merino alami yang lembut dan ramah bumi." 
                   class="input-clean w-full @error('subtitle') border-red-500 @enderror">
        </div>

        <!-- CTA Button Text -->
        <div>
            <label for="cta_text" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Teks Tombol CTA
            </label>
            <input type="text" 
                   name="cta_text" 
                   id="cta_text" 
                   value="{{ old('cta_text', $heroSlide->cta_text ?? 'Belanja Sekarang') }}" 
                   placeholder="Misal: Shop Men, Beli Sekarang" 
                   class="input-clean w-full @error('cta_text') border-red-500 @enderror">
        </div>

        <!-- CTA Link -->
        <div>
            <label for="cta_link" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Tautan Tombol CTA (URL)
            </label>
            <input type="text" 
                   name="cta_link" 
                   id="cta_link" 
                   value="{{ old('cta_link', $heroSlide->cta_link ?? '/men') }}" 
                   placeholder="Misal: /men, /women, /collections/wool-runners" 
                   class="input-clean w-full @error('cta_link') border-red-500 @enderror">
        </div>

        <!-- Image Upload or URL -->
        <div class="md:col-span-2 space-y-3">
            <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal">
                Foto Banner Hero
            </label>
            
            @if(isset($heroSlide) && $heroSlide->image)
                <div class="flex items-center gap-4 p-3 border border-sand rounded-card bg-sand/10">
                    <img src="{{ $heroSlide->image }}" alt="Current Slide" class="w-32 h-16 object-cover rounded-sm border border-sand">
                    <div class="text-caption text-stone">
                        <span class="font-bold text-charcoal block">Gambar Saat Ini</span>
                        <span>Unggah file baru di bawah jika ingin mengganti gambar.</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="image" class="block text-caption text-iron mb-1">Unggah File (JPG, PNG, WEBP max 4MB):</label>
                    <input type="file" name="image" id="image" accept="image/*" class="text-caption text-iron file:mr-4 file:py-2 file:px-4 file:rounded-pill file:border-0 file:text-caption file:font-semibold file:bg-charcoal file:text-canvas hover:file:bg-iron">
                </div>
                <div>
                    <label for="image_url" class="block text-caption text-iron mb-1">Atau masukkan URL gambar langsung:</label>
                    <input type="url" name="image_url" id="image_url" placeholder="https://..." class="input-clean w-full text-body-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Active Status -->
    <div class="pt-4 border-t border-sand">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" 
                   name="is_active" 
                   value="1" 
                   {{ old('is_active', $heroSlide->is_active ?? true) ? 'checked' : '' }} 
                   class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
            <span class="text-body-sm font-medium text-charcoal">Aktifkan slide banner ini untuk ditampilkan di halaman storefront</span>
        </label>
    </div>

    <!-- Submit CTA -->
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-sand">
        <a href="{{ route('admin.hero-slides.index') }}" class="btn-pill-light text-caption px-6 py-2.5">
            Batal
        </a>
        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
            {{ isset($heroSlide) ? 'Simpan Perubahan' : 'Buat Hero Slide' }}
        </button>
    </div>
</div>
