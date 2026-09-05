@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Halaman Statis (CMS)</h1>
            <p class="text-body-sm text-iron mt-1">Kelola konten halaman editorial seperti Tentang Kami, Keberlanjutan, FAQ, dan Kebijakan.</p>
        </div>
        <div>
            <a href="{{ route('admin.pages.create') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>+</span> Tambah Halaman
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-canvas border border-sand rounded-card p-4">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="flex gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari judul halaman atau slug..." 
                   class="input-clean flex-grow text-body-sm px-4 py-2 bg-canvas">
            <button type="submit" class="btn-pill-light text-caption px-5 py-2">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.pages.index') }}" class="btn-pill-light text-caption px-4 py-2 flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-sand text-left text-body-sm">
            <thead class="bg-sand/30 font-bold uppercase tracking-wide10 text-caption text-charcoal">
                <tr>
                    <th class="py-3.5 px-4">Judul Halaman</th>
                    <th class="py-3.5 px-4">Slug URL</th>
                    <th class="py-3.5 px-4">Meta Title</th>
                    <th class="py-3.5 px-4">Terakhir Diperbarui</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($pages as $page)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-4 px-4 font-bold text-charcoal">
                            {{ $page->title }}
                        </td>
                        <td class="py-4 px-4 font-mono text-caption text-stone">
                            /pages/{{ $page->slug }}
                        </td>
                        <td class="py-4 px-4 text-caption text-iron">
                            {{ $page->meta_title ?: '-' }}
                        </td>
                        <td class="py-4 px-4 text-caption text-stone">
                            {{ $page->updated_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-caption font-medium text-stone hover:text-charcoal mr-2">
                                Lihat ↗
                            </a>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                Edit
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus halaman ini?')">
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
                        <td colspan="5" class="py-8 text-center text-stone">
                            Belum ada halaman statis yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($pages as $page)
            <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-charcoal text-body">{{ $page->title }}</h3>
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-caption font-medium text-stone underline">
                        Lihat ↗
                    </a>
                </div>
                <div class="text-caption font-mono text-stone">/pages/{{ $page->slug }}</div>
                <div class="text-caption text-stone pt-2 border-t border-sand flex items-center justify-between">
                    <span>{{ $page->updated_at->format('d M Y') }}</span>
                    <div class="space-x-3">
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="font-bold text-charcoal underline">Edit</a>
                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus halaman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-red-600 underline">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada halaman statis.
            </div>
        @endforelse
    </div>

    @if ($pages->hasPages())
        <div class="mt-6">
            {{ $pages->links() }}
        </div>
    @endif
</div>
@endsection
