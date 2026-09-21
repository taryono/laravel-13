{{-- resources/views/admin/question_banks/form.blade.php --}}
@extends('layouts.app')

@section('title', isset($question) ? 'Edit Soal' : 'Tambah Soal')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@php
    /*
     |------------------------------------------------------------------
     | Siapkan data awal untuk Alpine. Semua pakai @json() supaya aman
     | dari XSS / parse error.
     |------------------------------------------------------------------
     */
    $isEdit = isset($question);

    // typeMap: [id => code]
    $typeMap = $questionTypes->pluck('code', 'id')->all();

    // Type id awal
    $initialTypeId = old('question_type_id', $question->question_type_id ?? '');

    // Options awal
    if (old('options')) {
        $initialOptions = old('options');
    } elseif ($isEdit && $question->relationLoaded('options')) {
        $initialOptions = $question->options
            ->map(
                fn($o) => [
                    'label' => $o->option_label,
                    'text' => $o->option_text,
                    'is_correct' => (bool) $o->is_correct,
                    'order_index' => (int) $o->order_index,
                ],
            )
            ->values()
            ->all();
    } else {
        $initialOptions = [];
    }

    // Pairs awal
    if (old('pairs')) {
        $initialPairs = old('pairs');
    } elseif ($isEdit && $question->relationLoaded('matchingPairs')) {
        $initialPairs = $question->matchingPairs
            ->map(
                fn($p) => [
                    'left' => $p->left_item,
                    'right' => $p->right_item,
                ],
            )
            ->values()
            ->all();
    } else {
        $initialPairs = [];
    }

    // Aliases awal (short answer)
    if (old('aliases')) {
        $initialAliases = array_values(old('aliases'));
    } elseif ($isEdit && !empty($question->correct_answer_text)) {
        $cat = $question->correct_answer_text;
        $initialAliases = is_array($cat) ? array_values($cat) : [(string) $cat];
    } else {
        $initialAliases = [];
    }

    // useStimulus
    $initialUseStimulus = (bool) old('use_stimulus', $question->stimulus_id ?? false);
@endphp

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="{{ asset('js/question-form.js') }}"></script>
@endpush

@section('content')
    <div class="container-fluid">

        <h4 class="mb-3">{{ $isEdit ? 'Edit Soal' : 'Tambah Soal Baru' }}</h4>

        <form action="{{ $isEdit ? route('question_bank.update', $question->id) : route('question_bank.store') }}"
            method="POST" enctype="multipart/form-data" x-data="questionForm({
                typeMap: @json($typeMap),
                initialTypeId: @json((string) $initialTypeId),
                initialOptions: @json($initialOptions),
                initialPairs: @json($initialPairs),
                initialAliases: @json($initialAliases),
                useStimulus: @json($initialUseStimulus),
            })" x-init="init()"
            @submit="onSubmit($event)">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- Error summary --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali input Anda:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ============ DATA UMUM ============ --}}
            @include('admin.question_banks.partials._general', [
                'subjects' => $subjects,
                'questionTypes' => $questionTypes,
                'difficultyLevels' => $difficultyLevels,
                'cognitiveLevels' => $cognitiveLevels,
                'stimuli' => $stimuli,
                'question' => $question ?? null,
            ])

            {{-- ============ TIPE: SINGLE & TRUE/FALSE ============ --}}
            <div x-show="typeCode === 'single_choice' || typeCode === 'true_false'" x-cloak>
                @include('admin.question_banks.partials._type_single')
            </div>

            {{-- ============ TIPE: MULTI ============ --}}
            <div x-show="typeCode === 'multi_choice'" x-cloak>
                @include('admin.question_banks.partials._type_multi', ['question' => $question ?? null])
            </div>

            {{-- ============ TIPE: ORDERING ============ --}}
            <div x-show="typeCode === 'ordering'" x-cloak>
                @include('admin.question_banks.partials._type_ordering')
            </div>

            {{-- ============ TIPE: MATCHING ============ --}}
            <div x-show="typeCode === 'matching'" x-cloak>
                @include('admin.question_banks.partials._type_matching')
            </div>

            {{-- ============ TIPE: SHORT ANSWER ============ --}}
            <div x-show="typeCode === 'short_answer'" x-cloak>
                @include('admin.question_banks.partials._type_short')
            </div>

            {{-- ============ TIPE: ESSAY ============ --}}
            <div x-show="typeCode === 'essay'" x-cloak>
                @include('admin.question_banks.partials._type_essay', ['question' => $question ?? null])
            </div>

            {{-- ============ PEMBAHASAN & SETTING ============ --}}
            @include('admin.question_banks.partials._settings', ['question' => $question ?? null])

            {{-- scoring_type: essay = manual, lainnya auto --}}
            <input type="hidden" name="scoring_type" :value="typeCode === 'essay' ? 'manual' : 'auto'">

            <div class="d-flex gap-2 mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan Soal
                </button>
                <a href="{{ route('question_bank.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
