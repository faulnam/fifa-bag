@extends('layouts.app')

@section('title', 'Ulasan Saya — fifa')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @include('account._nav')

    <div class="space-y-6">
        <div class="border-b border-sand pb-4">
            <h2 class="font-sans font-bold text-xl uppercase tracking-wide10 text-charcoal">
                Ulasan Produk
            </h2>
            <p class="text-body-sm text-iron mt-1">Ulasan dan testimoni pengalaman produk fifa yang pernah Anda kirimkan.</p>
        </div>

        @if ($reviews->isEmpty())
            <div class="text-center py-16 bg-sand/20 rounded-card p-6 space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-sand flex items-center justify-center text-charcoal text-2xl">
                    ★
                </div>
                <h3 class="font-sans font-bold text-lg text-charcoal">Belum Ada Ulasan</h3>
                <p class="text-body-sm text-iron max-w-sm mx-auto">
                    Anda belum menulis ulasan produk. Anda dapat memberikan ulasan langsung pada halaman produk yang telah Anda beli.
                </p>
                <div class="pt-2">
                    <a href="{{ route('account.orders.index') }}" class="btn-pill-dark text-caption px-6 py-2.5 inline-block">
                        Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($reviews as $review)
                    <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <!-- Product thumbnail & status header -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    @php
                                        $img = $review->product?->images?->firstWhere('is_primary', true) ?? $review->product?->images?->first();
                                        $imgPath = $img ? (filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path)) : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80';
                                    @endphp
                                    <div class="w-14 h-14 rounded-card bg-[#f5f4f0] overflow-hidden flex-shrink-0">
                                        <img src="{{ $imgPath }}" alt="{{ $review->product->name ?? 'Produk' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="font-sans font-bold text-body-sm text-charcoal line-clamp-1">
                                            {{ $review->product->name ?? 'Produk' }}
                                        </h3>
                                        <span class="text-caption text-stone">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div>
                                    @if ($review->is_approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-[11px] font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-[11px] font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu Moderasi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Star rating & review body -->
                            <div>
                                <div class="text-yellow-500 font-bold text-caption">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                                <h4 class="font-bold text-body-sm text-charcoal mt-1">{{ $review->title }}</h4>
                                <p class="text-caption text-iron mt-1 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>

                        @if ($review->product)
                            <div class="pt-3 border-t border-sand flex justify-end">
                                <a href="{{ route('products.show', $review->product->slug) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                    Lihat di Halaman Produk ↗
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
