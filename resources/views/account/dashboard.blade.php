@extends('layouts.app')

@section('title', 'Dashboard Akun Saya — fifa')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @include('account._nav')

    <div class="space-y-8">
        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-canvas border border-sand rounded-card p-4 sm:p-6 shadow-sm">
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Total Pesanan</span>
                <span class="font-sans font-bold text-2xl sm:text-3xl text-charcoal mt-1 block">
                    {{ $stats['total_orders'] }}
                </span>
                <a href="{{ route('account.orders.index') }}" class="text-[11px] text-iron hover:text-charcoal underline mt-1 block">
                    Lihat Semua Pesanan →
                </a>
            </div>

            <div class="bg-canvas border border-sand rounded-card p-4 sm:p-6 shadow-sm">
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Belum Terbayar</span>
                <span class="font-sans font-bold text-2xl sm:text-3xl {{ $stats['pending_payment'] > 0 ? 'text-amber-700' : 'text-charcoal' }} mt-1 block">
                    {{ $stats['pending_payment'] }}
                </span>
                <span class="text-[11px] text-stone mt-1 block">Menunggu pembayaran</span>
            </div>

            <div class="bg-canvas border border-sand rounded-card p-4 sm:p-6 shadow-sm">
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Dalam Pengiriman</span>
                <span class="font-sans font-bold text-2xl sm:text-3xl {{ $stats['in_shipping'] > 0 ? 'text-blue-700' : 'text-charcoal' }} mt-1 block">
                    {{ $stats['in_shipping'] }}
                </span>
                <span class="text-[11px] text-stone mt-1 block">Diproses kurir Biteship</span>
            </div>

            <div class="bg-canvas border border-sand rounded-card p-4 sm:p-6 shadow-sm">
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Wishlist Tersimpan</span>
                <span class="font-sans font-bold text-2xl sm:text-3xl text-charcoal mt-1 block">
                    {{ $stats['wishlist_count'] }}
                </span>
                <a href="{{ route('account.wishlist') }}" class="text-[11px] text-iron hover:text-charcoal underline mt-1 block">
                    Buka Wishlist →
                </a>
            </div>
        </div>

        <!-- Recent Orders & Default Address Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Recent Orders (2 cols) -->
            <div class="lg:col-span-2 bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-sand pb-3">
                    <h2 class="font-sans font-bold text-base uppercase tracking-wide10 text-charcoal">
                        Pesanan Terbaru
                    </h2>
                    <a href="{{ route('account.orders.index') }}" class="text-caption font-bold text-stone hover:text-charcoal uppercase tracking-wide10">
                        Lihat Semua →
                    </a>
                </div>

                <div class="divide-y divide-sand">
                    @forelse ($recentOrders as $order)
                        <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 text-body-sm">
                                <div>
                                    <span class="font-mono font-bold text-charcoal">{{ $order->order_number }}</span>
                                    <span class="text-caption text-stone block">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ in_array($order->status, ['paid', 'processing', 'ready_to_ship', 'shipped', 'delivered', 'completed']) ? 'bg-green-100 text-green-800' : ($order->status === 'pending_payment' ? 'bg-amber-100 text-amber-800' : 'bg-sand text-charcoal') }}">
                                        {{ $order->status_label ?? $order->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order items snapshot -->
                            <div class="flex flex-wrap gap-2 items-center">
                                @foreach ($order->items->take(3) as $item)
                                    <div class="flex items-center gap-2 bg-sand/20 rounded-input p-1.5 border border-sand/60 text-caption">
                                        @if($item->variant && $item->variant->product && $item->variant->product->images->isNotEmpty())
                                            <img src="{{ $item->variant->product->images->first()->image_path }}" class="w-8 h-8 object-cover rounded-sm">
                                        @endif
                                        <span class="font-medium text-charcoal line-clamp-1 max-w-[140px]">{{ $item->product_name_snapshot }} (x{{ $item->qty }})</span>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <span class="text-caption text-stone">+{{ $order->items->count() - 3 }} produk lainnya</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-1 text-caption text-stone">
                                <span class="font-bold text-charcoal text-body-sm">
                                    Total: Rp {{ number_format($order->total, 0, ',', '.') }}
                                </span>
                                <div class="space-x-3">
                                    @if($order->status === 'pending_payment')
                                        <a href="{{ route('orders.success', $order->order_number) }}" class="btn-pill-dark text-caption px-3 py-1 bg-amber-800 hover:bg-amber-900">
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                    <a href="{{ route('account.orders.show', $order->id) }}" class="font-bold text-charcoal underline hover:text-stone">
                                        Detail & Tracking →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-stone text-body-sm">
                            <p>Anda belum memiliki riwayat pesanan.</p>
                            <a href="{{ route('categories.men') }}" class="btn-pill-dark text-caption px-5 py-2 mt-4 inline-block">
                                Mulai Belanja Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Default Address & Profile Shortcut (1 col) -->
            <div class="space-y-6">
                <!-- Default Address Card -->
                <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-sand pb-3">
                        <h2 class="font-sans font-bold text-base uppercase tracking-wide10 text-charcoal">
                            Alamat Utama
                        </h2>
                        <a href="{{ route('account.addresses.index') }}" class="text-caption font-bold text-stone hover:text-charcoal uppercase tracking-wide10">
                            Kelola →
                        </a>
                    </div>

                    @if ($defaultAddress)
                        <div class="text-body-sm text-iron space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-charcoal">{{ $defaultAddress->recipient_name }}</span>
                                <span class="px-2 py-0.5 rounded-pill text-[10px] font-bold bg-sand text-charcoal uppercase">
                                    {{ $defaultAddress->label ?: 'Utama' }}
                                </span>
                            </div>
                            <p class="text-caption text-stone">{{ $defaultAddress->phone }}</p>
                            <p class="leading-relaxed pt-1">{{ $defaultAddress->address_line }}</p>
                            <p class="text-caption text-stone">{{ $defaultAddress->district }}, {{ $defaultAddress->city }}, {{ $defaultAddress->province }} {{ $defaultAddress->postal_code }}</p>
                        </div>
                    @else
                        <div class="text-center py-4 text-stone text-caption space-y-2">
                            <p>Belum ada alamat tersimpan.</p>
                            <a href="{{ route('account.addresses.create') }}" class="btn-pill-light text-caption px-4 py-1.5 inline-block">
                                + Tambah Alamat
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Account Security / Profile Shortcut -->
                <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm space-y-3">
                    <h2 class="font-sans font-bold text-base uppercase tracking-wide10 text-charcoal border-b border-sand pb-3">
                        Informasi Akun
                    </h2>
                    <div class="text-body-sm text-iron space-y-1 text-caption">
                        <p><strong class="text-charcoal">Nama:</strong> {{ $user->name }}</p>
                        <p><strong class="text-charcoal">Email:</strong> {{ $user->email }}</p>
                        <p><strong class="text-charcoal">Telepon:</strong> {{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('account.profile') }}" class="btn-pill-light text-caption w-full py-2 block text-center">
                            Edit Profil & Kata Sandi
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
