<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-card text-body-sm space-y-1">
            <span class="font-bold block">Terdapat kesalahan pada formulir:</span>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Coupon Code -->
        <div>
            <label for="code" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Kode Kupon Promo <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="code" 
                   id="code" 
                   value="{{ old('code', $coupon->code ?? '') }}" 
                   required 
                   placeholder="MISAL: DISKON50, FIFANEW" 
                   class="input-clean w-full uppercase font-mono text-body font-bold @error('code') border-red-500 @enderror">
            <p class="text-caption text-stone mt-1">Gunakan huruf kapital & angka tanpa spasi.</p>
        </div>

        <!-- Coupon Type -->
        <div>
            <label for="type" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Tipe Diskon <span class="text-red-500">*</span>
            </label>
            <select name="type" id="type" required class="input-clean w-full @error('type') border-red-500 @enderror">
                <option value="percent" {{ old('type', $coupon->type ?? 'percent') === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
            </select>
        </div>

        <!-- Discount Value -->
        <div>
            <label for="value" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Nilai Diskon <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   name="value" 
                   id="value" 
                   step="any"
                   min="0"
                   value="{{ old('value', isset($coupon) ? (int)$coupon->value : '') }}" 
                   required 
                   placeholder="Mis. 20 untuk 20% atau 50000 untuk Rp 50.000" 
                   class="input-clean w-full @error('value') border-red-500 @enderror">
        </div>

        <!-- Usage Quota Limit -->
        <div>
            <label for="usage_limit" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Batas Penggunaan (Kuota)
            </label>
            <input type="number" 
                   name="usage_limit" 
                   id="usage_limit" 
                   min="1"
                   value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" 
                   placeholder="Kosongkan jika kuota tidak terbatas (unlimited)" 
                   class="input-clean w-full @error('usage_limit') border-red-500 @enderror">
        </div>

        <!-- Min Purchase Requirement -->
        <div>
            <label for="min_purchase" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Minimum Belanja (Rp)
            </label>
            <input type="number" 
                   name="min_purchase" 
                   id="min_purchase" 
                   min="0"
                   value="{{ old('min_purchase', isset($coupon) ? (int)$coupon->min_purchase : '') }}" 
                   placeholder="Mis. 200000 (Opsional)" 
                   class="input-clean w-full @error('min_purchase') border-red-500 @enderror">
        </div>

        <!-- Max Discount Limit (for percent discount) -->
        <div>
            <label for="max_discount" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Maksimal Potongan Diskon (Rp)
            </label>
            <input type="number" 
                   name="max_discount" 
                   id="max_discount" 
                   min="0"
                   value="{{ old('max_discount', isset($coupon) ? (int)$coupon->max_discount : '') }}" 
                   placeholder="Mis. 100000 (Maksimal potongan untuk tipe %)" 
                   class="input-clean w-full @error('max_discount') border-red-500 @enderror">
        </div>

        <!-- Validity Start Date -->
        <div>
            <label for="starts_at" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Tanggal Mulai Berlaku
            </label>
            <input type="date" 
                   name="starts_at" 
                   id="starts_at" 
                   value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}" 
                   class="input-clean w-full @error('starts_at') border-red-500 @enderror">
            <p class="text-caption text-stone mt-1">Kosongkan jika kupon langsung berlaku hari ini.</p>
        </div>

        <!-- Validity Expiry Date -->
        <div>
            <label for="expires_at" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Tanggal Berakhir (Expired)
            </label>
            <input type="date" 
                   name="expires_at" 
                   id="expires_at" 
                   value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" 
                   class="input-clean w-full @error('expires_at') border-red-500 @enderror">
            <p class="text-caption text-stone mt-1">Harus sama dengan atau setelah tanggal mulai.</p>
        </div>
    </div>

    <!-- Active Status Toggle -->
    <div class="pt-4 border-t border-sand">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" 
                   name="is_active" 
                   value="1" 
                   {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }} 
                   class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
            <span class="text-body-sm font-medium text-charcoal">Aktifkan kupon promo ini untuk digunakan customer saat checkout</span>
        </label>
    </div>

    <!-- Submit CTA -->
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-sand">
        <a href="{{ route('admin.coupons.index') }}" class="btn-pill-light text-caption px-6 py-2.5">
            Batal
        </a>
        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
            {{ isset($coupon) ? 'Simpan Perubahan' : 'Buat Kupon Promo' }}
        </button>
    </div>
</div>
