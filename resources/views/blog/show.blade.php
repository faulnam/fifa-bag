@extends('layouts.app')

@section('title', $post->title . ' — fifa Journal')

@section('content')
<!-- Article Hero / Header -->
<div class="bg-sand/20 border-b border-sand py-12 sm:py-16 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <a href="{{ route('blog.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal inline-flex items-center gap-1">
            ← Kembali ke Jurnal
        </a>
        <h1 class="font-display font-normal text-3xl sm:text-4xl lg:text-5xl text-charcoal tracking-tight leading-tight">
            {{ $post->title }}
        </h1>
        <div class="flex items-center justify-center gap-4 text-caption uppercase tracking-wide10 text-stone pt-2">
            <span>{{ $post->published_at ? $post->published_at->format('d F Y') : 'fifa Team' }}</span>
            <span>•</span>
            <span>fifa Sustainability & Style</span>
        </div>
    </div>
</div>

<!-- Main Article Body -->
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    @if ($post->cover_image)
        <div class="mb-10 rounded-card overflow-hidden border border-sand shadow-sm">
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[500px]">
        </div>
    @endif

    @if ($post->excerpt)
        <div class="p-6 rounded-card bg-sand/20 border-l-4 border-charcoal text-body font-medium text-charcoal italic mb-8 leading-relaxed">
            {{ $post->excerpt }}
        </div>
    @endif

    <article class="prose prose-neutral max-w-none text-body text-iron leading-relaxed space-y-6">
        {!! $post->content !!}
    </article>

    <!-- Share / Footer -->
    <div class="mt-12 pt-8 border-t border-sand flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('blog.index') }}" class="btn-pill-light text-caption px-6 py-2.5 inline-flex items-center gap-2">
            ← Semua Artikel Jurnal
        </a>
        <a href="{{ route('categories.men') }}" class="btn-pill-dark text-caption px-6 py-2.5">
            Belanja Koleksi Terkini
        </a>
    </div>
</div>

<!-- Recent Posts Section -->
@if ($recentPosts->isNotEmpty())
    <section class="bg-sand/20 border-t border-sand py-12 sm:py-16">
        <div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-sans font-bold text-xl uppercase tracking-wide10 text-charcoal mb-8 text-center">
                Cerita Lainnya dari Jurnal
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recentPosts as $recPost)
                    <div class="bg-canvas border border-sand rounded-card overflow-hidden p-4 space-y-3 shadow-sm hover:shadow-md transition-shadow">
                        @if($recPost->cover_image)
                            <img src="{{ $recPost->cover_image }}" alt="{{ $recPost->title }}" class="w-full h-36 object-cover rounded-sm">
                        @endif
                        <span class="text-[11px] text-stone uppercase tracking-wide10 block">{{ $recPost->published_at ? $recPost->published_at->format('d M Y') : '' }}</span>
                        <h3 class="font-bold text-body-sm text-charcoal hover:underline line-clamp-2">
                            <a href="{{ route('blog.show', $recPost->slug) }}">{{ $recPost->title }}</a>
                        </h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
