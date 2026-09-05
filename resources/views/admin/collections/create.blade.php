@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-sand pb-4">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Buat Koleksi Baru</h1>
            <p class="text-body-sm text-iron">Kurasi produk ke dalam kelompok koleksi khusus.</p>
        </div>
        <a href="{{ route('admin.collections.index') }}" class="btn-pill-light text-caption">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 space-y-5">
        @csrf

        <div>
            <label for="title" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Judul Koleksi *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="input-inset w-full" placeholder="Contoh: New Arrivals">
            @error('title') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Slug (Opsional)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="input-inset w-full" placeholder="contoh: new-arrivals">
            @error('slug') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Koleksi</label>
            <textarea id="description" name="description" rows="3" class="input-inset w-full">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="order" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Urutan Tampilan</label>
                <input type="number" id="order" name="order" value="{{ old('order', 0) }}" class="input-inset w-full">
            </div>

            <div>
                <label for="banner_image" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Banner Koleksi (Opsional)</label>
                <input type="file" id="banner_image" name="banner_image" accept="image/*" class="input-inset w-full">
            </div>
        </div>

        <!-- Product Multi-Select -->
        <div class="border-t border-sand pt-4 space-y-2">
            <span class="block text-caption font-bold uppercase tracking-wide10 text-charcoal">Pilih Produk dalam Koleksi Ini</span>
            <div class="max-h-56 overflow-y-auto border border-sand rounded-card p-3 space-y-2 divide-y divide-sand/50">
                @foreach ($products as $prod)
                    <label class="flex items-center gap-3 pt-2 first:pt-0 cursor-pointer min-h-[36px]">
                        <input type="checkbox" name="products[]" value="{{ $prod->id }}" {{ in_array($prod->id, old('products', [])) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                        <span class="text-body-sm text-charcoal font-medium">{{ $prod->name }}</span>
                        <span class="text-caption text-stone">({{ $prod->category->name ?? '-' }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Status Aktif (Tampilkan di Toko)</span>
            </label>
        </div>

        <div class="pt-4 border-t border-sand">
            <button type="submit" class="btn-pill-dark">
                Simpan Koleksi
            </button>
        </div>
    </form>
</div>
@endsection
