 <!-- ===== MODAL MENU GENERATOR ===== -->

 <div class="modal fade modal-custom" id="menuModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog-centered">
         <div class="modal-content">
             <form action="{{ route('crud_generator.menuGenerator') }}" method="POST" id="menuForm">
                 @csrf
                 <div class="modal-header">
                     <h5 class="modal-title" id="menuModalTitle">Tambah Menu</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                 </div>
                 <div class="modal-body row g-3">
                     <input type="hidden" name="id" id="menu_id">
                     <div class="col-md-6">
                         <label for="organization_id" class="form-label">Organisasi <span
                                 class="text-danger">*</span></label>
                         <select name="organization_id" id="organization_id"
                             class="form-select @error('organization_id') is-invalid @enderror" required>
                             <option value="">-- Pilih Organisasi --</option>
                             {{-- Ambil dari daftar organisasi --}}
                             @foreach ($organizations as $org)
                                 <option value="{{ $org->id }}"
                                     {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                     {{ $org->name }}
                                 </option>
                             @endforeach
                         </select>
                         @error('organization_id')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">Judul Menu (title) <span class="text-danger">*</span></label>
                         <input type="text" class="form-control" name="title" id="menu_title" required>
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">Flyout Title</label>
                         <input type="text" class="form-control" name="flyout_title" id="menu_flyout_title"
                             placeholder="Judul saat menu ini ditampilkan sebagai flyout (opsional)">
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">Indonesia</label>
                         <input type="text" class="form-control" name="id_text" id="menu_id_text"
                             placeholder="Judul indonesia">
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">English</label>
                         <input type="text" class="form-control" name="en_text" id="menu_en_text"
                             placeholder="Judul english">
                     </div>

                     <div class="col-md-6">
                         <label class="form-label">
                             Slug
                             <small class="text-muted">(kosongkan untuk auto-generate dari title)</small>
                         </label>
                         <input type="text" class="form-control" name="slug" id="menu_slug"
                             placeholder="mis. master-produk">
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">Route name</label>
                         <input type="text" class="form-control" name="route" id="menu_route">
                     </div>

                     <div class="col-md-6">
                         <label class="form-label">URL</label>
                         <input type="text" class="form-control" name="url" id="menu_url">
                     </div>
                     <div class="col-md-6">
                         <label class="form-label">Parent Menu</label>
                         <select class="form-select" name="parent_id" id="menu_parent_id">
                             <option value="">— Tidak ada (root) —</option>
                             @foreach ($parentOptions ?? [] as $menu)
                                 <option value="{{ $menu['id'] }}">{{ $menu['label'] }}</option>
                             @endforeach
                         </select>
                     </div>

                     <!-- ===== ICON PICKER MENU ===== -->
                     <div class="col-md-12">
                         <x-icon-picker id="menu-icon-picker" name="icon" typeName="icon_type"
                             value="fas fa-th-large" typeValue="fa" label="Icon Menu" :required="true" />
                     </div>

                     <div class="col-md-4">
                         <label class="form-label">Tampilkan di</label>
                         <select class="form-select" name="type" id="menu_type">
                             <option value="sidebar">Sidebar</option>
                             <option value="topbar">Topbar</option>
                         </select>
                     </div>

                     <div class="col-md-4">
                         <label class="form-label">Urutan (sort order)</label>
                         <input type="number" class="form-control" name="sort_order" id="menu_sort_order"
                             min="0" value="0">
                     </div>
                     <div class="col-md-4">
                         <div class="form-check form-switch mt-4">
                             <input class="form-check-input" type="checkbox" name="is_active" id="menu_is_active"
                                 checked>
                             <label class="form-check-label">Aktif</label>
                         </div>
                     </div>

                     <!-- ===== SELECT ROLES ===== -->
                     <div class="col-md-12">
                         <div class="card border-primary">
                             <div class="card-header bg-primary text-white">
                                 <h6 class="mb-0">
                                     <i class="fas fa-user-shield"></i> Assign ke Role
                                 </h6>
                             </div>
                             <div class="card-body">

                                 @foreach ($roles->chunk(3) ?? [] as $news)
                                     <div class="row g-2 mb-2">
                                         @foreach ($news as $role)
                                             <div class="col-4">
                                                 <div class="form-check">
                                                     <input class="form-check-input" type="checkbox" name="role_ids[]" id="role_{{ $role->id }}"
                                                         value="{{ $role->id }}">
                                                     <label class="form-check-label small" for="role_{{ $role->id }}">
                                                         {{ $role->display_name }}
                                                     </label>
                                                 </div>
                                             </div>
                                         @endforeach
                                     </div>
                                 @endforeach
                                 <small class="text-muted">
                                     <i class="fas fa-info-circle"></i>
                                     Pilih role yang akan mendapatkan akses ke menu ini
                                 </small>
                             </div>
                         </div>
                     </div>

                     <div class="col-12">
                         <div class="alert alert-light border small mb-0">
                             <i class="fas fa-info-circle text-info"></i>
                             Assignment <strong>Role</strong> (per menu) dan <strong>Action</strong>
                             (permission granular) dikelola lewat tombol
                             <em>Kelola Role</em> / <em>Kelola Action</em> di baris menu setelah menu ini disimpan.
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                     <button type="submit" class="btn btn-primary" id="submitMenuForm">
                         <i class="fas fa-save"></i> Simpan Menu
                     </button>
                 </div>
             </form>
         </div>
     </div>
 </div>
