@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
    <div class="border-b border-sand pb-6">
        <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Pengaturan Toko (Site Settings)</h1>
        <p class="text-body-sm text-iron mt-1">Konfigurasi umum toko, integrasi API Biteship, Payment Gateway Midtrans, dan media sosial.</p>
    </div>

    <!-- Section Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-sand pb-2">
        <button type="button" 
                @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'bg-charcoal text-canvas font-bold' : 'bg-canvas text-charcoal hover:bg-sand/30'"
                class="px-5 py-2 rounded-pill text-caption uppercase tracking-wide10 transition-colors">
            1. Informasi Umum
        </button>

        <button type="button" 
                @click="activeTab = 'shipping'" 
                :class="activeTab === 'shipping' ? 'bg-charcoal text-canvas font-bold' : 'bg-canvas text-charcoal hover:bg-sand/30'"
                class="px-5 py-2 rounded-pill text-caption uppercase tracking-wide10 transition-colors">
            2. Logistik & Biteship
        </button>

        <button type="button" 
                @click="activeTab = 'payment'" 
                :class="activeTab === 'payment' ? 'bg-charcoal text-canvas font-bold' : 'bg-canvas text-charcoal hover:bg-sand/30'"
                class="px-5 py-2 rounded-pill text-caption uppercase tracking-wide10 transition-colors">
            3. Pembayaran & Gateway
        </button>

        <button type="button" 
                @click="activeTab = 'social'" 
                :class="activeTab === 'social' ? 'bg-charcoal text-canvas font-bold' : 'bg-canvas text-charcoal hover:bg-sand/30'"
                class="px-5 py-2 rounded-pill text-caption uppercase tracking-wide10 transition-colors">
            4. Media Sosial & Kontak
        </button>
    </div>

    <!-- Group 1: General Settings -->
    <div x-show="activeTab === 'general'" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_group" value="general">

            <div class="border-b border-sand pb-4">
                <h2 class="font-bold text-lg text-charcoal">Informasi & Branding Toko</h2>
                <p class="text-caption text-stone">Nama toko, tagline, dan threshold gratis ongkir.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="site_name" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Nama Toko</label>
                    <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'fifa Indonesia') }}" class="input-clean w-full">
                </div>

                <div>
                    <label for="site_tagline" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Tagline Toko</label>
                    <input type="text" name="site_tagline" id="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Natural Materials, Sustainable Footwear') }}" class="input-clean w-full">
                </div>

                <div>
                    <label for="contact_email" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Email Layanan Pelanggan</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'support@fifa.co.id') }}" class="input-clean w-full">
                </div>

                <div>
                    <label for="contact_phone" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Telepon / WhatsApp</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '0812-3456-7890') }}" class="input-clean w-full">
                </div>

                <div>
                    <label for="free_shipping_threshold" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Batas Gratis Ongkir (Rp)</label>
                    <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '500000') }}" class="input-clean w-full">
                    <p class="text-caption text-stone mt-1">Belanja di atas nilai ini akan mendapatkan banner gratis ongkir.</p>
                </div>

                <div>
                    <label for="announcement_text" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Teks Announcement Bar Header</label>
                    <input type="text" name="announcement_text" id="announcement_text" value="{{ old('announcement_text', $settings['announcement_text'] ?? 'Gratis Ongkir ke Seluruh Indonesia untuk pesanan di atas Rp 500.000') }}" class="input-clean w-full">
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="announcement_active" value="0">
                    <input type="checkbox" name="announcement_active" value="1" {{ ($settings['announcement_active'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-body-sm font-medium text-charcoal">Tampilkan Announcement Bar di bagian paling atas toko</span>
                </label>

                <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                    Simpan Pengaturan Umum
                </button>
            </div>
        </form>
    </div>

    <!-- Group 2: Shipping / Biteship Settings -->
    <div x-show="activeTab === 'shipping'" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_group" value="shipping">

            <div class="border-b border-sand pb-4">
                <h2 class="font-bold text-lg text-charcoal">Konfigurasi Pengiriman & Biteship API</h2>
                <p class="text-caption text-iron">Area asal gudang (<code class="font-mono text-charcoal">origin_biteship_area_id</code>) digunakan untuk menghitung tarif ongkir kurir realtime.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="origin_biteship_area_id" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                        Origin Biteship Area ID (Gudang Asal) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="origin_biteship_area_id" 
                           id="origin_biteship_area_id" 
                           value="{{ old('origin_biteship_area_id', $settings['origin_biteship_area_id'] ?? 'IDNP6IDNC148IDND859') }}" 
                           required
                           placeholder="Misal: IDNP6IDNC148IDND859 (Kebayoran Baru, Jakarta Selatan)" 
                           class="input-clean w-full font-mono text-body-sm">
                    <p class="text-caption text-stone mt-1">Area ID ini menjadi titik awal kurir saat penjemputan barang dan kalkulasi ongkir.</p>
                </div>

                <div>
                    <label for="origin_city" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Kota Asal Gudang</label>
                    <input type="text" name="origin_city" id="origin_city" value="{{ old('origin_city', $settings['origin_city'] ?? 'Jakarta Selatan') }}" class="input-clean w-full">
                </div>

                <div>
                    <label for="origin_postal_code" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Kode Pos Gudang</label>
                    <input type="text" name="origin_postal_code" id="origin_postal_code" value="{{ old('origin_postal_code', $settings['origin_postal_code'] ?? '12190') }}" class="input-clean w-full">
                </div>

                <div class="md:col-span-2">
                    <label for="origin_address" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Alamat Lengkap Gudang Asal</label>
                    <textarea name="origin_address" id="origin_address" rows="2" class="input-clean w-full text-body-sm">{{ old('origin_address', $settings['origin_address'] ?? 'Gudang Utama fifa, Jl. Jenderal Sudirman Kav. 52-53, SCBD, Kebayoran Baru') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="biteship_api_key" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Biteship API Secret Key</label>
                    <input type="password" name="biteship_api_key" id="biteship_api_key" value="{{ old('biteship_api_key', $settings['biteship_api_key'] ?? '') }}" placeholder="biteship_live_..." class="input-clean w-full font-mono text-body-sm">
                    <p class="text-caption text-stone mt-1">Biarkan kosong jika menggunakan default environment (.env).</p>
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="biteship_active" value="0">
                    <input type="checkbox" name="biteship_active" value="1" {{ ($settings['biteship_active'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-body-sm font-medium text-charcoal">Aktifkan kalkulasi ongkir kurir otomatis via Biteship</span>
                </label>

                <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                    Simpan Pengaturan Pengiriman
                </button>
            </div>
        </form>
    </div>

    <!-- Group 3: Payment / Gateway Settings -->
    <div x-show="activeTab === 'payment'" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_group" value="payment">

            <div class="border-b border-sand pb-4">
                <h2 class="font-bold text-lg text-charcoal">Metode Pembayaran & Payment Gateway</h2>
                <p class="text-caption text-iron">Pilih gateway pembayaran aktif (Midtrans Snap, Sandbox Mock, atau Manual Transfer).</p>
            </div>

            <div class="space-y-4">
                <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal">Gateway Pembayaran Aktif <span class="text-red-500">*</span></label>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="p-4 border rounded-card cursor-pointer flex flex-col justify-between {{ ($settings['payment_gateway_active'] ?? 'midtrans') === 'midtrans' ? 'border-charcoal bg-sand/20' : 'border-sand bg-canvas' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-charcoal text-body-sm">Midtrans Snap</span>
                            <input type="radio" name="payment_gateway_active" value="midtrans" {{ ($settings['payment_gateway_active'] ?? 'midtrans') === 'midtrans' ? 'checked' : '' }} class="text-charcoal focus:ring-charcoal">
                        </div>
                        <p class="text-caption text-iron">VA BCA, Mandiri, BNI, BRI, QRIS, GoPay, Kartu Kredit via Popup resmi Snap.js.</p>
                    </label>

                    <label class="p-4 border rounded-card cursor-pointer flex flex-col justify-between {{ ($settings['payment_gateway_active'] ?? '') === 'sandbox' ? 'border-charcoal bg-sand/20' : 'border-sand bg-canvas' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-charcoal text-body-sm">Sandbox Mock</span>
                            <input type="radio" name="payment_gateway_active" value="sandbox" {{ ($settings['payment_gateway_active'] ?? '') === 'sandbox' ? 'checked' : '' }} class="text-charcoal focus:ring-charcoal">
                        </div>
                        <p class="text-caption text-iron">Simulasi pembayaran instan untuk testing dan demo tanpa memerlukan koneksi API.</p>
                    </label>

                    <label class="p-4 border rounded-card cursor-pointer flex flex-col justify-between {{ ($settings['payment_gateway_active'] ?? '') === 'manual' ? 'border-charcoal bg-sand/20' : 'border-sand bg-canvas' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-charcoal text-body-sm">Manual Transfer</span>
                            <input type="radio" name="payment_gateway_active" value="manual" {{ ($settings['payment_gateway_active'] ?? '') === 'manual' ? 'checked' : '' }} class="text-charcoal focus:ring-charcoal">
                        </div>
                        <p class="text-caption text-iron">Transfer rekening bank manual dengan konfirmasi pembayaran oleh admin.</p>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-sand">
                <div>
                    <label for="midtrans_client_key" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Midtrans Client Key</label>
                    <input type="text" name="midtrans_client_key" id="midtrans_client_key" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? '') }}" placeholder="SB-Mid-client-..." class="input-clean w-full font-mono text-body-sm">
                </div>

                <div>
                    <label for="midtrans_server_key" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Midtrans Server Key</label>
                    <input type="password" name="midtrans_server_key" id="midtrans_server_key" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? '') }}" placeholder="SB-Mid-server-..." class="input-clean w-full font-mono text-body-sm">
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="midtrans_is_production" value="0">
                    <input type="checkbox" name="midtrans_is_production" value="1" {{ ($settings['midtrans_is_production'] ?? '0') == '1' ? 'checked' : '' }} class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
                    <span class="text-body-sm font-medium text-charcoal">Gunakan Mode Production (Live Transaksi Riil)</span>
                </label>

                <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                    Simpan Pengaturan Pembayaran
                </button>
            </div>
        </form>
    </div>

    <!-- Group 4: Social & Contact Settings -->
    <div x-show="activeTab === 'social'" class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_group" value="social">

            <div class="border-b border-sand pb-4">
                <h2 class="font-bold text-lg text-charcoal">Tautan Media Sosial & Alamat Kontak</h2>
                <p class="text-caption text-iron">Tautan yang muncul pada footer global toko.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="instagram_url" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? 'https://instagram.com/fifa') }}" placeholder="https://instagram.com/..." class="input-clean w-full text-body-sm">
                </div>

                <div>
                    <label for="facebook_url" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? 'https://facebook.com/fifa') }}" placeholder="https://facebook.com/..." class="input-clean w-full text-body-sm">
                </div>

                <div>
                    <label for="tiktok_url" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">TikTok URL</label>
                    <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? 'https://tiktok.com/@fifa') }}" placeholder="https://tiktok.com/..." class="input-clean w-full text-body-sm">
                </div>

                <div>
                    <label for="youtube_url" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">YouTube URL</label>
                    <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/..." class="input-clean w-full text-body-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="store_address" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">Alamat Kantor / Flagship</label>
                    <textarea name="store_address" id="store_address" rows="2" class="input-clean w-full text-body-sm">{{ old('store_address', $settings['store_address'] ?? 'fifa Indonesia HQ, SCBD Lot 52-53, Jakarta Selatan 12190') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-end">
                <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
                    Simpan Pengaturan Sosial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
