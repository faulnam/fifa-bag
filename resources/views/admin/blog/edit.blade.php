@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-sand pb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.blog.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal inline-flex items-center gap-1 mb-2">
                ← Kembali ke Daftar Artikel
            </a>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Edit Artikel: {{ $post->title }}</h1>
        </div>
        @if($post->is_published)
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn-pill-light text-caption px-4 py-2">
                Lihat di Web ↗
            </a>
        @endif
    </div>

    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.blog._form', ['post' => $post])
        </form>
    </div>
</div>
@endsection
