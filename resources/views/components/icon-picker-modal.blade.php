@props(['id' => 'icon-picker-modal'])

<div class="modal fade icon-picker-modal" id="{{ $id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-icons"></i> Pilih Icon Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="icon-search-box">
                    <input type="text" class="search-input" id="{{ $id }}-search" placeholder="🔍 Cari icon...">
                    
                    <div class="type-selector">
                        <button class="btn-type active" data-type="fa" data-modal="{{ $id }}">
                            <i class="fas fa-font-awesome"></i> Font Awesome
                        </button>
                        <button class="btn-type" data-type="material" data-modal="{{ $id }}">
                            <span class="material-symbols-outlined" style="font-size: 1rem;">search</span> Material
                        </button>
                    </div>
                </div>

                <div class="icon-counter" id="{{ $id }}-counter">Menampilkan <span id="{{ $id }}-count">0</span> icon</div>

                <div class="icon-grid" id="{{ $id }}-grid"></div>

                <div class="icon-empty" id="{{ $id }}-empty" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>Tidak ada icon yang ditemukan</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" id="{{ $id }}-cancel">Batal</button>
                <button class="btn btn-primary" id="{{ $id }}-confirm">
                    <i class="fas fa-check me-2"></i> Pilih Icon
                </button>
            </div>
        </div>
    </div>
</div>