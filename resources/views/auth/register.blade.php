@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 sm:py-24">
    <div class="text-center space-y-2 mb-8">
        <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Buat Akun Baru</h1>
        <p class="text-body-sm text-iron">Daftar untuk menikmati kemudahan checkout dan pelacakan pesanan.</p>
    </div>

    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Nama Lengkap
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="Nama Anda"
                       class="input-inset w-full @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Alamat Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@email.com"
                       class="input-inset w-full @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Nomor WhatsApp / HP (Opsional)
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="081234567890"
                       class="input-inset w-full @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Password (Minimal 8 Karakter)
                </label>
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

            <div>
                <label for="password_confirmation" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                    Konfirmasi Password
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       placeholder="••••••••"
                       class="input-inset w-full">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-pill-dark w-full">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-sand text-center">
            <p class="text-body-sm text-iron">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-charcoal hover:underline ml-1">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
