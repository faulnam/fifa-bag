@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-caption text-stone uppercase tracking-wide10 mb-6">
        <a href="{{ route('home') }}" class="hover:text-charcoal">Home</a>
        <span>/</span>
        <a href="{{ route('account.dashboard') }}" class="hover:text-charcoal">Akun Saya</a>
        <span>/</span>
        <a href="{{ route('account.addresses.index') }}" class="hover:text-charcoal">Buku Alamat</a>
        <span>/</span>
        <span class="text-charcoal font-bold">Tambah Alamat</span>
    </nav>

    <div class="border-b border-sand pb-4 mb-6">
        <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Tambah Alamat Baru</h1>
        <p class="text-body-sm text-iron mt-1">Masukkan data alamat pengiriman lengkap Anda.</p>
    </div>

    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-xs">
        <form action="{{ route('account.addresses.store') }}" method="POST">
            @csrf
            
            @include('account.addresses._form', ['address' => null])

            <div class="pt-6 mt-6 border-t border-sand flex items-center justify-between">
                <a href="{{ route('account.addresses.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal">
                    ← Batal
                </a>
                <button type="submit" class="btn-pill-dark text-caption px-8 py-3.5">
                    Simpan Alamat
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
