{{-- resources/views/admin/question_banks/form.blade.php --}}
{{-- Dipakai bersama untuk Create & Edit. Butuh Alpine.js untuk bagian dinamis. --}}
@extends('layouts.app')

@section('title', isset($question) ? 'Edit Soal' : 'Tambah Soal')

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid">

        <h4 class="mb-3">{{ isset($question) ? 'Edit Soal' : 'Tambah Soal Baru' }}</h4>

        {{--
        questionTypeMap: dipakai Alpine untuk tahu "kode" tipe soal yang dipilih,
        karena question_type_id di DB cuma angka. Sesuaikan kode ini dengan
        kolom `code` di tabel question_types kamu, misalnya:
        single_choice, multi_choice, true_false, matching, ordering,
        short_answer, essay
    --}}
        <form action="{{ isset($question) ? route('question_bank.update', $question->id) : route('question_bank.store') }}"
            method="POST" enctype="multipart/form-data" x-data="questionForm({
                typeMap: {{ $questionTypes->pluck('code', 'id')->toJson() }},
                initialTypeId: '{{ old('question_type_id', $question->question_type_id ?? '') }}',
                initialOptions: {{ old('options', isset($question) ? $question->options->map(fn($o) => ['label' => $o->option_label, 'text' => $o->option_text, 'is_correct' => (bool) $o->is_correct, 'order_index' => $o->order_index])->toJson() : '[]') }},
                initialPairs: {{ old('pairs', isset($question) ? $question->matchingPairs->map(fn($p) => ['left' => $p->left_item, 'right' => $p->right_item])->toJson() : '[]') }},
                initialAliases: {{ old('aliases', isset($question) && $question->correct_answer_text ? json_encode($question->correct_answer_text) : '[]') }}
            })" x-init="init()">
            @csrf
            @if (isset($question))
                @method('PUT')
            @endif

            {{-- ===================== DATA UMUM ===================== --}}
            <div class="card mb-3">
                <div class="card-header fw-bold">Data Umum</div>
                <div class="card-body row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $question->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipe Soal <span class="text-danger">*</span></label>
                        <select name="question_type_id" class="form-select" x-model="typeId" required>
                            <option value="">-- Pilih Tipe Soal --</option>
                            @foreach ($questionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Mengubah tipe soal akan mengosongkan bagian jawaban di bawah.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Poin <span class="text-danger">*</span></label>
                        <input type="number" name="points" class="form-control" min="1" required
                            value="{{ old('points', $question->points ?? 10) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                        <select name="difficulty_level_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($difficultyLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('difficulty_level_id', $question->difficulty_level_id ?? '') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Level Kognitif <span class="text-danger">*</span></label>
                        <select name="cognitive_level_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($cognitiveLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('cognitive_level_id', $question->cognitive_level_id ?? '') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }} @if ($level->code)
                                        ({{ $level->code }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tag</label>
                        <input type="text" name="tags_input" class="form-control"
                            placeholder="pisahkan dengan koma, contoh: aljabar, kelas-10"
                            value="{{ old('tags_input', isset($question->tags) ? implode(', ', $question->tags ?? []) : '') }}">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="use_stimulus" value="1"
                                x-model="useStimulus"
                                {{ old('use_stimulus', $question->stimulus_id ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Soal ini bagian dari wacana/stimulus berkelompok</label>
                        </div>
                    </div>

                    <div class="col-md-8" x-show="useStimulus" x-cloak>
                        <label class="form-label">Pilih Stimulus</label>
                        <select name="stimulus_id" class="form-select">
                            <option value="">-- Pilih Stimulus yang Sudah Ada --</option>
                            @foreach ($stimuli as $stimulus)
                                <option value="{{ $stimulus->id }}"
                                    {{ old('stimulus_id', $question->stimulus_id ?? '') == $stimulus->id ? 'selected' : '' }}>
                                    {{ $stimulus->title ?? 'Stimulus #' . $stimulus->id }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Belum ada stimulus yang cocok?
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg"
                                data-method="GET" data-title="Tambah Data Stimulus Baru"
                                data-url="{{ route('question_stimuli.create') }}"
                                data-submit='<button class="btn btn-primary" type="submit" form="formQuestionStimuli">Simpan</button>'
                                data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                                <i class="bi bi-plus-circle"></i> Stimulus Baru
                            </button>
                        </small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Teks Soal <span class="text-danger">*</span></label>
                        <textarea name="question_text" rows="4" class="form-control" required placeholder="Tulis pertanyaan di sini...">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Lampiran Media Soal (opsional)</label>
                        <input type="file" name="question_media[]" class="form-control" multiple
                            accept="image/*,audio/*,video/*">
                        <small class="text-muted">Bisa upload lebih dari satu file (gambar/audio/video).</small>
                    </div>

                </div>
            </div>

            {{-- ===================== BAGIAN DINAMIS PER TIPE SOAL ===================== --}}

            {{-- ---------- PILIHAN GANDA (single) & BENAR-SALAH ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'single_choice' || typeCode === 'true_false'" x-cloak>
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Opsi Jawaban (Pilih Satu Jawaban Benar)</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" x-show="typeCode === 'single_choice'"
                        @click="addOption()">
                        <i class="fa fa-plus"></i> Tambah Opsi
                    </button>
                </div>
                <div class="card-body">
                    <template x-for="(opt, i) in options" :key="i">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-auto">
                                <input type="radio" name="correct_single" class="form-check-input mt-0"
                                    :checked="opt.is_correct" @change="setSingleCorrect(i)">
                            </div>
                            <div class="col-1">
                                <input type="text" class="form-control text-center" placeholder="A"
                                    x-model="opt.label" :name="'options[' + i + '][label]'">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Teks opsi jawaban"
                                    x-model="opt.text" :name="'options[' + i + '][text]'">
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
                </div>
            </div>

            {{-- ---------- PILIHAN GANDA KOMPLEKS (multi-select) ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'multi_choice'" x-cloak>
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Opsi Jawaban (Bisa Lebih dari Satu Jawaban Benar)</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addOption()">
                        <i class="fa fa-plus"></i> Tambah Opsi
                    </button>
                </div>
                <div class="card-body">
                    <template x-for="(opt, i) in options" :key="i">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-auto">
                                <input type="checkbox" class="form-check-input mt-0" x-model="opt.is_correct">
                            </div>
                            <div class="col-1">
                                <input type="text" class="form-control text-center" placeholder="A"
                                    x-model="opt.label" :name="'options[' + i + '][label]'">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Teks opsi jawaban"
                                    x-model="opt.text" :name="'options[' + i + '][text]'">
                            </div>
                            <input type="hidden" :name="'options[' + i + '][is_correct]'"
                                :value="opt.is_correct ? 1 : 0">
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
                            {{ old('partial_scoring', $question->partial_scoring ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Beri nilai sebagian (partial scoring) jika jawaban tidak lengkap
                            benar</label>
                    </div>
                </div>
            </div>

            {{-- ---------- URUTKAN (ordering) ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'ordering'" x-cloak>
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Item Urutan (Susun Sesuai Urutan yang Benar)</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addOption()">
                        <i class="fa fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">Urutan baris di bawah ini = urutan jawaban yang benar. Gunakan
                        tombol naik/turun untuk mengatur.</small>
                    <template x-for="(opt, i) in options" :key="i">
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
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    @click="moveOption(i, -1)" :disabled="i === 0">
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" @click="moveOption(i, 1)"
                                    :disabled="i === options.length - 1">
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

            {{-- ---------- MENJODOHKAN (matching) ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'matching'" x-cloak>
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Pasangan Menjodohkan</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addPair()">
                        <i class="fa fa-plus"></i> Tambah Pasangan
                    </button>
                </div>
                <div class="card-body">
                    <template x-for="(pair, i) in pairs" :key="i">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Item kiri" x-model="pair.left"
                                    :name="'pairs[' + i + '][left]'">
                            </div>
                            <div class="col-auto"><i class="fa fa-arrows-left-right"></i></div>
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Item kanan (pasangannya)"
                                    x-model="pair.right" :name="'pairs[' + i + '][right]'">
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

            {{-- ---------- ISIAN SINGKAT (short answer) ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'short_answer'" x-cloak>
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Kunci Jawaban (Bisa Lebih dari Satu Alias)</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="addAlias()">
                        <i class="fa fa-plus"></i> Tambah Alias
                    </button>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        Tambahkan semua variasi jawaban yang dianggap benar, contoh: "Jakarta", "DKI Jakarta", "kota
                        jakarta".
                        Pencocokan tidak case-sensitive.
                    </small>
                    <template x-for="(alias, i) in aliases" :key="i">
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Jawaban yang diterima"
                                    x-model="aliases[i]" :name="'aliases[' + i + ']'">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeAlias(i)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ---------- ESAI ---------- --}}
            <div class="card mb-3" x-show="typeCode === 'essay'" x-cloak>
                <div class="card-header fw-bold">Rubrik Penilaian (opsional)</div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        Soal esai tidak punya kunci jawaban otomatis — akan dikoreksi manual oleh guru.
                        Rubrik ini hanya panduan, tidak memengaruhi penilaian sistem.
                    </small>
                    <textarea name="essay_rubric" rows="3" class="form-control"
                        placeholder="Contoh: skor 10 jika menyebutkan 3 poin utama, skor 5 jika hanya 1-2 poin...">{{ old('essay_rubric', $question->explanation ?? '') }}</textarea>
                </div>
            </div>

            {{-- ===================== PEMBAHASAN & PENGATURAN AKHIR ===================== --}}
            <div class="card mb-3">
                <div class="card-header fw-bold">Pembahasan &amp; Pengaturan</div>
                <div class="card-body row g-3">
                    <div class="col-12" x-show="typeCode !== 'essay'">
                        <label class="form-label">Pembahasan Jawaban (opsional)</label>
                        <textarea name="explanation" rows="3" class="form-control"
                            placeholder="Penjelasan kenapa jawaban tersebut benar...">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1"
                                {{ old('is_public', $question->is_public ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Soal publik (bisa dipakai guru lain)</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- hidden field: scoring_type otomatis mengikuti tipe soal --}}
            <input type="hidden" name="scoring_type" :value="typeCode === 'essay' ? 'manual' : 'auto'">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan Soal
                </button>
                <a href="{{ route('question_bank.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>

    <script>
        function questionForm({
            typeMap,
            initialTypeId,
            initialOptions,
            initialPairs,
            initialAliases
        }) {
            return {
                typeMap,
                typeId: initialTypeId,
                typeCode: '',
                useStimulus: false,
                options: initialOptions && initialOptions.length ? initialOptions : [],
                pairs: initialPairs && initialPairs.length ? initialPairs : [],
                aliases: initialAliases && initialAliases.length ? initialAliases : [''],

                init() {
                    this.$watch('typeId', (val) => this.onTypeChange(val));
                    this.onTypeChange(this.typeId, true);
                },

                onTypeChange(val, isInitial = false) {
                    this.typeCode = this.typeMap[val] || '';

                    if (isInitial) return; // jangan reset data saat pertama kali load (mode edit)

                    // reset semua data dinamis setiap kali tipe soal diganti user
                    this.options = [];
                    this.pairs = [];
                    this.aliases = [''];

                    if (this.typeCode === 'true_false') {
                        this.options = [{
                                label: 'B',
                                text: 'Benar',
                                is_correct: false,
                                order_index: 0
                            },
                            {
                                label: 'S',
                                text: 'Salah',
                                is_correct: false,
                                order_index: 1
                            },
                        ];
                    } else if (['single_choice', 'multi_choice', 'ordering'].includes(this.typeCode)) {
                        this.addOption();
                        this.addOption();
                    } else if (this.typeCode === 'matching') {
                        this.addPair();
                        this.addPair();
                    }
                },

                addOption() {
                    const labels = 'ABCDEFGH';
                    this.options.push({
                        label: labels[this.options.length] || '',
                        text: '',
                        is_correct: false,
                        order_index: this.options.length,
                    });
                },
                removeOption(i) {
                    this.options.splice(i, 1);
                },
                moveOption(i, dir) {
                    const target = i + dir;
                    if (target < 0 || target >= this.options.length) return;
                    [this.options[i], this.options[target]] = [this.options[target], this.options[i]];
                },
                setSingleCorrect(i) {
                    this.options.forEach((o, idx) => o.is_correct = (idx === i));
                },

                addPair() {
                    this.pairs.push({
                        left: '',
                        right: ''
                    });
                },
                removePair(i) {
                    this.pairs.splice(i, 1);
                },

                addAlias() {
                    this.aliases.push('');
                },
                removeAlias(i) {
                    this.aliases.splice(i, 1);
                },
            };
        }
    </script>
@endsection
