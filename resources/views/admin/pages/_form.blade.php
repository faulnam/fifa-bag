@push('styles')
<!-- Quill CDN CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-toolbar.ql-snow {
        border-color: #e0dacf !important;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        background-color: #ece9e2;
    }
    .ql-container.ql-snow {
        border-color: #e0dacf !important;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        min-height: 250px;
    }
</style>
@endpush

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
        <!-- Title -->
        <div>
            <label for="title" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Judul Halaman <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $page->title ?? '') }}" 
                   required 
                   placeholder="Misal: Tentang Kami, FAQ & Bantuan, Keberlanjutan" 
                   class="input-clean w-full font-bold text-body @error('title') border-red-500 @enderror">
        </div>

        <!-- Slug URL -->
        <div>
            <label for="slug" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Slug URL (Opsional)
            </label>
            <input type="text" 
                   name="slug" 
                   id="slug" 
                   value="{{ old('slug', $page->slug ?? '') }}" 
                   placeholder="otomatis-dari-judul jika kosong" 
                   class="input-clean w-full font-mono text-body-sm @error('slug') border-red-500 @enderror">
        </div>
    </div>

    <!-- Quill Rich Text Editor for Content -->
    <div>
        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
            Isi Konten Halaman <span class="text-red-500">*</span>
        </label>
        
        <!-- Hidden input synced with Quill editor -->
        <input type="hidden" name="content" id="page-content-input" value="{{ old('content', $page->content ?? '') }}">
        
        <!-- Quill Editor Container -->
        <div id="quill-editor" class="bg-canvas">
            {!! old('content', $page->content ?? '') !!}
        </div>
        <p class="text-caption text-stone mt-1">Gunakan toolbar untuk format teks, judul, link, dan list. Konten disanitasi secara otomatis untuk keamanan.</p>
    </div>

    <!-- SEO Meta Tags -->
    <div class="pt-6 border-t border-sand space-y-4">
        <h3 class="text-caption font-bold uppercase tracking-wide10 text-charcoal">Optimasi SEO (Search Engine)</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="meta_title" class="block text-caption font-medium text-iron mb-1">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" placeholder="Judul pada Google Search" class="input-clean w-full text-body-sm">
            </div>

            <div>
                <label for="meta_description" class="block text-caption font-medium text-iron mb-1">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2" placeholder="Ringkasan isi halaman untuk mesin pencari" class="input-clean w-full text-body-sm">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Submit CTA -->
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-sand">
        <a href="{{ route('admin.pages.index') }}" class="btn-pill-light text-caption px-6 py-2.5">
            Batal
        </a>
        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
            {{ isset($page) ? 'Simpan Perubahan' : 'Buat Halaman' }}
        </button>
    </div>
</div>

@push('scripts')
<!-- Quill CDN JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis konten halaman di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        const form = document.querySelector('form');
        const contentInput = document.getElementById('page-content-input');

        form.addEventListener('submit', () => {
            contentInput.value = quill.root.innerHTML;
        });
    });
</script>
@endpush
