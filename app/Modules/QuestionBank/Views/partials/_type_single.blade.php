{{-- resources/views/admin/question_banks/partials/_type_single.blade.php --}}
<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Opsi Jawaban (Pilih Satu Jawaban Benar)</span>
        <button type="button" class="btn btn-sm btn-outline-primary" x-show="typeCode === 'single_choice'"
            @click="addOption()">
            <i class="fa fa-plus"></i> Tambah Opsi
        </button>
    </div>
    <div class="card-body">
        <template x-for="(opt, i) in options" :key="opt._uid">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-auto">
                    <input type="radio" class="form-check-input mt-0" :name="'correct_single_' + opt._uid"
                        :checked="singleCorrectIndex === i" @change="setSingleCorrect(i)">
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
                <div class="col-auto" x-show="typeCode === 'single_choice'">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeOption(i)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>
        <small class="text-muted" x-show="typeCode === 'true_false'">
            Tipe Benar/Salah otomatis memakai 2 opsi tetap.
        </small>
        <template x-if="typeCode === 'single_choice' && singleCorrectIndex === -1">
            <small class="text-danger d-block mt-2">Pilih satu jawaban benar.</small>
        </template>
    </div>
</div>
