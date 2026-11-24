 <div class="row g-4">
     {{-- KOLOM KIRI (8/12): KONTEN UTAMA & SEO --}}
     <div class="col-lg-8">
         <div class="mb-3">
             <label for="title" class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
             <input type="text" name="title" id="title" maxlength="191"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title', $post->title ?? '') }}" required>
             @error('title')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>

         <div class="mb-3">
             <label for="slug" class="form-label fw-semibold">Slug URL <span class="text-danger">*</span></label>
             <div class="input-group input-group-sm">
                 <span class="input-group-text text-muted">/berita/</span>
                 <input type="text" name="slug" id="slug" maxlength="191"
                     class="form-control @error('slug') is-invalid @enderror"
                     value="{{ old('slug', $post->slug ?? '') }}" required>
             </div>
             @error('slug')
                 <div class="invalid-feedback d-block">{{ $message }}</div>
             @enderror
         </div>

         <div class="mb-3">
             <label for="excerpt" class="form-label fw-semibold">Ringkasan (Excerpt)</label>
             <textarea name="excerpt" id="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror"
                 placeholder="Ringkasan singkat artikel untuk kartu pratinjau...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
             @error('excerpt')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>

         <div class="mb-3">
             <label for="content" class="form-label fw-semibold">Isi Artikel</label>
             <textarea name="content" id="content" rows="10"
                 class="form-control rich-editor @error('content') is-invalid @enderror">{{ old('content', $post->content ?? '') }}</textarea>
             @error('content')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>

         {{-- Accordion Pengaturan SEO --}}
         <div class="accordion" id="seoAccordion">
             <div class="accordion-item">
                 <h2 class="accordion-header">
                     <button class="accordion-button collapsed py-2 fw-semibold" type="button"
                         data-bs-toggle="collapse" data-bs-target="#collapseSeo">
                         Pengaturan Meta SEO (Opsional)
                     </button>
                 </h2>
                 <div id="collapseSeo" class="accordion-collapse collapse" data-bs-parent="#seoAccordion">
                     <div class="accordion-body">
                         <div class="mb-2">
                             <label for="meta_title" class="form-label small fw-semibold">Meta Title</label>
                             <input type="text" name="meta_title" id="meta_title" maxlength="191"
                                 class="form-control form-control-sm"
                                 value="{{ old('meta_title', $post->meta_title ?? '') }}">
                         </div>
                         <div class="mb-2">
                             <label for="meta_description" class="form-label small fw-semibold">Meta Description</label>
                             <textarea name="meta_description" id="meta_description" rows="2" maxlength="191"
                                 class="form-control form-control-sm">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                         </div>
                         <div>
                             <label for="meta_keywords" class="form-label small fw-semibold">Meta Keywords</label>
                             <input type="text" name="meta_keywords" id="meta_keywords" maxlength="191"
                                 class="form-control form-control-sm" placeholder="ppdb, pengumuman sekolah, prestasi"
                                 value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}">
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     {{-- KOLOM KANAN (4/12): PUBLIKASI, KATEGORI & GAMBAR UNGGULAN --}}
     <div class="col-lg-4">
         <div class="card bg-light border mb-3">
             <div class="card-body">
                 <h6 class="fw-bold mb-3">Publikasi</h6>

                 <div class="mb-3">
                     <label for="status" class="form-label small fw-semibold">Status <span
                             class="text-danger">*</span></label>
                     <select name="status" id="status" class="form-select form-select-sm" required>
                         @foreach ($statuses as $st)
                             <option value="{{ $st->value }}" @selected(old('status', isset($post) ? $post->status->value : 'draft') === $st->value)>
                                 {{ $st->label() }}
                             </option>
                         @endforeach
                     </select>
                 </div>

                 <div class="mb-3">
                     <label for="published_at" class="form-label small fw-semibold">Tanggal &amp; Jam Terbit</label>
                     <input type="datetime-local" name="published_at" id="published_at"
                         class="form-control form-control-sm"
                         value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                     <div class="form-text" style="font-size: 0.72rem;">Wajib diisi jika status
                         <strong>Terjadwal</strong>.
                     </div>
                 </div>

                 <div class="mb-3">
                     <label for="cms_category_id" class="form-label small fw-semibold">Kategori</label>
                     <select name="cms_category_id" id="cms_category_id" class="form-select form-select-sm">
                         <option value="">-- Tanpa Kategori --</option>
                         @foreach ($categories as $cat)
                             <option value="{{ $cat->id }}" @selected(old('cms_category_id', $post->cms_category_id ?? null) == $cat->id)>
                                 {{ $cat->name }}
                             </option>
                         @endforeach
                     </select>
                 </div>

                 <div class="form-check form-switch mb-2">
                     <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                         value="1" @checked(old('is_featured', $post->is_featured ?? false))>
                     <label class="form-check-label small fw-semibold" for="is_featured">Artikel Unggulan
                         (Featured)</label>
                 </div>

                 <div class="form-check form-switch">
                     <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments"
                         value="1" @checked(old('allow_comments', $post->allow_comments ?? true))>
                     <label class="form-check-label small fw-semibold" for="allow_comments">Izinkan Komentar</label>
                 </div>
             </div>
         </div>

         {{-- Kartu Featured Image --}}
         <div class="card bg-light border">
             <div class="card-body">
                 <h6 class="fw-bold mb-2">Gambar Unggulan (Cover)</h6>

                 @if (isset($post) && $post->featured_image_url)
                     <div class="mb-2 text-center">
                         <img src="{{ $post->featured_image_url }}" class="img-fluid rounded border mb-2"
                             style="max-height: 150px;" alt="Preview">
                         <div class="form-check text-start">
                             <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image"
                                 value="1">
                             <label class="form-check-label small text-danger" for="remove_image">Hapus gambar saat
                                 ini</label>
                         </div>
                     </div>
                 @endif

                 <input type="file" name="featured_image" id="featured_image"
                     accept="image/jpeg,image/png,image/webp"
                     class="form-control form-control-sm @error('featured_image') is-invalid @enderror">
                 <div class="form-text" style="font-size: 0.72rem;">Format JPG, PNG, WebP. Maksimal 2 MB.</div>
                 @error('featured_image')
                     <div class="invalid-feedback">{{ $message }}</div>
                 @enderror
             </div>
         </div>
     </div>
 </div>


 <script>
     (function() {
         const titleInput = document.getElementById('title');
         const slugInput = document.getElementById('slug');
         let slugEdited = {{ isset($post) ? 'true' : 'false' }};

         if (slugInput) {
             slugInput.addEventListener('input', () => {
                 slugEdited = true;
             });
         }

         if (titleInput && slugInput) {
             titleInput.addEventListener('input', function() {
                 if (slugEdited) return;
                 slugInput.value = this.value
                     .toLowerCase()
                     .trim()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-');
             });
         }
     })();
 </script>
 <script>
     (function() {
         // 1. Bersihkan instance TinyMCE lama jika modal dibuka ulang
         if (typeof tinymce !== 'undefined') {
             const existingEditor = tinymce.get('content');
             if (existingEditor) {
                 existingEditor.remove();
             }

             // 2. Inisialisasi TinyMCE pada textarea#content
             tinymce.init({
                 selector: '#content',
                 height: 380,
                 menubar: false,
                 branding: false,
                 promotion: false,
                 plugins: 'lists link image table code wordcount autoresize',
                 toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code',
                 content_style: 'body { font-family: Inter, system-ui, sans-serif; font-size: 14px; }',

                 // Otomatis sinkronisasi isi editor ke <textarea> setiap kali mengetik
                 setup: function(editor) {
                     editor.on('change keyup', function() {
                         editor.save();
                     });
                 },

                 // Opsional: Upload gambar langsung dari dalam editor ke server Laravel
                 images_upload_url: '{{ route('cms_post.upload_image') }}',
                 automatic_uploads: true,
                 images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                     const formData = new FormData();
                     formData.append('file', blobInfo.blob(), blobInfo.filename());

                     fetch('{{ route('cms_post.upload_image') }}', {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': document.querySelector(
                                     'meta[name="csrf-token"]')?.content || '',
                                 'Accept': 'application/json'
                             },
                             body: formData
                         })
                         .then(res => res.ok ? res.json() : Promise.reject('Upload gagal (' + res
                             .status + ')'))
                         .then(data => resolve(data.location))
                         .catch(err => reject(err));
                 })
             });
         }

         // 3. Pastikan triggerSave() dipanggil saat tombol .modal-submit diklik
         document.querySelectorAll('.modal-submit').forEach(btn => {
             btn.addEventListener('click', function() {
                 if (typeof tinymce !== 'undefined') {
                     tinymce.triggerSave();
                 }
             });
         });
     })();
 </script>
