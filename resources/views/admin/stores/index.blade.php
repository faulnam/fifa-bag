@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Toko Fisik (Store Locations)</h1>
            <p class="text-body-sm text-iron mt-1">Kelola gerai fisik, alamat, jam operasional, dan koordinat Google Maps.</p>
        </div>
        <div>
            <a href="{{ route('admin.stores.create') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>+</span> Tambah Toko Fisik
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-canvas border border-sand rounded-card p-4">
        <form method="GET" action="{{ route('admin.stores.index') }}" class="flex gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari nama gerai, kota, atau alamat..." 
                   class="input-clean flex-grow text-body-sm px-4 py-2 bg-canvas">
            <button type="submit" class="btn-pill-light text-caption px-5 py-2">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.stores.index') }}" class="btn-pill-light text-caption px-4 py-2 flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-sand text-left text-body-sm">
            <thead class="bg-sand/30 font-bold uppercase tracking-wide10 text-caption text-charcoal">
                <tr>
                    <th class="py-3.5 px-4">Nama Toko</th>
                    <th class="py-3.5 px-4">Kota & Alamat</th>
                    <th class="py-3.5 px-4">Telepon & Jam Buka</th>
                    <th class="py-3.5 px-4">Koordinat Maps</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($stores as $store)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-4 px-4 font-bold text-charcoal">
                            {{ $store->name }}
                        </td>
                        <td class="py-4 px-4 text-caption">
                            <span class="font-bold text-charcoal block">{{ $store->city }}</span>
                            <span class="text-iron">{{ $store->address }}</span>
                        </td>
                        <td class="py-4 px-4 text-caption text-iron">
                            <div>Tel: {{ $store->phone ?: '-' }}</div>
                            <div class="text-stone">{{ $store->opening_hours ?: 'Setiap hari 10:00 - 22:00' }}</div>
                        </td>
                        <td class="py-4 px-4 font-mono text-caption text-stone">
                            @if($store->latitude && $store->longitude)
                                {{ $store->latitude }}, {{ $store->longitude }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $store->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                                {{ $store->is_active ? 'Buka / Aktif' : 'Tutup / Nonaktif' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.stores.edit', $store->id) }}" class="text-caption font-bold text-charcoal underline hover:text-stone">
                                Edit
                            </a>
                            <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus toko ini?')">
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
                            Belum ada lokasi toko fisik yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-4">
        @forelse ($stores as $store)
            <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-charcoal text-body">{{ $store->name }}</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-pill text-[11px] font-medium {{ $store->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                        {{ $store->is_active ? 'Aktif' : 'Tutup' }}
                    </span>
                </div>
                <div class="text-caption text-iron">
                    <span class="font-bold text-charcoal block">{{ $store->city }}</span>
                    <span>{{ $store->address }}</span>
                </div>
                <div class="text-caption text-stone pt-2 border-t border-sand flex items-center justify-between">
                    <span>{{ $store->phone ?: 'No phone' }}</span>
                    <div class="space-x-3">
                        <a href="{{ route('admin.stores.edit', $store->id) }}" class="font-bold text-charcoal underline">Edit</a>
                        <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus toko ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-bold text-red-600 underline">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada toko fisik.
            </div>
        @endforelse
    </div>

    @if ($stores->hasPages())
        <div class="mt-6">
            {{ $stores->links() }}
        </div>
    @endif
</div>
@endsection
