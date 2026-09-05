@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' — fifa')

@section('content')
<div class="bg-sand/20 border-b border-sand py-12 sm:py-16 text-center">
    <div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display font-normal text-3xl sm:text-4xl lg:text-5xl text-charcoal tracking-tight">
            {{ $page->title }}
        </h1>
        <p class="text-caption font-bold uppercase tracking-wide10 text-stone mt-3">fifa Journal & Story</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <article class="prose prose-neutral max-w-none text-body text-iron leading-relaxed space-y-6">
        {!! $page->content !!}
    </article>

    <div class="mt-12 pt-8 border-t border-sand flex items-center justify-between text-caption text-stone">
        <span>Terakhir diperbarui: {{ $page->updated_at->format('d F Y') }}</span>
        <a href="{{ route('home') }}" class="font-bold text-charcoal uppercase tracking-wide10 hover:underline">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
