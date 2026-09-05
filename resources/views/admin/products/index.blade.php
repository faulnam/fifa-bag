@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Katalog Produk</h1>
            <p class="text-body-sm text-iron mt-1">Kelola seluruh produk, varian ukuran/warna, dan galeri foto.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-pill-dark">
            + Tambah Produk Baru
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama produk atau slug..." 
                       class="input-inset w-full">
            </div>

            <div>
                <select name="category_id" class="input-inset w-full" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ strtoupper($cat->gender) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <select name="status" class="input-inset w-full" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @if (request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="btn-pill-light text-caption whitespace-nowrap px-3">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Table -->
    <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-body-sm">
                <thead class="bg-sand/30 text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga Dasar</th>
                        <th class="py-3 px-4">Varian</th>
                        <th class="py-3 px-4">Gambar</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand">
                    @forelse ($products as $prod)
                        @php $primaryImg = $prod->images->firstWhere('is_primary', true) ?? $prod->images->first(); @endphp
                        <tr class="hover:bg-sand/10 transition">
                            <!-- Product thumbnail & name -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-input bg-sand/30 overflow-hidden flex-shrink-0 border border-sand">
                                        @if ($primaryImg)
                                            <img src="{{ filter_var($primaryImg->image_path, FILTER_VALIDATE_URL) ? $primaryImg->image_path : asset('storage/' . $primaryImg->image_path) }}" 
                                                 alt="{{ $prod->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[9px] text-stone">No Img</div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.products.edit', $prod) }}" class="font-bold text-charcoal hover:underline line-clamp-1">
                                            {{ $prod->name }}
                                        </a>
                                        <span class="text-caption text-iron font-mono">{{ $prod->slug }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-iron">
                                {{ $prod->category->name ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 font-medium text-charcoal">
                                Rp {{ number_format($prod->base_price, 0, ',', '.') }}
                                @if ($prod->compare_at_price)
                                    <span class="block text-caption text-stone line-through">
                                        Rp {{ number_format($prod->compare_at_price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-pill text-caption font-medium bg-sand text-charcoal">
                                    {{ $prod->variants_count }} Varian
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-pill text-caption font-medium bg-sand text-charcoal">
                                    {{ $prod->images->count() }} Foto
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-block w-2.5 h-2.5 rounded-full {{ $prod->is_active ? 'bg-green-600' : 'bg-stone' }}"></span>
                                <span class="text-caption ml-1">{{ $prod->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $prod) }}" class="text-charcoal font-bold hover:underline text-caption uppercase">
                                    Kelola
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $prod->name }} beserta seluruh varian dan fotonya?')">
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
                            <td colspan="7" class="py-8 text-center text-stone">Tidak ada produk yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-4 border-t border-sand">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
