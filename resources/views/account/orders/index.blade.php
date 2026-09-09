@extends('layouts.app')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @include('account._nav')

    <div class="space-y-6">
        @if ($orders->isEmpty())
            <div class="text-center py-16 bg-sand/20 rounded-card p-6 space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-sand flex items-center justify-center text-charcoal">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="font-sans font-bold text-lg text-charcoal">Belum Ada Riwayat Pesanan</h2>
                <p class="text-body-sm text-iron max-w-sm mx-auto">
                    Anda belum melakukan pemesanan produk. Jelajahi koleksi tas dan ransel ramah lingkungan fifa.
                </p>
                <div class="pt-2">
                    <a href="{{ route('home') }}" class="btn-pill-dark text-caption px-6 py-3">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-canvas border border-sand rounded-card p-5 sm:p-6 shadow-xs space-y-4">
                        
                        <!-- Order Card Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-sand gap-3 text-body-sm">
                            <div>
                                <span class="font-sans font-bold text-lg text-charcoal block">{{ $order->order_number }}</span>
                                <span class="text-caption text-stone">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>

                            <div class="flex items-center gap-3">
                                @php
                                    $badgeClasses = match($order->status) {
                                        'paid' => 'bg-green-100 text-green-800 border-green-200',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'ready_to_ship' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'delivered', 'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'pending_payment' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'cancelled', 'refunded' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-sand text-charcoal border-sand',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-pill text-caption font-bold uppercase tracking-wide10 border {{ $badgeClasses }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Items Preview -->
                        <div class="divide-y divide-sand/60">
                            @foreach ($order->items as $item)
                                @php
                                    $img = $item->variant?->product?->images?->firstWhere('is_primary', true) ?? $item->variant?->product?->images?->first();
                                    $imgPath = $img ? (filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path)) : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80';
                                @endphp
                                <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-14 h-14 rounded-card bg-[#f5f4f0] overflow-hidden flex-shrink-0">
                                            <img src="{{ $imgPath }}" alt="{{ $item->product_name_snapshot }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-sans font-bold text-body-sm text-charcoal">{{ $item->product_name_snapshot }}</h3>
                                            <p class="text-caption text-iron">{{ $item->variant_snapshot['color_name'] ?? '' }} • Size {{ $item->variant_snapshot['size'] ?? '' }} EU (x{{ $item->qty }})</p>
                                        </div>
                                    </div>
                                    <span class="font-sans font-bold text-body-sm text-charcoal whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Card Footer -->
                        <div class="pt-4 border-t border-sand flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="text-body-sm">
                                <span class="text-stone">Total Pembayaran:</span>
                                <span class="font-sans font-bold text-lg text-charcoal ml-1">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('account.orders.show', $order) }}" class="btn-pill-dark text-caption px-6 py-2.5 w-full sm:w-auto text-center">
                                    Lihat Detail & Lacak Kurir →
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach

                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
