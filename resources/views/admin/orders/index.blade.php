@extends('layouts.admin', ['title' => 'Kelola Pesanan'])

@section('content')
<div class="space-y-6">
    <!-- Header Title & Filter Summary -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-sand">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Kelola Pesanan</h1>
            <p class="text-body-sm text-iron mt-1">Daftar transaksi, pembayaran, dan status fulfillment pesanan toko.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-canvas border border-sand rounded-card p-4 shadow-xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari nomor pesanan, nama customer, email, no HP..." 
                       class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
            </div>
            
            <div class="sm:col-span-4">
                <select name="status" class="input-clean text-body-sm w-full rounded-sm border-sand focus:border-charcoal focus:ring-0">
                    <option value="">Semua Status</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses Gudang</option>
                    <option value="ready_to_ship" {{ request('status') === 'ready_to_ship' ? 'selected' : '' }}>Siap Dikirim / Pickup</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="btn-pill-dark w-full text-caption py-2.5">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.orders.index') }}" class="btn-pill-light text-caption px-3 py-2.5 flex items-center justify-center" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-xs">
        @if ($orders->isEmpty())
            <div class="p-12 text-center text-body-sm text-stone space-y-2">
                <p class="font-bold text-charcoal">Tidak ada pesanan yang cocok dengan filter pencarian.</p>
                <p>Pesanan baru dari customer akan muncul di halaman ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-body-sm">
                    <thead class="bg-sand/30 border-b border-sand text-caption uppercase tracking-wide10 text-stone">
                        <tr>
                            <th class="py-3 px-4">No. Pesanan</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Item & Total</th>
                            <th class="py-3 px-4">Kurir</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sand">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-sand/10 transition">
                                <td class="py-4 px-4 font-bold text-charcoal">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-charcoal">{{ $order->user?->name ?? $order->guest_name }}</div>
                                    <div class="text-caption text-stone">{{ $order->user?->email ?? $order->guest_email }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-charcoal">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                    <div class="text-caption text-stone">{{ $order->items->sum('qty') }} Item Produk</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-medium text-charcoal">{{ $order->courier_company ?: '-' }}</span>
                                    <span class="text-caption text-stone block">{{ $order->courier_type }}</span>
                                </td>
                                <td class="py-4 px-4">
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
                                    <span class="inline-block px-2.5 py-0.5 rounded-pill text-[11px] font-bold uppercase tracking-wide10 border {{ $badgeClasses }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-caption text-stone whitespace-nowrap">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-pill-dark text-caption px-4 py-1.5 inline-block">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-sand bg-sand/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
