@extends('layouts.admin', ['title' => 'Detail Pesanan #' . $order->order_number])

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-sand">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal">
                    ← Kembali ke Daftar Pesanan
                </a>
            </div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal mt-2">
                Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-body-sm text-iron">
                Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB • Oleh {{ $order->user?->name ?? $order->guest_name }}
            </p>
        </div>

        <div>
            @php
                $badgeClasses = match($order->status) {
                    'paid' => 'bg-green-100 text-green-800 border-green-300',
                    'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                    'ready_to_ship' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                    'shipped' => 'bg-purple-100 text-purple-800 border-purple-300',
                    'delivered', 'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                    'pending_payment' => 'bg-amber-100 text-amber-800 border-amber-300',
                    'cancelled', 'refunded' => 'bg-red-100 text-red-800 border-red-300',
                    default => 'bg-sand text-charcoal border-sand',
                };
            @endphp
            <span class="inline-block px-4 py-1.5 rounded-pill text-caption font-bold uppercase tracking-wide10 border {{ $badgeClasses }}">
                Status: {{ str_replace('_', ' ', $order->status) }}
            </span>
        </div>
    </div>

    <!-- Fulfillment & Biteship Action Card -->
    <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-4">
        <h2 class="font-sans font-bold text-lg text-charcoal border-b border-sand pb-2">
            Aksi Fulfillment & Pengiriman Biteship
        </h2>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-1 text-body-sm">
                <p><span class="font-bold text-charcoal">Kurir Pilihan:</span> {{ $order->courier_company }} - {{ $order->courier_type }}</p>
                <p><span class="font-bold text-charcoal">Biteship Order ID:</span> {{ $order->shipment?->biteship_order_id ?? 'Belum dibuat' }}</p>
                @if ($order->shipment?->tracking_id)
                    <p><span class="font-bold text-charcoal">Nomor Resi / Tracking:</span> <strong class="text-charcoal bg-sand/40 px-2 py-0.5 rounded">{{ $order->shipment->tracking_id }}</strong></p>
                @endif
                @if ($order->shipment?->waybill_id)
                    <p><span class="font-bold text-charcoal">Waybill ID:</span> {{ $order->shipment->waybill_id }}</p>
                @endif
            </div>

            <!-- Fulfillment Trigger Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if (! $order->shipment || empty($order->shipment->biteship_order_id))
                    <form action="{{ route('admin.orders.process-shipping', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                            Proses Pengiriman ke Biteship
                        </button>
                    </form>
                @else
                    @if ($order->shipment->status === 'pending')
                        <form action="{{ route('admin.orders.request-pickup', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5 bg-indigo-900 hover:bg-black">
                                Request Pickup Kurir
                            </button>
                        </form>
                    @else
                        <span class="text-caption font-bold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-pill">
                            ✓ Pickup Sudah Diminta / Berjalan
                        </span>
                    @endif
                @endif
            </div>
        </div>

        <!-- Manual Status Update Section -->
        <div class="pt-4 border-t border-sand/70">
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                @csrf
                @method('PATCH')
                <label for="order_status_select" class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                    Ubah Status Manual:
                </label>
                <select id="order_status_select" name="status" class="input-clean text-body-sm rounded-sm border-sand focus:border-charcoal focus:ring-0">
                    <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="ready_to_ship" {{ $order->status === 'ready_to_ship' ? 'selected' : '' }}>Ready to Ship</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-pill-light text-caption px-4 py-2">
                    Simpan Status
                </button>
            </form>
        </div>
    </div>

    <!-- 2 Column Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Ordered Items (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Items Table -->
            <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-4">
                <h2 class="font-sans font-bold text-lg text-charcoal border-b border-sand pb-3">
                    Item Produk ({{ $order->items->sum('qty') }} pcs)
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
                                    <div class="text-caption text-iron mt-0.5">
                                        Warna: {{ $item->variant_snapshot['color_name'] ?? '-' }} • Size: {{ $item->variant_snapshot['size'] ?? '-' }} EU
                                    </div>
                                    <div class="text-[11px] text-stone">
                                        SKU: {{ $item->variant_snapshot['sku'] ?? '-' }} • Bobot: {{ $item->variant_snapshot['weight_grams'] ?? 600 }}g
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-sans font-bold text-body-sm text-charcoal block">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                                <span class="text-caption text-stone">{{ $item->qty }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Price Calculations Summary -->
                <div class="border-t border-sand pt-4 space-y-2 text-body-sm">
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
                            <span>Diskon</span>
                            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="border-t border-sand pt-3 flex items-center justify-between text-body font-bold text-charcoal">
                        <span>Grand Total</span>
                        <span class="font-sans text-xl">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Internal Notes -->
            <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-3">
                <h2 class="font-sans font-bold text-lg text-charcoal border-b border-sand pb-2">
                    Catatan Internal Pesanan
                </h2>
                <form action="{{ route('admin.orders.notes', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <textarea name="notes" rows="3" placeholder="Tulis catatan admin untuk pesanan ini..." class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">{{ old('notes', $order->notes) }}</textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-pill-dark text-caption px-6 py-2">
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Column: Customer, Address & Tracking History (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Customer & Shipping Info Card -->
            <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-3">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                    Informasi Penerima
                </h2>
                <div class="text-body-sm text-iron space-y-1">
                    <p class="font-bold text-charcoal">{{ $order->shipping_address_snapshot['recipient_name'] ?? $order->guest_name }}</p>
                    <p>No. Telepon: <span class="font-medium text-charcoal">{{ $order->shipping_address_snapshot['phone'] ?? $order->guest_phone }}</span></p>
                    <p class="text-caption text-stone">{{ $order->user?->email ?? $order->guest_email }}</p>
                    <div class="pt-2 border-t border-sand/50 text-caption text-iron">
                        <p class="font-medium text-charcoal">Alamat Pengiriman:</p>
                        <p>{{ $order->shipping_address_snapshot['address_line'] ?? '-' }}</p>
                        <p>{{ $order->shipping_address_snapshot['district'] ?? '' }}, {{ $order->shipping_address_snapshot['city'] ?? '' }}</p>
                        <p>{{ $order->shipping_address_snapshot['province'] ?? '' }} {{ $order->shipping_address_snapshot['postal_code'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info Card -->
            <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-3">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                    Status Pembayaran
                </h2>
                <div class="text-body-sm text-iron space-y-1.5">
                    <p><span class="font-medium text-charcoal">Gateway:</span> {{ strtoupper($order->payment?->gateway ?? 'Midtrans') }}</p>
                    <p><span class="font-medium text-charcoal">Metode:</span> {{ strtoupper($order->payment?->payment_method ?? 'Snap') }}</p>
                    <p><span class="font-medium text-charcoal">Status:</span> 
                        <span class="font-bold {{ $order->payment?->status === 'success' ? 'text-green-700' : 'text-amber-700' }}">
                            {{ strtoupper($order->payment?->status ?? 'Pending') }}
                        </span>
                    </p>
                    @if ($order->payment?->paid_at)
                        <p class="text-caption text-stone">Dibayar pada: {{ $order->payment->paid_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Tracking History Timeline Card -->
            <div class="bg-canvas border border-sand rounded-card p-6 shadow-xs space-y-4">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-2">
                    Histori Tracking Kurir
                </h2>

                @if (! $order->shipment || $order->shipment->trackings->isEmpty())
                    <p class="text-caption text-stone">Belum ada pembaruan tracking dari kurir Biteship.</p>
                @else
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-sand">
                        @foreach ($order->shipment->trackings as $tracking)
                            <div class="relative">
                                <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-charcoal ring-4 ring-canvas"></div>
                                <div class="text-body-sm font-bold text-charcoal">{{ strtoupper(str_replace('_', ' ', $tracking->status)) }}</div>
                                <p class="text-caption text-iron mt-0.5">{{ $tracking->note }}</p>
                                <span class="text-[10px] text-stone block mt-1">{{ $tracking->occurred_at->format('d/m/Y H:i') }} WIB</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
