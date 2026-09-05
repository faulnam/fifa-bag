<div class="border-b border-sand pb-4 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Portal Pelanggan</span>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">
                {{ Auth::user()->name }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-caption text-iron hidden sm:inline-block">{{ Auth::user()->email }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-pill-light text-caption px-4 py-1.5 min-h-[36px]">
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile-First Horizontal Scrollable Navigation Bar -->
    <nav class="flex items-center space-x-2 overflow-x-auto no-scrollbar py-1 text-caption font-medium uppercase tracking-wide10 -mx-4 px-4 sm:mx-0 sm:px-0">
        <a href="{{ route('account.dashboard') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.dashboard') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Ringkasan</span>
        </a>

        <a href="{{ route('account.orders.index') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.orders.*') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span>Pesanan & Tracking</span>
        </a>

        <a href="{{ route('account.addresses.index') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.addresses.*') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Buku Alamat</span>
        </a>

        <a href="{{ route('account.wishlist') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.wishlist') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>Wishlist</span>
        </a>

        <a href="{{ route('account.reviews.index') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.reviews.*') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <span>Ulasan Saya</span>
        </a>

        <a href="{{ route('account.profile') }}" 
           class="whitespace-nowrap px-4 py-2 rounded-pill transition-colors flex items-center gap-2 {{ request()->routeIs('account.profile') ? 'bg-charcoal text-canvas font-bold' : 'bg-sand/30 text-charcoal hover:bg-sand' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>Profil & Keamanan</span>
        </a>
    </nav>
</div>
