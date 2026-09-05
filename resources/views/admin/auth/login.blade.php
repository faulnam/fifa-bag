<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal — fifa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body class="min-h-full bg-sand/30 flex items-center justify-center p-4 font-sans antialiased text-charcoal">
    <div class="max-w-md w-full">
        <div class="text-center mb-8 flex flex-col items-center">
            <img src="{{ asset('images/fifa-logo.svg') }}" alt="fifa" class="h-10 w-auto object-contain mb-1">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone mt-1 block">Panel Administrasi Toko</span>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-8 shadow-sm">
            @if (session('error'))
                <div class="bg-charcoal text-canvas text-caption p-3 rounded-input mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Email Administrator
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="admin@fifa.test"
                           class="input-inset w-full @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-caption text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Password
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

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer min-h-[44px]">
                        <input type="checkbox" name="remember" class="rounded border-slateBorder text-charcoal focus:ring-charcoal w-4 h-4">
                        <span class="text-caption text-iron">Ingat sesi saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-pill-dark w-full">
                        Masuk ke Panel Admin
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-sand text-center">
                <a href="{{ route('home') }}" class="text-caption font-medium text-iron hover:text-charcoal transition">
                    ← Kembali ke Toko Publik
                </a>
            </div>
        </div>
    </div>
</body>
</html>
