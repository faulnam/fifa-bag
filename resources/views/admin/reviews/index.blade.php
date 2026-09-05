@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Moderasi Ulasan Produk</h1>
            <p class="text-body-sm text-iron mt-1">Kelola dan moderasi testimoni kepuasan pelanggan sebelum ditampilkan ke halaman produk.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-pill text-caption font-bold bg-yellow-100 text-yellow-800">
                {{ $counts['pending'] }} Perlu Moderasi
            </span>
            <span class="px-3 py-1 rounded-pill text-caption font-bold bg-green-100 text-green-800">
                {{ $counts['approved'] }} Disetujui
            </span>
        </div>
    </div>

    <!-- Filter Status Tabs & Search -->
    <div class="bg-canvas border border-sand rounded-card p-4 flex flex-col sm:flex-row gap-4 justify-between items-center">
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ !request('status') ? 'bg-charcoal text-canvas' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('status') === 'pending' ? 'bg-charcoal text-canvas' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
                Menunggu Moderasi ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ request('status') === 'approved' ? 'bg-charcoal text-canvas' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
                Disetujui ({{ $counts['approved'] }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-2 w-full sm:w-auto">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari user / produk / ulasan..." 
                   class="input-clean text-body-sm px-3 py-1.5 bg-canvas w-full sm:w-64">
            <button type="submit" class="btn-pill-light text-caption px-4 py-1.5">
                Cari
            </button>
        </form>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-sand text-left text-body-sm">
            <thead class="bg-sand/30 font-bold uppercase tracking-wide10 text-caption text-charcoal">
                <tr>
                    <th class="py-3.5 px-4">Produk</th>
                    <th class="py-3.5 px-4">Customer</th>
                    <th class="py-3.5 px-4">Rating & Judul</th>
                    <th class="py-3.5 px-4">Komentar Ulasan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Moderasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($reviews as $review)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-4 px-4">
                            <span class="font-bold text-charcoal block">{{ $review->product->name ?? 'Produk Dihapus' }}</span>
                            @if($review->product)
                                <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="text-caption text-stone hover:underline">Lihat PDP ↗</a>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-caption">
                            <span class="font-bold text-charcoal block">{{ $review->user->name ?? 'Guest/Deleted' }}</span>
                            <span class="text-stone">{{ $review->user->email ?? '-' }}</span>
                            @if ($review->order_item_id)
                                <span class="text-[10px] text-green-700 bg-green-50 px-1.5 py-0.5 rounded-sm block mt-0.5 font-bold">✓ Verified Buyer</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-yellow-500 font-bold">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                <span class="text-caption text-charcoal ml-1 font-mono">({{ $review->rating }}/5)</span>
                            </div>
                            <span class="font-bold text-charcoal text-body-sm block mt-1">{{ $review->title }}</span>
                        </td>
                        <td class="py-4 px-4 text-body-sm text-iron max-w-xs">
                            <p class="line-clamp-3 leading-relaxed">{{ $review->comment }}</p>
                            <span class="text-[11px] text-stone block mt-1">{{ $review->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @if ($review->is_approved)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                            @if (! $review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-pill-dark text-caption px-3 py-1 bg-green-800 hover:bg-green-900 min-h-[32px]">
                                        Approve ✓
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-pill-light text-caption px-3 py-1 min-h-[32px] text-stone">
                                        Tolak / Unapprove
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus ulasan ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-caption font-bold text-red-600 underline hover:text-red-800 ml-2">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-stone">
                            Tidak ada ulasan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($reviews as $review)
            <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-body-sm text-charcoal">{{ $review->product->name ?? 'Produk' }}</span>
                    @if ($review->is_approved)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-pill text-[11px] font-medium bg-green-100 text-green-800">
                            Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-pill text-[11px] font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-caption">
                    <span class="text-stone">{{ $review->user->name ?? 'User' }}</span>
                    <span class="text-yellow-500 font-bold">{{ str_repeat('★', $review->rating) }}</span>
                </div>

                <div class="text-body-sm text-iron">
                    <h4 class="font-bold text-charcoal">{{ $review->title }}</h4>
                    <p class="text-caption mt-1 leading-relaxed">{{ $review->comment }}</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-sand">
                    @if (! $review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-pill-dark text-caption px-4 py-1.5 bg-green-800">
                                Approve ✓
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-pill-light text-caption px-4 py-1.5 text-stone">
                                Unapprove
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-pill-light text-caption px-3 py-1.5 text-red-600 border-red-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Tidak ada ulasan ditemukan.
            </div>
        @endforelse
    </div>

    @if ($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
