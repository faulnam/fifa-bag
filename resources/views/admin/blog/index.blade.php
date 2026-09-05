@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Artikel Blog & Jurnal</h1>
            <p class="text-body-sm text-iron mt-1">Kelola tulisan cerita, inovasi material, dan panduan gaya fifa.</p>
        </div>
        <div>
            <a href="{{ route('admin.blog.create') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>+</span> Tulis Artikel Baru
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-canvas border border-sand rounded-card p-4">
        <form method="GET" action="{{ route('admin.blog.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari judul artikel..." 
                   class="input-clean flex-grow text-body-sm px-4 py-2 bg-canvas">
            
            <select name="status" class="input-clean text-body-sm px-4 py-2 bg-canvas">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            <button type="submit" class="btn-pill-light text-caption px-5 py-2">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.blog.index') }}" class="btn-pill-light text-caption px-4 py-2 flex items-center justify-center">
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
                    <th class="py-3.5 px-4">Cover</th>
                    <th class="py-3.5 px-4">Judul Artikel</th>
                    <th class="py-3.5 px-4">Slug URL</th>
                    <th class="py-3.5 px-4">Tanggal Publikasi</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($posts as $post)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-3 px-4">
                            @if($post->cover_image)
                                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-14 h-10 object-cover rounded-sm border border-sand">
                            @else
                                <div class="w-14 h-10 bg-sand/40 rounded-sm border border-sand flex items-center justify-center text-[10px] text-stone">No Image</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 font-bold text-charcoal">
                            {{ $post->title }}
                        </td>
                        <td class="py-4 px-4 font-mono text-caption text-stone">
                            /journal/{{ $post->slug }}
                        </td>
                        <td class="py-4 px-4 text-caption text-stone">
                            {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft (Belum terbit)' }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                            @if($post->is_published)
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-caption font-medium text-stone hover:text-charcoal mr-2">
                                    Lihat ↗
                                </a>
                            @endif
                            <a href="{{ route('admin.blog.edit', $post->id) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                Edit
                            </a>
                            <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
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
                        <td colspan="6" class="py-8 text-center text-stone">
                            Belum ada artikel jurnal yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($posts as $post)
            <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
                @if($post->cover_image)
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-36 object-cover">
                @endif
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                            {{ $post->is_published ? 'Published' : 'Draft' }}
                        </span>
                        <span class="text-caption text-stone">{{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}</span>
                    </div>
                    <h3 class="font-bold text-charcoal text-body">{{ $post->title }}</h3>
                    <div class="text-caption text-stone font-mono">/journal/{{ $post->slug }}</div>
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-sand">
                        @if($post->is_published)
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-caption font-medium text-stone underline">Lihat ↗</a>
                        @endif
                        <a href="{{ route('admin.blog.edit', $post->id) }}" class="font-bold text-charcoal underline text-caption">Edit</a>
                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-red-600 underline text-caption">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada artikel.
            </div>
        @endforelse
    </div>

    @if ($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
