@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b border-sand pb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.stores.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal inline-flex items-center gap-1 mb-2">
                ← Kembali ke Daftar Toko
            </a>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Tambah Lokasi Toko Fisik</h1>
        </div>
    </div>

    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.stores.store') }}" method="POST">
            @csrf
            @include('admin.stores._form')
        </form>
    </div>
</div>
@endsection
