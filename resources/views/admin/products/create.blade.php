@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-sand pb-4">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Tambah Produk Baru</h1>
            <p class="text-body-sm text-iron">Isi informasi dasar produk. Varian ukuran/warna dan foto tambahan dapat dikelola setelah produk dibuat.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-pill-light text-caption">
            ← Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-card bg-red-50 border border-red-200 text-red-700 text-body-sm space-y-1">
            <span class="font-bold">Perhatian:</span>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 space-y-6">
        @csrf

        <!-- General Info -->
        <div class="space-y-4">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Informasi Utama</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Nama Produk *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input-inset w-full" placeholder="Contoh: Men's Tree Runner Go">
                </div>

                <div>
                    <label for="category_id" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Kategori *</label>
                    <select id="category_id" name="category_id" required class="input-inset w-full">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ strtoupper($cat->gender) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Slug (Opsional)</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="input-inset w-full" placeholder="commuter-backpack-20l">
            </div>

            <div>
                <label for="short_description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Ringkas</label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description') }}" class="input-inset w-full" placeholder="Ransel komuter ergonomis berbahan kanvas organik anti-air...">
            </div>

            <div>
                <label for="description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Lengkap (HTML / Teks)</label>
                <textarea id="description" name="description" rows="4" class="input-inset w-full">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Prices & Weight -->
        <div class="space-y-4 border-t border-sand pt-6">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Harga & Pengiriman</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="base_price" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Harga Jual (Rp) *</label>
                    <input type="number" id="base_price" name="base_price" value="{{ old('base_price') }}" required min="0" step="1000" class="input-inset w-full" placeholder="1750000">
                </div>

                <div>
                    <label for="compare_at_price" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Harga Coret / Asli (Rp)</label>
                    <input type="number" id="compare_at_price" name="compare_at_price" value="{{ old('compare_at_price') }}" min="0" step="1000" class="input-inset w-full" placeholder="1950000">
                </div>

                <div>
                    <label for="weight_grams" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Berat Pengiriman (Gram) *</label>
                    <input type="number" id="weight_grams" name="weight_grams" value="{{ old('weight_grams', 500) }}" required min="1" class="input-inset w-full">
                </div>
            </div>
        </div>

        <!-- Material & Sustainability -->
        <div class="space-y-4 border-t border-sand pt-6">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Material & Keberlanjutan</h2>
            
            <div>
                <label for="material_info" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Informasi Material</label>
                <textarea id="material_info" name="material_info" rows="2" class="input-inset w-full" placeholder="Upper: FSC-certified TENCEL Lyocell...">{{ old('material_info') }}</textarea>
            </div>

            <div>
                <label for="sustainability_note" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Catatan Keberlanjutan & Karbon</label>
                <textarea id="sustainability_note" name="sustainability_note" rows="2" class="input-inset w-full" placeholder="Carbon footprint: 4.87 kg CO2e...">{{ old('sustainability_note') }}</textarea>
            </div>
        </div>

        <!-- Initial Images & Collections -->
        <div class="space-y-4 border-t border-sand pt-6">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Foto Produk & Koleksi</h2>
            
            <div>
                <label for="images" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Upload Foto Awal (Wajib minimal 1 foto untuk status Aktif)</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" class="input-inset w-full">
            </div>

            @if ($collections->isNotEmpty())
                <div>
                    <span class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Masukkan ke Koleksi (Opsional)</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 border border-sand rounded-card p-3">
                        @foreach ($collections as $col)
                            <label class="flex items-center gap-2 cursor-pointer text-body-sm text-charcoal">
                                <input type="checkbox" name="collections[]" value="{{ $col->id }}" {{ in_array($col->id, old('collections', [])) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                                <span>{{ $col->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Produk Unggulan (Featured)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Status Aktif (Tampilkan di Toko)</span>
                </label>
            </div>
        </div>

        <div class="pt-6 border-t border-sand">
            <button type="submit" class="btn-pill-dark">
                Simpan & Lanjutkan Kelola Varian
            </button>
        </div>
    </form>
</div>
@endsection
