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
                Judul Artikel <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $post->title ?? '') }}" 
                   required 
                   placeholder="Misal: Cerita di Balik Wol Merino Alami Selandia Baru" 
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
                   value="{{ old('slug', $post->slug ?? '') }}" 
                   placeholder="otomatis-dari-judul jika kosong" 
                   class="input-clean w-full font-mono text-body-sm @error('slug') border-red-500 @enderror">
        </div>

        <!-- Excerpt -->
        <div class="md:col-span-2">
            <label for="excerpt" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
                Ringkasan / Excerpt Artikel
            </label>
            <textarea name="excerpt" 
                      id="excerpt" 
                      rows="2" 
                      placeholder="Ringkasan singkat yang muncul pada kartu artikel dan daftar blog" 
                      class="input-clean w-full text-body-sm @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        </div>

        <!-- Cover Image -->
        <div class="md:col-span-2 space-y-3">
            <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal">
                Foto Sampul (Cover Image)
            </label>
            
            @if(isset($post) && $post->cover_image)
                <div class="flex items-center gap-4 p-3 border border-sand rounded-card bg-sand/10">
                    <img src="{{ $post->cover_image }}" alt="Cover" class="w-24 h-16 object-cover rounded-sm border border-sand">
                    <div class="text-caption text-stone">
                        <span class="font-bold text-charcoal block">Gambar Cover Saat Ini</span>
                        <span>Unggah gambar baru di bawah jika ingin mengganti.</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="cover_image" class="block text-caption text-iron mb-1">Unggah File (JPG, PNG, WEBP max 4MB):</label>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*" class="text-caption text-iron file:mr-4 file:py-2 file:px-4 file:rounded-pill file:border-0 file:text-caption file:font-semibold file:bg-charcoal file:text-canvas hover:file:bg-iron">
                </div>
                <div>
                    <label for="cover_image_url" class="block text-caption text-iron mb-1">Atau masukkan URL gambar langsung:</label>
                    <input type="url" name="cover_image_url" id="cover_image_url" placeholder="https://..." class="input-clean w-full text-body-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Quill Rich Text Editor for Content -->
    <div>
        <label class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-2">
            Isi Konten Artikel <span class="text-red-500">*</span>
        </label>
        
        <!-- Hidden input synced with Quill editor -->
        <input type="hidden" name="content" id="blog-content-input" value="{{ old('content', $post->content ?? '') }}">
        
        <!-- Quill Editor Container -->
        <div id="quill-editor" class="bg-canvas">
            {!! old('content', $post->content ?? '') !!}
        </div>
        <p class="text-caption text-stone mt-1">Gunakan toolbar untuk format teks editorial, quote, dan gambar. Konten aman & tersanitasi dari XSS.</p>
    </div>

    <!-- Publishing Status -->
    <div class="pt-6 border-t border-sand grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" 
                       name="is_published" 
                       value="1" 
                       {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }} 
                       class="rounded border-sand text-charcoal focus:ring-charcoal w-4 h-4">
                <span class="text-body-sm font-medium text-charcoal">Publikasikan artikel ini ke Jurnal Publik</span>
            </label>
        </div>

        <div>
            <label for="published_at" class="block text-caption font-bold uppercase tracking-wide10 text-charcoal mb-1">
                Tanggal Publikasi
            </label>
            <input type="date" 
                   name="published_at" 
                   id="published_at" 
                   value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d') : date('Y-m-d')) }}" 
                   class="input-clean w-full text-body-sm">
        </div>
    </div>

    <!-- Submit CTA -->
    <div class="flex items-center justify-end gap-3 pt-6 border-t border-sand">
        <a href="{{ route('admin.blog.index') }}" class="btn-pill-light text-caption px-6 py-2.5">
            Batal
        </a>
        <button type="submit" class="btn-pill-dark text-caption px-6 py-2.5">
            {{ isset($post) ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}
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
            placeholder: 'Tulis cerita artikel di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        const form = document.querySelector('form');
        const contentInput = document.getElementById('blog-content-input');

        form.addEventListener('submit', () => {
            contentInput.value = quill.root.innerHTML;
        });
    });
</script>
@endpush
