@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Pelanggan Newsletter</h1>
            <p class="text-body-sm text-iron mt-1">Daftar email audiens yang berlangganan kabar terbaru dan promo fifa.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subscribers.export') }}" class="btn-pill-dark text-caption px-5 py-2.5 inline-flex items-center gap-2">
                <span>📥</span> Unduh Data CSV
            </a>
        </div>
    </div>

    <!-- Quick Stats & Search -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Total Subscriber</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-1 block">{{ $totalSubscribers }}</span>
        </div>
        <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Status Aktif</span>
            <span class="font-sans font-bold text-2xl text-green-700 mt-1 block">{{ $activeSubscribers }}</span>
        </div>
        <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Ekspor Format</span>
                <span class="font-sans font-bold text-body text-charcoal mt-1 block">CSV (Excel Ready)</span>
            </div>
            <a href="{{ route('admin.subscribers.export') }}" class="btn-pill-light text-caption px-4 py-2">
                Export
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-canvas border border-sand rounded-card p-4">
        <form method="GET" action="{{ route('admin.subscribers.index') }}" class="flex gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari email subscriber..." 
                   class="input-clean flex-grow text-body-sm px-4 py-2 bg-canvas">
            <button type="submit" class="btn-pill-light text-caption px-5 py-2">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.subscribers.index') }}" class="btn-pill-light text-caption px-4 py-2 flex items-center justify-center">
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
                    <th class="py-3.5 px-4">Alamat Email</th>
                    <th class="py-3.5 px-4">Tanggal Berlangganan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand">
                @forelse ($subscribers as $subscriber)
                    <tr class="hover:bg-sand/10 transition-colors">
                        <td class="py-4 px-4 font-medium text-charcoal">
                            {{ $subscriber->email }}
                        </td>
                        <td class="py-4 px-4 text-caption text-stone">
                            {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-medium {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                                {{ $subscriber->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <form action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus email ini dari daftar subscriber?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-caption font-bold text-red-600 underline hover:text-red-800">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-stone">
                            Belum ada pelanggan yang berlangganan newsletter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Cards (Visible on < md) -->
    <div class="md:hidden space-y-3">
        @forelse ($subscribers as $subscriber)
            <div class="bg-canvas border border-sand rounded-card p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="font-bold text-charcoal text-body-sm block">{{ $subscriber->email }}</span>
                    <span class="text-caption text-stone">{{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d M Y') : '' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-pill text-[11px] font-medium {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-stone/20 text-stone' }}">
                        {{ $subscriber->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <form action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" method="POST" onsubmit="return confirm('Hapus subscriber ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-caption font-bold text-red-600 p-1">✕</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-canvas border border-sand rounded-card p-6 text-center text-stone">
                Belum ada subscriber.
            </div>
        @endforelse
    </div>

    @if ($subscribers->hasPages())
        <div class="mt-6">
            {{ $subscribers->links() }}
        </div>
    @endif
</div>
@endsection
