@extends('layouts.app')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @include('account._nav')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-sand">
        <div>
            <h2 class="font-sans font-bold text-xl uppercase tracking-wide10 text-charcoal">Buku Alamat</h2>
            <p class="text-body-sm text-iron mt-1">Kelola daftar alamat pengiriman pesanan Anda.</p>
        </div>
        <a href="{{ route('account.addresses.create') }}" class="btn-pill-dark text-caption px-6 py-3">
            + Tambah Alamat Baru
        </a>
    </div>

    <!-- Flash notification -->
    @if (session('success'))
        <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-sm text-body-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="mt-8">
        @if ($addresses->isEmpty())
            <div class="text-center py-16 bg-sand/20 rounded-card p-6 space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-sand flex items-center justify-center text-charcoal">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="font-sans font-bold text-lg text-charcoal">Belum Ada Alamat Tersimpan</h2>
                <p class="text-body-sm text-iron max-w-sm mx-auto">
                    Tambahkan alamat tempat tinggal atau kantor Anda untuk mempermudah proses checkout.
                </p>
                <div class="pt-2">
                    <a href="{{ route('account.addresses.create') }}" class="btn-pill-dark text-caption px-6 py-3">
                        Tambah Alamat Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($addresses as $address)
                    <div class="bg-canvas border {{ $address->is_default ? 'border-charcoal ring-1 ring-charcoal' : 'border-sand' }} rounded-card p-6 flex flex-col justify-between relative shadow-xs">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-sans font-bold text-body text-charcoal">{{ $address->label }}</span>
                                    @if ($address->is_default)
                                        <span class="bg-charcoal text-canvas text-[10px] uppercase tracking-wide10 font-bold px-2 py-0.5 rounded-pill">
                                            Utama
                                        </span>
                                    @endif
                                </div>
                                <div class="text-caption text-stone">
                                    {{ $address->phone }}
                                </div>
                            </div>

                            <div class="text-body-sm text-charcoal font-medium">
                                {{ $address->recipient_name }}
                            </div>

                            <p class="text-body-sm text-iron mt-1 line-clamp-3">
                                {{ $address->address_line }}
                            </p>

                            <p class="text-caption text-stone mt-2">
                                {{ $address->district }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                            </p>
                        </div>

                        <!-- Actions footer -->
                        <div class="pt-6 mt-4 border-t border-sand/70 flex items-center justify-between text-caption font-bold uppercase tracking-wide10">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('account.addresses.edit', $address) }}" class="text-charcoal hover:underline">
                                    Ubah
                                </a>
                                <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-stone hover:text-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                            @if (! $address->is_default)
                                <form action="{{ route('account.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-charcoal hover:underline">
                                        Set Alamat Utama
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
