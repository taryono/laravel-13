<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Opsi Jawaban (Bisa Lebih dari Satu Benar)</span>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="addOption()">
            <i class="fa fa-plus"></i> Tambah Opsi
        </button>
    </div>
    <div class="card-body">
        <template x-for="(opt, i) in options" :key="opt._uid">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-auto">
                    <input type="checkbox" class="form-check-input mt-0" x-model="opt.is_correct">
                </div>
                <div class="col-1">
                    <input type="text" class="form-control text-center" placeholder="A" x-model="opt.label"
                        :name="'options[' + i + '][label]'">
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Teks opsi jawaban" x-model="opt.text"
                        :name="'options[' + i + '][text]'">
                </div>
                <input type="hidden" :name="'options[' + i + '][is_correct]'" :value="opt.is_correct ? 1 : 0">
                <input type="hidden" :name="'options[' + i + '][order_index]'" :value="i">
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeOption(i)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="partial_scoring" value="1"
                @checked(old('partial_scoring', $question->partial_scoring ?? false))>
            <label class="form-check-label">Beri nilai sebagian (partial scoring)</label>
        </div>
    </div>
</div>
