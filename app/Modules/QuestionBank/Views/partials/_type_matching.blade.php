<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Pasangan Menjodohkan</span>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="addPair()">
            <i class="fa fa-plus"></i> Tambah Pasangan
        </button>
    </div>
    <div class="card-body">
        <template x-for="(pair, i) in pairs" :key="pair._uid">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-5">
                    <input type="text" class="form-control" placeholder="Item kiri" x-model="pair.left"
                        :name="'pairs[' + i + '][left]'">
                </div>
                <div class="col-auto"><i class="fa fa-arrows-left-right"></i></div>
                <div class="col-5">
                    <input type="text" class="form-control" placeholder="Item kanan" x-model="pair.right"
                        :name="'pairs[' + i + '][right]'">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removePair(i)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
