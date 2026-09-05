@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        snapToken: '{{ $order->status === 'pending_payment' ? app(\App\Services\Payment\PaymentService::class)->createTransaction($order)['token'] : '' }}',
        payNow() {
            if (!this.snapToken) {
                alert('Token pembayaran tidak ditemukan.');
                return;
            }
            if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                window.snap.pay(this.snapToken, {
                    onSuccess: function() { window.location.reload(); },
                    onPending: function() { window.location.reload(); },
                    onError: function() { alert('Pembayaran gagal atau dibatalkan.'); },
                    onClose: function() { console.log('Snap closed.'); }
                });
            } else {
                alert('Memuat modul Midtrans Snap...');
            }
        }
     }">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-caption text-stone uppercase tracking-wide10 mb-6">
        <a href="{{ route('home') }}" class="hover:text-charcoal">Home</a>
        <span>/</span>
        <a href="{{ route('account.dashboard') }}" class="hover:text-charcoal">Akun Saya</a>
        <span>/</span>
        <a href="{{ route('account.orders.index') }}" class="hover:text-charcoal">Riwayat Pesanan</a>
        <span>/</span>
        <span class="text-charcoal font-bold">{{ $order->order_number }}</span>
    </nav>

    <!-- Header & Status Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-sand">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">
                Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-body-sm text-iron mt-1">
                Dipesan pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <div>
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
            <span class="inline-block px-4 py-1.5 rounded-pill text-caption font-bold uppercase tracking-wide10 border {{ $badgeClasses }}">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
        </div>
    </div>

    <!-- Pending Payment Callout if unpaid -->
    @if ($order->status === 'pending_payment')
        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1 text-body-sm">
                <p class="font-bold text-amber-900">Pesanan Menunggu Pembayaran</p>
                <p class="text-amber-800">Selesaikan pembayaran Anda untuk memproses pengiriman produk.</p>
            </div>
            <button type="button" @click="payNow()" class="btn-pill-dark text-caption px-6 py-2.5 whitespace-nowrap shadow-sm">
                Bayar Sekarang →
            </button>
        </div>
    @endif

    <!-- Vertical Tracking Timeline (Mobile First - No Horizontal Scrolling) -->
    <div class="mt-8 bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-sand pb-3">
            <h2 class="font-sans font-bold text-lg text-charcoal">
                Pelacakan Pengiriman (Live Tracking)
            </h2>
            @if ($order->shipment?->tracking_id)
                <span class="text-caption font-bold text-charcoal bg-sand/40 px-2 py-0.5 rounded">
                    Resi: {{ $order->shipment->tracking_id }}
                </span>
            @endif
        </div>

        <div class="text-body-sm text-iron">
            <p><span class="font-medium text-charcoal">Kurir:</span> {{ $order->courier_company }} ({{ $order->courier_type }})</p>
        </div>

        <!-- Vertical Timeline Stepper -->
        <div class="pt-4">
            <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-sand">
                
                @if ($order->shipment && $order->shipment->trackings->isNotEmpty())
                    @foreach ($order->shipment->trackings as $index => $tracking)
                        <div class="relative">
                            <!-- Bullet Icon -->
                            <div class="absolute -left-6 sm:-left-8 top-1 w-3 h-3 rounded-full {{ $index === 0 ? 'bg-charcoal ring-4 ring-sand/50' : 'bg-stone/60 ring-2 ring-canvas' }}"></div>
                            
                            <div class="space-y-0.5">
                                <h3 class="font-sans font-bold text-body-sm text-charcoal">
                                    {{ ucwords(str_replace('_', ' ', $tracking->status)) }}
                                </h3>
                                <p class="text-caption text-iron">{{ $tracking->note }}</p>
                                <span class="text-[11px] text-stone block">{{ $tracking->occurred_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default Steps when webhook is pending -->
                    <div class="relative">
                        <div class="absolute -left-6 sm:-left-8 top-1 w-3 h-3 rounded-full bg-charcoal ring-4 ring-sand/50"></div>
                        <div class="space-y-0.5">
                            <h3 class="font-sans font-bold text-body-sm text-charcoal">Pesanan Dibuat</h3>
                            <p class="text-caption text-iron">Pesanan Anda berhasil tercatat di sistem kami.</p>
                            <span class="text-[11px] text-stone block">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    @if ($order->isPaid())
                        <div class="relative">
                            <div class="absolute -left-6 sm:-left-8 top-1 w-3 h-3 rounded-full bg-charcoal ring-4 ring-sand/50"></div>
                            <div class="space-y-0.5">
                                <h3 class="font-sans font-bold text-body-sm text-charcoal">Pembayaran Terverifikasi</h3>
                                <p class="text-caption text-iron">Pembayaran telah diterima dan pesanan sedang disiapkan di gudang.</p>
                            </div>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

    <!-- Items Breakdown -->
    <div class="mt-8 bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-4">
        <h2 class="font-sans font-bold text-lg text-charcoal border-b border-sand pb-3">
            Rincian Produk
        </h2>

        <div class="divide-y divide-sand">
            @foreach ($order->items as $item)
                @php
                    $img = $item->variant?->product?->images?->firstWhere('is_primary', true) ?? $item->variant?->product?->images?->first();
                    $imgPath = $img ? (filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path)) : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80';
                @endphp
                <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-card bg-[#f5f4f0] overflow-hidden flex-shrink-0">
                            <img src="{{ $imgPath }}" alt="{{ $item->product_name_snapshot }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-sans font-bold text-body-sm text-charcoal">{{ $item->product_name_snapshot }}</h3>
                            <p class="text-caption text-iron mt-0.5">
                                {{ $item->variant_snapshot['color_name'] ?? '' }} • Size {{ $item->variant_snapshot['size'] ?? '' }} EU
                            </p>
                            <span class="text-caption text-stone">{{ $item->qty }}x (Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                        </div>
                    </div>
                    <span class="font-sans font-bold text-body-sm text-charcoal whitespace-nowrap">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Shipping Address & Total Calculation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-canvas border border-sand rounded-card p-6 space-y-3">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                Alamat Penerima
            </h2>
            <div class="text-body-sm text-iron space-y-1">
                <p class="font-bold text-charcoal">{{ $order->shipping_address_snapshot['recipient_name'] ?? $order->guest_name }} ({{ $order->shipping_address_snapshot['phone'] ?? $order->guest_phone }})</p>
                <p>{{ $order->shipping_address_snapshot['address_line'] ?? '-' }}</p>
                <p class="text-caption text-stone">
                    {{ $order->shipping_address_snapshot['district'] ?? '' }}, {{ $order->shipping_address_snapshot['city'] ?? '' }}, {{ $order->shipping_address_snapshot['province'] ?? '' }} {{ $order->shipping_address_snapshot['postal_code'] ?? '' }}
                </p>
            </div>
        </div>

        <div class="bg-[#fcfbf9] border border-sand rounded-card p-6 space-y-3">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                Ringkasan Pembayaran
            </h2>
            <div class="space-y-2 text-body-sm">
                <div class="flex items-center justify-between text-iron">
                    <span>Subtotal Produk</span>
                    <span class="font-medium text-charcoal">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-iron">
                    <span>Ongkos Kirim</span>
                    <span class="font-medium text-charcoal">
                        @if ((float) $order->shipping_cost == 0)
                            <span class="text-green-700 font-bold uppercase">Gratis Ongkir</span>
                        @else
                            Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
                <div class="border-t border-sand pt-2 flex items-center justify-between text-body font-bold text-charcoal">
                    <span>Total Pembayaran</span>
                    <span class="font-sans text-xl">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Back -->
    <div class="mt-8 flex justify-center">
        <a href="{{ route('account.orders.index') }}" class="btn-pill-light text-caption px-8 py-3">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

</div>

<!-- Midtrans Snap.js Script if pending payment -->
@if ($order->status === 'pending_payment')
    @php
        $snapJsUrl = config('services.midtrans.snap_js', 'https://app.sandbox.midtrans.com/snap/snap.js');
        $clientKey = config('services.midtrans.client_key', '');
    @endphp
    <script type="text/javascript" src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
@endif
@endsection
