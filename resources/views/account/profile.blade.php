@extends('layouts.app')

@section('title', 'Profil & Keamanan Akun — fifa')

@section('content')
<div class="max-w-container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @include('account._nav')

    @if (session('success'))
        <div class="bg-charcoal text-canvas p-4 rounded-card mb-6 text-body-sm flex items-center gap-2 shadow-sm">
            <span class="font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-card mb-6 text-body-sm space-y-1">
            <span class="font-bold block">Terdapat kesalahan pada formulir:</span>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form 1: Personal Information -->
        <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-sand pb-4">
                <h2 class="font-sans font-bold text-lg uppercase tracking-wide10 text-charcoal">
                    Informasi Profil
                </h2>
                <p class="text-body-sm text-iron mt-1">Perbarui nama lengkap, alamat email, dan nomor telepon kontak Anda.</p>
            </div>

            <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="input-clean w-full text-body @error('name') border-red-500 @enderror">
                </div>

                <div>
                    <label for="email" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           class="input-clean w-full text-body @error('email') border-red-500 @enderror">
                </div>

                <div>
                    <label for="phone" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           placeholder="Misal: 081234567890" 
                           class="input-clean w-full text-body @error('phone') border-red-500 @enderror">
                </div>

                <div class="pt-4 border-t border-sand flex justify-end">
                    <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Change Password (Requires Current Password Re-Auth) -->
        <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-sand pb-4">
                <h2 class="font-sans font-bold text-lg uppercase tracking-wide10 text-charcoal">
                    Keamanan & Kata Sandi
                </h2>
                <p class="text-body-sm text-iron mt-1">Ganti kata sandi akun Anda. Wajib memasukkan kata sandi saat ini demi keamanan.</p>
            </div>

            <form action="{{ route('account.profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Kata Sandi Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           id="current_password" 
                           required 
                           placeholder="••••••••" 
                           class="input-clean w-full text-body @error('current_password') border-red-500 @enderror">
                    <p class="text-[11px] text-stone mt-1">Verifikasi identitas sebelum mengubah kredensial.</p>
                </div>

                <div>
                    <label for="password" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           placeholder="Minimal 8 karakter" 
                           class="input-clean w-full text-body @error('password') border-red-500 @enderror">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           required 
                           placeholder="Ketik ulang kata sandi baru" 
                           class="input-clean w-full text-body">
                </div>

                <div class="pt-4 border-t border-sand flex justify-end">
                    <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
