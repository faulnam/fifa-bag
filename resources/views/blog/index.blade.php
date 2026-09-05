@extends('layouts.app')

@section('title', 'fifa Journal — Kisah Material Alami & Keberlanjutan')

@section('content')
<!-- Header Hero -->
<div class="bg-sand/20 border-b border-sand py-12 sm:py-16 text-center">
    <div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-caption font-bold uppercase tracking-wide10 text-stone block mb-2">Cerita & Jurnal Kami</span>
        <h1 class="font-display font-normal text-3xl sm:text-4xl lg:text-5xl text-charcoal tracking-tight">
            The fifa Journal
        </h1>
        <p class="text-body text-iron max-w-xl mx-auto mt-4 leading-relaxed">
            Inspirasi desain sepatu, inovasi material wol dan pohon terbarukan, serta komitmen kami untuk jejak karbon nol.
        </p>
    </div>
</div>

<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <!-- Articles Grid (2 cols mobile, 3 cols desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($posts as $post)
            <article class="group bg-canvas border border-sand rounded-card overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden aspect-[16/10] bg-sand/30">
                    @if ($post->cover_image)
                        <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-stone text-caption font-bold uppercase">fifa Story</div>
                    @endif
                </a>

                <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="text-caption text-stone block uppercase tracking-wide10 font-bold">
                            {{ $post->published_at ? $post->published_at->format('d F Y') : 'Journal' }}
                        </span>
                        <h2 class="font-sans font-bold text-lg text-charcoal group-hover:underline line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if($post->excerpt)
                            <p class="text-body-sm text-iron line-clamp-3 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-sand/50">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-caption font-bold uppercase tracking-wide10 text-charcoal inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                            Baca Selengkapnya <span>→</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-16 text-center text-stone bg-sand/10 rounded-card border border-sand">
                <p class="text-body font-medium">Belum ada artikel yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    @if ($posts->hasPages())
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
