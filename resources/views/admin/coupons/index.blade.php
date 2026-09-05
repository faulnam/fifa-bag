@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Kupon Promo</h1>
            <p class="text-body-sm text-iron mt-1">Kelola diskon, masa berlaku, dan batasan penggunaan kupon belanja.</p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.create') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>+</span> Tambah Kupon
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-canvas border border-sand rounded-card p-4">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari kode kupon..." 
                   class="input-clean flex-grow text-body-sm px-4 py-2 bg-canvas">
            
            <select name="status" class="input-clean text-body-sm px-4 py-2 bg-canvas">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="btn-pill-light text-caption px-5 py-2 min-h-[40px]">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.coupons.index') }}" class="btn-pill-light text-caption px-4 py-2 flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Coupons Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-sand text-left text-body-sm">
            <thead class="bg-sand/30 font-bold uppercase tracking-wide10 text-caption text-charcoal">
                <tr>
                    <th class="py-3.5 px-4">Kode Kupon</th>
                    <th class="py-3.5 px-4">Tipe & Nilai</th>
                    <th class="py-3.5 px-4">Min. Belanja / Max. Diskon</th>
                    <th class="py-3.5 px-4">Masa Berlaku</th>
                    <th class="py-3.5 px-4">Penggunaan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($coupons as $coupon)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-4 px-4 font-bold font-mono text-charcoal">
                            {{ $coupon->code }}
                        </td>
                        <td class="py-4 px-4">
                            @if ($coupon->type === 'percent')
                                <span class="font-semibold text-charcoal">{{ (int) $coupon->value }}%</span>
                            @else
                                <span class="font-semibold text-charcoal">Rp {{ number_format($coupon->value, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-caption text-stone block uppercase">{{ $coupon->type }}</span>
                        </td>
                        <td class="py-4 px-4 text-caption text-iron">
                            <div>Min: {{ $coupon->min_purchase ? 'Rp ' . number_format($coupon->min_purchase, 0, ',', '.') : 'Tanpa min.' }}</div>
                            <div>Max: {{ $coupon->max_discount ? 'Rp ' . number_format($coupon->max_discount, 0, ',', '.') : 'Tanpa batas' }}</div>
                        </td>
                        <td class="py-4 px-4 text-caption text-iron">
                            <div>Mulai: {{ $coupon->starts_at ? $coupon->starts_at->format('d M Y') : 'Sekarang' }}</div>
                            <div>Selesai: {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Selamanya' }}</div>
                        </td>
                        <td class="py-4 px-4 text-caption">
                            <span class="font-medium text-charcoal">{{ $coupon->used_count }}</span>
                            @if ($coupon->usage_limit)
                                <span class="text-stone">/ {{ $coupon->usage_limit }} kuota</span>
                            @else
                                <span class="text-stone">/ ∞</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                                    {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                Edit
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kupon promo ini?')">
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
                        <td colspan="7" class="py-8 text-center text-stone">
                            Belum ada kupon promo yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Coupons Mobile Stacked Cards (Visible only on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($coupons as $coupon)
            <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-bold text-body text-charcoal">{{ $coupon->code }}</span>
                    <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                            {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-2 gap-2 text-caption text-iron border-t border-sand/50 pt-2">
                    <div>
                        <span class="text-stone block">Diskon:</span>
                        <span class="font-bold text-charcoal">
                            {{ $coupon->type === 'percent' ? (int)$coupon->value . '%' : 'Rp ' . number_format($coupon->value, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-stone block">Penggunaan:</span>
                        <span class="font-bold text-charcoal">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</span>
                    </div>
                    <div>
                        <span class="text-stone block">Masa Berlaku:</span>
                        <span>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Selamanya' }}</span>
                    </div>
                    <div>
                        <span class="text-stone block">Min. Belanja:</span>
                        <span>{{ $coupon->min_purchase ? 'Rp ' . number_format($coupon->min_purchase, 0, ',', '.') : 'Tanpa min.' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-sand">
                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn-pill-light text-caption px-4 py-1.5 min-h-[36px]">
                        Edit Kupon
                    </a>
                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Hapus kupon ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-pill-light text-caption px-4 py-1.5 min-h-[36px] text-red-600 border-red-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada kupon promo.
            </div>
        @endforelse
    </div>

    @if ($coupons->hasPages())
        <div class="mt-6">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
