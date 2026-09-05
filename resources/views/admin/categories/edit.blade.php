@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-sand pb-4">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Edit Kategori</h1>
            <p class="text-body-sm text-iron">Ubah informasi kategori {{ $category->name }}.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-pill-light text-caption">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Nama Kategori *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required class="input-inset w-full">
            @error('name') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Slug *</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required class="input-inset w-full">
            @error('slug') <p class="text-caption text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="gender" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Gender *</label>
                <select id="gender" name="gender" required class="input-inset w-full">
                    <option value="men" {{ old('gender', $category->gender) === 'men' ? 'selected' : '' }}>Pria (Men)</option>
                    <option value="women" {{ old('gender', $category->gender) === 'women' ? 'selected' : '' }}>Wanita (Women)</option>
                    <option value="unisex" {{ old('gender', $category->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                </select>
            </div>

            <div>
                <label for="parent_id" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Parent Kategori</label>
                <select id="parent_id" name="parent_id" class="input-inset w-full">
                    <option value="">-- Kategori Induk --</option>
                    @foreach ($parentCategories as $pCat)
                        <option value="{{ $pCat->id }}" {{ old('parent_id', $category->parent_id) == $pCat->id ? 'selected' : '' }}>
                            {{ $pCat->name }} ({{ strtoupper($pCat->gender) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="description" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Deskripsi Kategori</label>
            <textarea id="description" name="description" rows="3" class="input-inset w-full">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="order" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Urutan Tampilan</label>
                <input type="number" id="order" name="order" value="{{ old('order', $category->order) }}" class="input-inset w-full">
            </div>

            <div>
                <label for="image" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Ganti Gambar</label>
                <input type="file" id="image" name="image" accept="image/*" class="input-inset w-full">
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer min-h-[44px]">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                <span class="text-caption font-medium uppercase tracking-wide10 text-charcoal">Status Aktif (Tampilkan di Toko)</span>
            </label>
        </div>

        <div class="pt-4 border-t border-sand flex items-center justify-between">
            <button type="submit" class="btn-pill-dark">
                Perbarui Kategori
            </button>
        </div>
    </form>
</div>
@endsection
