@extends('layouts.admin')

@push('scripts')
<!-- SortableJS CDN for Drag-and-Drop Image Reordering -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('sortable-images');
        if (el) {
            Sortable.create(el, {
                animation: 150,
                ghostClass: 'opacity-40',
                onEnd: function () {
                    const imageIds = Array.from(el.children).map(child => child.dataset.id);
                    fetch("{{ route('admin.products.images.reorder', $product) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ order: imageIds })
                    }).then(res => res.json()).then(data => {
                        console.log('Images reordered successfully');
                    });
                }
            });
        }
    });
</script>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-10" x-data="{ activeTab: 'details' }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-block w-2.5 h-2.5 rounded-full {{ $product->is_active ? 'bg-green-600' : 'bg-stone' }}"></span>
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">{{ $product->name }}</h1>
            <p class="text-body-sm text-iron mt-0.5">Kelola informasi detail produk, varian ukuran/warna, dan galeri foto.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn-pill-light text-caption">
                Lihat di Toko ↗
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn-pill-light text-caption">
                ← Kembali ke List
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex border-b border-sand bg-oatMilk/30 rounded-t-card p-1 gap-2">
        <button type="button" 
                @click="activeTab = 'details'"
                :class="activeTab === 'details' ? 'bg-canvas text-charcoal font-bold shadow-sm' : 'text-iron hover:text-charcoal'"
                class="px-5 py-3 rounded-pill text-caption font-medium uppercase tracking-wide10 transition min-h-[44px]">
            1. Detail & SEO
        </button>

        <button type="button" 
                @click="activeTab = 'variants'"
                :class="activeTab === 'variants' ? 'bg-canvas text-charcoal font-bold shadow-sm' : 'text-iron hover:text-charcoal'"
                class="px-5 py-3 rounded-pill text-caption font-medium uppercase tracking-wide10 transition min-h-[44px] flex items-center gap-2">
            <span>2. Varian Warna & Ukuran</span>
            <span class="bg-sand px-2 py-0.5 rounded-full text-[10px]">{{ $product->variants->count() }}</span>
        </button>

        <button type="button" 
                @click="activeTab = 'images'"
                :class="activeTab === 'images' ? 'bg-canvas text-charcoal font-bold shadow-sm' : 'text-iron hover:text-charcoal'"
                class="px-5 py-3 rounded-pill text-caption font-medium uppercase tracking-wide10 transition min-h-[44px] flex items-center gap-2">
            <span>3. Galeri Foto Produk</span>
            <span class="bg-sand px-2 py-0.5 rounded-full text-[10px]">{{ $product->images->count() }}</span>
        </button>
    </div>

    <!-- TAB 1: PRODUCT DETAILS -->
    <div x-show="activeTab === 'details'" class="space-y-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 space-y-6 shadow-sm">
            @csrf
            @method('PUT')

            <!-- General Info -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Nama Produk *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="input-inset w-full">
                    </div>

                    <div>
                        <label for="category_id" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Kategori *</label>
                        <select id="category_id" name="category_id" required class="input-inset w-full">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ strtoupper($cat->gender) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Slug *</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required class="input-inset w-full">
                </div>

                <div>
                    <label for="short_description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Singkat</label>
                    <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="input-inset w-full">
                </div>

                <div>
                    <label for="description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Lengkap</label>
                    <textarea id="description" name="description" rows="4" class="input-inset w-full">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Prices & Shipping -->
            <div class="space-y-4 border-t border-sand pt-6">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Harga & Pengiriman</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="base_price" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Harga Dasar (Rp) *</label>
                        <input type="number" id="base_price" name="base_price" value="{{ old('base_price', $product->base_price) }}" required min="0" step="1000" class="input-inset w-full">
                    </div>

                    <div>
                        <label for="compare_at_price" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Harga Coret / Asli (Rp)</label>
                        <input type="number" id="compare_at_price" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" min="0" step="1000" class="input-inset w-full">
                    </div>

                    <div>
                        <label for="weight_grams" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Berat (Gram) *</label>
                        <input type="number" id="weight_grams" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams) }}" required min="1" class="input-inset w-full">
                    </div>
                </div>
            </div>

            <!-- Materials & Carbon -->
            <div class="space-y-4 border-t border-sand pt-6">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Material & Keberlanjutan</h2>
                
                <div>
                    <label for="material_info" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Informasi Material</label>
                    <textarea id="material_info" name="material_info" rows="2" class="input-inset w-full">{{ old('material_info', $product->material_info) }}</textarea>
                </div>

                <div>
                    <label for="sustainability_note" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Jejak Karbon & Catatan Keberlanjutan</label>
                    <textarea id="sustainability_note" name="sustainability_note" rows="2" class="input-inset w-full">{{ old('sustainability_note', $product->sustainability_note) }}</textarea>
                </div>
            </div>

            <!-- Collections & Status -->
            <div class="space-y-4 border-t border-sand pt-6">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">Koleksi & Status</h2>
                
                @php $assignedCols = $product->collections->pluck('id')->toArray(); @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 border border-sand rounded-card p-3">
                    @foreach ($collections as $col)
                        <label class="flex items-center gap-2 cursor-pointer text-body-sm text-charcoal">
                            <input type="checkbox" name="collections[]" value="{{ $col->id }}" {{ in_array($col->id, old('collections', $assignedCols)) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                            <span>{{ $col->title }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                        <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Produk Unggulan (Featured)</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                        <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Status Aktif (Tampilkan di Toko)</span>
                    </label>
                </div>
                @error('is_active')
                    <p class="text-caption text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6 border-t border-sand flex items-center justify-between">
                <button type="submit" class="btn-pill-dark">
                    Simpan Perubahan Detail
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: NESTED VARIANTS MANAGER -->
    <div x-show="activeTab === 'variants'" class="space-y-8" style="display: none;">
        
        <!-- Add New Variant Form (with Color Hex Picker + Preview Swatch) -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-5" 
             x-data="{ colorHex: '#212121' }">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3">
                + Tambah Varian Baru
            </h2>

            <form action="{{ route('admin.products.variants.store', $product) }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Color Name -->
                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Nama Warna *</label>
                        <input type="text" name="color_name" required placeholder="Contoh: Natural Black" class="input-inset w-full">
                    </div>

                    <!-- Color Hex Picker with Live Swatch Preview -->
                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Kode Warna Hex *</label>
                        <div class="flex items-center gap-3">
                            <input type="color" 
                                   name="color_hex" 
                                   x-model="colorHex" 
                                   class="w-10 h-10 p-0.5 border border-sand rounded cursor-pointer">
                            <span class="w-8 h-8 rounded-full border border-stone/50 block shadow-inner" 
                                  :style="'background-color: ' + colorHex"></span>
                            <span class="font-mono text-caption text-charcoal font-bold" x-text="colorHex"></span>
                        </div>
                    </div>

                    <!-- Size -->
                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Ukuran (EU) *</label>
                        <input type="text" name="size" required placeholder="40" class="input-inset w-full">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Stok *</label>
                        <input type="number" name="stock" value="10" required min="0" class="input-inset w-full">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">SKU (Opsional, di-generate jika kosong)</label>
                        <input type="text" name="sku" placeholder="Contoh: TRG-BLK-40" class="input-inset w-full">
                    </div>

                    <div>
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Harga Khusus / Override (Rp, opsional)</label>
                        <input type="number" name="price_override" min="0" step="1000" placeholder="Kosongkan jika sama dengan harga dasar" class="input-inset w-full">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="btn-pill-dark text-caption">
                        + Tambahkan Varian
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Variants Table -->
        <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
            <div class="p-4 bg-sand/30 border-b border-sand flex items-center justify-between">
                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                    Daftar Varian Produk ({{ $product->variants->count() }})
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-body-sm">
                    <thead class="bg-sand/20 text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand">
                        <tr>
                            <th class="py-3 px-4">Warna</th>
                            <th class="py-3 px-4">Ukuran</th>
                            <th class="py-3 px-4">SKU</th>
                            <th class="py-3 px-4">Stok</th>
                            <th class="py-3 px-4">Harga Override</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sand">
                        @forelse ($product->variants as $variant)
                            <tr class="hover:bg-sand/10 transition">
                                <!-- Color -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full border border-stone/50 block flex-shrink-0" style="background-color: {{ $variant->color_hex }}"></span>
                                        <span class="font-medium text-charcoal">{{ $variant->color_name }}</span>
                                    </div>
                                </td>

                                <!-- Size -->
                                <td class="py-3.5 px-4 font-bold text-charcoal">{{ $variant->size }}</td>

                                <!-- SKU -->
                                <td class="py-3.5 px-4 font-mono text-caption text-iron">{{ $variant->sku }}</td>

                                <!-- Stock -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-pill text-caption font-bold {{ $variant->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $variant->stock }} pcs
                                    </span>
                                </td>

                                <!-- Price Override -->
                                <td class="py-3.5 px-4 text-iron">
                                    {{ $variant->price_override ? 'Rp ' . number_format($variant->price_override, 0, ',', '.') : '-' }}
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right">
                                    <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus varian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-bold hover:underline text-caption uppercase">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-stone">Belum ada varian ukuran/warna untuk produk ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- TAB 3: NESTED IMAGES MANAGER WITH DRAG-REORDER -->
    <div x-show="activeTab === 'images'" class="space-y-8" style="display: none;">
        
        <!-- Upload Form -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-4">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3">
                + Upload Foto Produk Baru
            </h2>

            <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Pilih File Foto (Bisa pilih beberapa sekaligus)</label>
                        <input type="file" name="images[]" multiple required accept="image/*" class="input-inset w-full">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-pill-dark text-caption w-full min-h-[44px]">
                            Upload Foto
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Gallery Grid with Drag Reorder -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-sand pb-3">
                <span class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                    Galeri Foto Produk ({{ $product->images->count() }}) — Drag kartu untuk mengubah urutan
                </span>
            </div>

            <div id="sortable-images" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($product->images as $img)
                    <div data-id="{{ $img->id }}" class="group relative rounded-card border {{ $img->is_primary ? 'border-charcoal ring-2 ring-charcoal' : 'border-sand' }} bg-[#f5f4f0] p-2 flex flex-col justify-between cursor-move shadow-sm">
                        <!-- Image thumbnail -->
                        <div class="w-full aspect-square rounded-input overflow-hidden bg-white mb-2">
                            <img src="{{ filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path) }}" 
                                 alt="Product image" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Status badge -->
                        <div class="flex items-center justify-between pt-1">
                            @if ($img->is_primary)
                                <span class="bg-charcoal text-canvas text-[10px] font-bold uppercase tracking-wide10 px-2 py-0.5 rounded-pill">
                                    ★ Cover Utama
                                </span>
                            @else
                                <form action="{{ route('admin.products.images.primary', [$product, $img]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-caption text-iron hover:text-charcoal font-bold hover:underline">
                                        Jadikan Utama
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST" onsubmit="return confirm('Hapus gambar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-bold text-caption hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-stone">
                        Belum ada foto yang diunggah untuk produk ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
