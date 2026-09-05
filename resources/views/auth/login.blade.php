@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 sm:py-24">
    <div class="text-center space-y-2 mb-8">
        <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Masuk ke Akun Anda</h1>
        <p class="text-body-sm text-iron">Masukkan email dan password untuk melanjutkan belanja.</p>
    </div>

    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Alamat Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@email.com"
                       class="input-inset w-full @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                        Password
                    </label>
                </div>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="input-inset w-full @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center space-x-2 cursor-pointer min-h-[44px]">
                    <input type="checkbox" name="remember" class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-caption text-iron">Ingat Saya</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-pill-dark w-full">
                    Masuk
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-sand text-center">
            <p class="text-body-sm text-iron">
                Belum memiliki akun Fifa?
                <a href="{{ route('register') }}" class="font-bold text-charcoal hover:underline ml-1">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
