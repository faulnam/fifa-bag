@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16"
     x-data="{
        snapToken: '{{ $snapToken ?? '' }}',
        orderStatus: '{{ $order->status }}',
        orderNumber: '{{ $order->order_number }}',
        isProcessing: false,
        paymentMessage: '',

        payNow() {
            if (!this.snapToken) {
                alert('Token pembayaran tidak ditemukan. Silakan muat ulang halaman.');
                return;
            }

            if (typeof window.snap === 'undefined') {
                alert('Modul pembayaran Midtrans sedang dimuat. Silakan coba sesaat lagi.');
                return;
            }

            const self = this;
            window.snap.pay(this.snapToken, {
                onSuccess: function(result) {
                    self.orderStatus = 'paid';
                    self.paymentMessage = 'Pembayaran berhasil! Terima kasih.';
                    window.location.reload();
                },
                onPending: function(result) {
                    self.paymentMessage = 'Menunggu penyelesaian pembayaran melalui VA / gerai pilihan Anda.';
                },
                onError: function(result) {
                    alert('Pembayaran gagal atau dibatalkan. Anda dapat mencobanya kembali.');
                },
                onClose: function() {
                    console.log('Snap popup closed by customer without completing payment.');
                }
            });
        },

        async simulatePaymentNow() {
            if (this.isProcessing) return;
            this.isProcessing = true;
            try {
                const res = await fetch('{{ route('orders.simulate-payment', $order) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.orderStatus = 'paid';
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal melakukan simulasi pembayaran.');
                    this.isProcessing = false;
                }
            } catch (e) {
                alert('Terjadi kesalahan: ' + e.message);
                this.isProcessing = false;
            }
        }
     }">

    <!-- Success / Status Hero Banner -->
    <div class="text-center space-y-3 pb-8 border-b border-sand">
        <template x-if="orderStatus === 'paid' || orderStatus === 'processing' || orderStatus === 'ready_to_ship' || orderStatus === 'shipped' || orderStatus === 'delivered'">
            <div>
                <div class="w-16 h-16 mx-auto rounded-full bg-green-100 text-green-700 flex items-center justify-center text-2xl font-bold mb-3">
                    ✓
                </div>
                <span class="inline-block bg-green-100 text-green-800 text-caption font-bold uppercase tracking-wide10 px-3 py-1 rounded-pill mb-2">
                    Pembayaran Berhasil
                </span>
                <h1 class="font-sans font-bold text-2xl sm:text-3xl text-charcoal">
                    Terima Kasih Atas Pesanan Anda!
                </h1>
                <p class="text-body-sm text-iron max-w-md mx-auto">
                    Pesanan Anda telah kami terima dan akan segera disiapkan di gudang kami untuk proses pengiriman.
                </p>
            </div>
        </template>

        <template x-if="orderStatus === 'pending_payment'">
            <div>
                <div class="w-16 h-16 mx-auto rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-2xl font-bold mb-3">
                    ⏱
                </div>
                <span class="inline-block bg-amber-100 text-amber-900 text-caption font-bold uppercase tracking-wide10 px-3 py-1 rounded-pill mb-2">
                    Menunggu Pembayaran
                </span>
                <h1 class="font-sans font-bold text-2xl sm:text-3xl text-charcoal">
                    Pesanan Berhasil Dibuat
                </h1>
                <p class="text-body-sm text-iron max-w-md mx-auto">
                    Selesaikan pembayaran pesanan Anda sebelum batas waktu berakhir untuk mengamankan stok produk.
                </p>

                <div class="pt-4 flex items-center justify-center gap-3 flex-wrap">
                    <button type="button" 
                            @click="simulatePaymentNow()" 
                            :disabled="isProcessing"
                            class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-3.5 rounded-pill text-caption font-bold tracking-wide10 shadow-md transition flex items-center gap-2">
                        <template x-if="isProcessing">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span>⚡ Simulasi Bayar Berhasil (Demo)</span>
                    </button>

                    <button type="button" 
                            @click="payNow()" 
                            class="btn-pill-dark px-8 py-3.5 text-caption font-bold tracking-wide10 shadow-md">
                        Bayar via Midtrans Snap →
                    </button>
                </div>
            </div>
        </template>

        <template x-if="orderStatus === 'cancelled' || orderStatus === 'failed'">
            <div>
                <div class="w-16 h-16 mx-auto rounded-full bg-red-100 text-red-700 flex items-center justify-center text-2xl font-bold mb-3">
                    ✕
                </div>
                <span class="inline-block bg-red-100 text-red-800 text-caption font-bold uppercase tracking-wide10 px-3 py-1 rounded-pill mb-2">
                    Pesanan Dibatalkan
                </span>
                <h1 class="font-sans font-bold text-2xl text-charcoal">
                    Pembayaran Kedaluwarsa / Dibatalkan
                </h1>
                <p class="text-body-sm text-iron max-w-md mx-auto">
                    Stok produk telah dikembalikan ke inventaris toko kami. Anda dapat membuat pesanan baru kapan saja.
                </p>
            </div>
        </template>
    </div>

    <!-- Order Meta Bar -->
    <div class="bg-sand/20 border border-sand rounded-card p-4 sm:p-5 mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-body-sm">
        <div>
            <span class="text-caption text-stone uppercase tracking-wide10 block">Nomor Pesanan</span>
            <span class="font-sans font-bold text-lg text-charcoal">{{ $order->order_number }}</span>
        </div>
        <div>
            <span class="text-caption text-stone uppercase tracking-wide10 block">Tanggal Pemesanan</span>
            <span class="font-medium text-charcoal">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>
        <div>
            <span class="text-caption text-stone uppercase tracking-wide10 block">Total Transaksi</span>
            <span class="font-sans font-bold text-lg text-charcoal">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Purchased Products List -->
    <div class="mt-8 bg-canvas border border-sand rounded-card p-6 space-y-6">
        <h2 class="font-sans font-bold text-lg text-charcoal border-b border-sand pb-3">
            Item Pesanan ({{ $order->items->sum('qty') }} Produk)
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
                            <span class="text-caption text-stone">Qty: {{ $item->qty }}x (Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                        </div>
                    </div>
                    <span class="font-sans font-bold text-body-sm text-charcoal whitespace-nowrap">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Shipping & Price Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        
        <!-- Shipping Address Snapshot -->
        <div class="bg-canvas border border-sand rounded-card p-6 space-y-3">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                Alamat & Kurir Pengiriman
            </h2>
            <div class="text-body-sm text-iron space-y-1">
                <p class="font-bold text-charcoal">{{ $order->shipping_address_snapshot['recipient_name'] ?? $order->guest_name }} ({{ $order->shipping_address_snapshot['phone'] ?? $order->guest_phone }})</p>
                <p>{{ $order->shipping_address_snapshot['address_line'] ?? '-' }}</p>
                <p class="text-caption text-stone">
                    {{ $order->shipping_address_snapshot['district'] ?? '' }}, {{ $order->shipping_address_snapshot['city'] ?? '' }}, {{ $order->shipping_address_snapshot['province'] ?? '' }} {{ $order->shipping_address_snapshot['postal_code'] ?? '' }}
                </p>
            </div>
            <div class="pt-2 border-t border-sand/60 text-body-sm">
                <span class="text-caption text-stone uppercase tracking-wide10 block">Kurir Pilihan</span>
                <span class="font-medium text-charcoal">{{ $order->courier_company }} - {{ $order->courier_type }}</span>
            </div>
        </div>

        <!-- Payment & Cost Breakdown -->
        <div class="bg-[#fcfbf9] border border-sand rounded-card p-6 space-y-3">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                Rincian Biaya
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
                @if ((float) $order->discount > 0)
                    <div class="flex items-center justify-between text-green-700">
                        <span>Diskon Kupon</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="border-t border-sand pt-2 flex items-center justify-between text-body">
                    <span class="font-bold text-charcoal">Total Bayar</span>
                    <span class="font-sans font-bold text-xl text-charcoal">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Actions Footer -->
    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('home') }}" class="btn-pill-light w-full sm:w-auto text-center px-8 py-3.5 text-caption font-bold tracking-wide10">
            ← Kembali ke Beranda
        </a>
        @auth
            <a href="{{ route('account.dashboard') }}" class="btn-pill-dark w-full sm:w-auto text-center px-8 py-3.5 text-caption font-bold tracking-wide10">
                Lihat Akun & Pesanan Saya →
            </a>
        @endauth
    </div>

</div>

<!-- Midtrans Snap.js CDN Script -->
@php
    $snapJsUrl = config('services.midtrans.snap_js', 'https://app.sandbox.midtrans.com/snap/snap.js');
    $clientKey = config('services.midtrans.client_key', '');
@endphp
<script type="text/javascript" 
        src="{{ $snapJsUrl }}" 
        data-client-key="{{ $clientKey }}"></script>
@endsection
