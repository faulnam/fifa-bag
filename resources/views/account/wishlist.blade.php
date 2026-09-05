@extends('layouts.app')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-caption text-stone uppercase tracking-wide10 mb-6">
        <a href="{{ route('home') }}" class="hover:text-charcoal">Home</a>
        <span>/</span>
        <a href="{{ route('account.dashboard') }}" class="hover:text-charcoal">Akun Saya</a>
        <span>/</span>
        <span class="text-charcoal">Wishlist</span>
    </nav>

    <div class="space-y-6">
        <!-- Title & Counter -->
        <div class="flex items-baseline justify-between border-b border-sand pb-4">
            <div>
                <h1 class="font-sans font-bold text-2xl sm:text-3xl text-charcoal">
                    Wishlist Saya
                </h1>
                <p class="text-body-sm text-iron mt-1">Daftar produk favorit yang Anda simpan untuk nanti.</p>
            </div>
            <span class="text-body-sm text-iron font-medium">{{ $wishlists->total() }} Produk</span>
        </div>

        @if ($wishlists->isEmpty())
            <div class="text-center py-20 bg-sand/15 rounded-card space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-sand flex items-center justify-center text-charcoal">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h2 class="font-sans font-bold text-xl text-charcoal">Wishlist Anda Masih Kosong</h2>
                <p class="text-body-sm text-iron max-w-sm mx-auto">
                    Simpan produk favorit Anda saat menjelajah agar mudah ditemukan kembali kapan saja.
                </p>
                <div class="pt-4">
                    <a href="{{ route('collections.show', 'new-arrivals') }}" class="btn-pill-dark px-8 py-3.5 text-body-sm font-bold tracking-wide10 inline-block">
                        Jelajahi Produk
                    </a>
                </div>
            </div>
        @else
            <!-- Wishlist Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($wishlists as $item)
                    @if ($item->product)
                        <div class="flex flex-col justify-between bg-canvas rounded-card border border-sand p-3 relative group">
                            <!-- Product Card with Wishlist Toggle -->
                            @include('partials.product-card', ['product' => $item->product])
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-8">
                {{ $wishlists->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
