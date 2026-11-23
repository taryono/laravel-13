<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Item Urutan (Susun Sesuai Urutan yang Benar)</span>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="addOption()">
            <i class="fa fa-plus"></i> Tambah Item
        </button>
    </div>
    <div class="card-body">
        <small class="text-muted d-block mb-2">
            Urutan baris = urutan jawaban benar. Gunakan tombol naik/turun.
        </small>
        <template x-for="(opt, i) in options" :key="opt._uid">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-auto">
                    <span class="badge bg-secondary" x-text="i + 1"></span>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Teks item" x-model="opt.text"
                        :name="'options[' + i + '][text]'">
                </div>
                <input type="hidden" :name="'options[' + i + '][order_index]'" :value="i">
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="moveOption(i, -1)"
                        :disabled="i === 0">
                        <i class="fa fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="moveOption(i, 1)"
                        :disabled="i === options.length_1">
                        <i class="fa fa-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeOption(i)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
