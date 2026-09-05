@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Hero Banner Slides</h1>
            <p class="text-body-sm text-iron mt-1">Kelola slide editorial banner untuk homepage, koleksi pria, wanita, dan sale.</p>
        </div>
        <div>
            <a href="{{ route('admin.hero-slides.create') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>+</span> Tambah Hero Slide
            </a>
        </div>
    </div>

    <!-- Filter by Page -->
    <div class="bg-canvas border border-sand rounded-card p-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.hero-slides.index') }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ !request('page') ? 'bg-charcoal text-canvas' : 'bg-sand/40 text-charcoal hover:bg-sand' }}">
            Semua Halaman
        </a>
        <a href="{{ route('admin.hero-slides.index', ['page' => 'home']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('page') === 'home' ? 'bg-charcoal text-canvas' : 'bg-sand/40 text-charcoal hover:bg-sand' }}">
            Home (Utama)
        </a>
        <a href="{{ route('admin.hero-slides.index', ['page' => 'men']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('page') === 'men' ? 'bg-charcoal text-canvas' : 'bg-sand/40 text-charcoal hover:bg-sand' }}">
            Men
        </a>
        <a href="{{ route('admin.hero-slides.index', ['page' => 'women']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('page') === 'women' ? 'bg-charcoal text-canvas' : 'bg-sand/40 text-charcoal hover:bg-sand' }}">
            Women
        </a>
        <a href="{{ route('admin.hero-slides.index', ['page' => 'sale']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('page') === 'sale' ? 'bg-charcoal text-canvas' : 'bg-sand/40 text-charcoal hover:bg-sand' }}">
            Sale
        </a>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-sand text-left text-body-sm">
            <thead class="bg-sand/30 font-bold uppercase tracking-wide10 text-caption text-charcoal">
                <tr>
                    <th class="py-3.5 px-4">Preview</th>
                    <th class="py-3.5 px-4">Halaman</th>
                    <th class="py-3.5 px-4">Judul & Subtitle</th>
                    <th class="py-3.5 px-4">Tombol CTA</th>
                    <th class="py-3.5 px-4">Urutan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($slides as $slide)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-3 px-4">
                            <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="w-16 h-10 object-cover rounded-sm border border-sand">
                        </td>
                        <td class="py-3 px-4 uppercase text-caption font-bold text-charcoal">
                            <span class="px-2.5 py-1 rounded-pill bg-sand text-charcoal">{{ $slide->page }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-charcoal">{{ $slide->title }}</div>
                            <div class="text-caption text-iron">{{ $slide->subtitle }}</div>
                        </td>
                        <td class="py-3 px-4 text-caption text-iron">
                            @if($slide->cta_text)
                                <span class="font-medium text-charcoal">{{ $slide->cta_text }}</span> → <span class="text-stone">{{ $slide->cta_link }}</span>
                            @else
                                <span class="text-stone">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-caption font-bold">{{ $slide->order }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                                {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.hero-slides.edit', $slide->id) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                Edit
                            </a>
                            <form action="{{ route('admin.hero-slides.destroy', $slide->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus slide ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-caption font-bold text-red-600 underline hover:text-red-800 ml-2">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-stone">
                            Belum ada hero slide banner yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($slides as $slide)
            <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
                <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="w-full h-36 object-cover">
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-pill bg-sand text-caption font-bold uppercase text-charcoal">{{ $slide->page }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                            {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <h3 class="font-bold text-charcoal text-body">{{ $slide->title }}</h3>
                    @if($slide->subtitle)
                        <p class="text-caption text-iron">{{ $slide->subtitle }}</p>
                    @endif
                    <div class="flex items-center justify-between text-caption text-stone pt-2 border-t border-sand">
                        <span>Urutan: {{ $slide->order }}</span>
                        <div class="space-x-3">
                            <a href="{{ route('admin.hero-slides.edit', $slide->id) }}" class="font-bold text-charcoal underline">Edit</a>
                            <form action="{{ route('admin.hero-slides.destroy', $slide->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus slide ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-bold text-red-600 underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada hero slide banner.
            </div>
        @endforelse
    </div>

    @if ($slides->hasPages())
        <div class="mt-6">
            {{ $slides->links() }}
        </div>
    @endif
</div>
@endsection
