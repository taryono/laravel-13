<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Kunci Jawaban (Bisa Lebih dari Satu Alias)</span>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="addAlias()">
            <i class="fa fa-plus"></i> Tambah Alias
        </button>
    </div>
    <div class="card-body">
        <small class="text-muted d-block mb-2">
            Tambahkan semua variasi jawaban yang dianggap benar. Pencocokan tidak case-sensitive.
        </small>
        <template x-for="(alias, i) in aliases" :key="i">
            <div class="row g-2 align-items-center mb-2">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Jawaban yang diterima" x-model="aliases[i]"
                        :name="'aliases[' + i + ']'">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeAlias(i)"
                        :disabled="aliases.length === 1">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
