@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Kategori Produk</h1>
            <p class="text-body-sm text-iron mt-1">Kelola struktur kategori bertingkat dan pembagian gender.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-pill-dark">
            + Tambah Kategori
        </a>
    </div>

    <!-- Category Tree Table -->
    <div class="bg-canvas border border-sand rounded-card overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-body-sm">
                <thead class="bg-sand/30 text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand">
                    <tr>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Slug</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">Parent</th>
                        <th class="py-3 px-4">Urutan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-sand/10 transition">
                            <td class="py-3.5 px-4 font-medium text-charcoal">
                                @if ($cat->parent_id)
                                    <span class="text-stone mr-1">↳</span>
                                @endif
                                {{ $cat->name }}
                            </td>
                            <td class="py-3.5 px-4 text-iron font-mono text-caption">{{ $cat->slug }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-pill text-caption uppercase font-medium {{ $cat->gender === 'men' ? 'bg-blue-100 text-blue-800' : ($cat->gender === 'women' ? 'bg-rose-100 text-rose-800' : 'bg-sand text-charcoal') }}">
                                    {{ $cat->gender }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-stone">{{ $cat->parent->name ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-iron">{{ $cat->order }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block w-2.5 h-2.5 rounded-full {{ $cat->is_active ? 'bg-green-600' : 'bg-stone' }}"></span>
                                <span class="text-caption ml-1">{{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="text-charcoal font-bold hover:underline text-caption uppercase">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 font-bold hover:underline text-caption uppercase">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-stone">Belum ada kategori yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
