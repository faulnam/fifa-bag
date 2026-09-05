@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-sand pb-4">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Edit Koleksi</h1>
            <p class="text-body-sm text-iron">Ubah informasi koleksi {{ $collection->title }}.</p>
        </div>
        <a href="{{ route('admin.collections.index') }}" class="btn-pill-light text-caption">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Judul Koleksi *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $collection->title) }}" required class="input-inset w-full">
            @error('title') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Slug *</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $collection->slug) }}" required class="input-inset w-full">
            @error('slug') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Koleksi</label>
            <textarea id="description" name="description" rows="3" class="input-inset w-full">{{ old('description', $collection->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="order" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Urutan Tampilan</label>
                <input type="number" id="order" name="order" value="{{ old('order', $collection->order) }}" class="input-inset w-full">
            </div>

            <div>
                <label for="banner_image" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Ganti Banner</label>
                <input type="file" id="banner_image" name="banner_image" accept="image/*" class="input-inset w-full">
            </div>
        </div>

        <!-- Product Multi-Select -->
        @php $assignedProductIds = $collection->products->pluck('id')->toArray(); @endphp
        <div class="border-t border-sand pt-4 space-y-2">
            <span class="block text-caption font-bold uppercase tracking-wide10 text-charcoal">Produk Terkait ({{ count($assignedProductIds) }} Dipilih)</span>
            <div class="max-h-56 overflow-y-auto border border-sand rounded-card p-3 space-y-2 divide-y divide-sand/50">
                @foreach ($products as $prod)
                    <label class="flex items-center gap-3 pt-2 first:pt-0 cursor-pointer min-h-[36px]">
                        <input type="checkbox" name="products[]" value="{{ $prod->id }}" {{ in_array($prod->id, old('products', $assignedProductIds)) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                        <span class="text-body-sm text-charcoal font-medium">{{ $prod->name }}</span>
                        <span class="text-caption text-stone">({{ $prod->category->name ?? '-' }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Status Aktif</span>
            </label>
        </div>

        <div class="pt-4 border-t border-sand">
            <button type="submit" class="btn-pill-dark">
                Perbarui Koleksi
            </button>
        </div>
    </form>
</div>
@endsection
