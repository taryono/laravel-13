<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use App\Models\QuestionMatchingPair;
use App\Models\QuestionMedia;
use App\Models\Subject;
use App\Models\QuestionType;
use App\Models\DifficultyLevel;
use App\Models\CognitiveLevel;
use App\Models\QuestionStimulus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionBankController extends Controller
{
    public function create()
    {
        return view('admin.question_banks.form', $this->formData());
    }

    public function edit(QuestionBank $questionBank)
    {
        $questionBank->load(['options', 'matchingPairs']);

        return view('admin.question_banks.form', array_merge(
            $this->formData(),
            ['question' => $questionBank]
        ));
    }

    public function store(Request $request)
    {
        $this->saveQuestion($request);

        return redirect()
            ->route('admin.question-banks.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update(Request $request, QuestionBank $questionBank)
    {
        $this->saveQuestion($request, $questionBank);

        return redirect()
            ->route('admin.question-banks.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Inti logic simpan soal: validasi berbeda per tipe soal,
     * lalu simpan question_banks + relasi anaknya dalam 1 transaction.
     */
    private function saveQuestion(Request $request, ?QuestionBank $questionBank = null): QuestionBank
    {
        $typeCode = QuestionType::find($request->question_type_id)?->code;

        // ---- Validasi dasar (berlaku semua tipe) ----
        $validated = $request->validate([
            'subject_id'          => ['required', 'exists:subjects,id'],
            'question_type_id'    => ['required', 'exists:question_types,id'],
            'difficulty_level_id' => ['required', 'exists:difficulty_levels,id'],
            'cognitive_level_id'  => ['required', 'exists:cognitive_levels,id'],
            'question_text'       => ['required', 'string'],
            'points'               => ['required', 'integer', 'min:1'],
            'explanation'          => ['nullable', 'string'],
            'scoring_type'         => ['required', 'in:auto,manual'],
            'stimulus_id'          => ['nullable', 'exists:question_stimuli,id'],
            'tags_input'           => ['nullable', 'string'],
            'question_media.*'     => ['nullable', 'file', 'max:10240'],
        ]);

        // ---- Validasi tambahan sesuai tipe soal ----
        match ($typeCode) {
            'single_choice', 'true_false' => $request->validate([
                'options'                    => ['required', 'array', 'min:2'],
                'options.*.text'             => ['required', 'string'],
                'options.*.is_correct'       => ['required'],
            ]) && $this->assertExactlyOneCorrect($request->options),

            'multi_choice' => $request->validate([
                'options'               => ['required', 'array', 'min:2'],
                'options.*.text'        => ['required', 'string'],
                'options.*.is_correct'  => ['required'],
            ]) && $this->assertAtLeastOneCorrect($request->options),

            'ordering' => $request->validate([
                'options'         => ['required', 'array', 'min:2'],
                'options.*.text'  => ['required', 'string'],
            ]),

            'matching' => $request->validate([
                'pairs'          => ['required', 'array', 'min:2'],
                'pairs.*.left'   => ['required', 'string'],
                'pairs.*.right'  => ['required', 'string'],
            ]),

            'short_answer' => $request->validate([
                'aliases'    => ['required', 'array', 'min:1'],
                'aliases.*'  => ['required', 'string'],
            ]),

            'essay' => [], // tidak ada kunci jawaban otomatis

            default => throw new \InvalidArgumentException('Tipe soal tidak dikenali.'),
        };

        return DB::transaction(function () use ($request, $validated, $typeCode, $questionBank) {

            $tags = $request->filled('tags_input')
                ? array_map('trim', explode(',', $request->tags_input))
                : null;

            $data = [
                'school_id'            => auth()->user()->school_id,
                'subject_id'           => $validated['subject_id'],
                'question_type_id'     => $validated['question_type_id'],
                'difficulty_level_id'  => $validated['difficulty_level_id'],
                'cognitive_level_id'   => $validated['cognitive_level_id'],
                'stimulus_id'          => $request->boolean('use_stimulus') ? $validated['stimulus_id'] : null,
                'question_text'        => $validated['question_text'],
                'explanation'          => $typeCode === 'essay' ? $request->essay_rubric : $validated['explanation'],
                'tags'                 => $tags,
                'points'               => $validated['points'],
                'partial_scoring'      => $request->boolean('partial_scoring'),
                'scoring_type'         => $validated['scoring_type'],
                'is_public'            => $request->boolean('is_public'),
                'is_active'            => $request->boolean('is_active'),
                'correct_answer_text'  => $typeCode === 'short_answer' ? array_values($request->aliases) : null,
                'updated_by'           => auth()->id(),
            ];

            if ($questionBank) {
                // simpan versi lama sebelum diupdate (audit trail)
                $questionBank->versions()->create([
                    'version_number' => $questionBank->versions()->count() + 1,
                    'snapshot_data'  => $questionBank->only([
                        'question_text',
                        'correct_answer_text',
                        'explanation',
                    ]) + ['options' => $questionBank->options->toArray()],
                    'changed_by' => auth()->id(),
                ]);

                $questionBank->update($data);
                $questionBank->options()->delete();
                $questionBank->matchingPairs()->delete();
            } else {
                $data['created_by'] = auth()->id();
                $questionBank = QuestionBank::create($data);
            }

            // ---- simpan opsi jawaban ----
            if (in_array($typeCode, ['single_choice', 'true_false', 'multi_choice', 'ordering'])) {
                foreach ($request->options as $i => $opt) {
                    $questionBank->options()->create([
                        'option_label' => $opt['label'] ?? null,
                        'option_text'  => $opt['text'],
                        'is_correct'   => $opt['is_correct'] ?? false,
                        'order_index'  => $opt['order_index'] ?? $i,
                    ]);
                }
            }

            // ---- simpan pasangan menjodohkan ----
            if ($typeCode === 'matching') {
                foreach ($request->pairs as $i => $pair) {
                    $questionBank->matchingPairs()->create([
                        'left_item'   => $pair['left'],
                        'right_item'  => $pair['right'],
                        'pair_key'    => (string) ($i + 1),
                        'order_index' => $i,
                    ]);
                }
            }

            // ---- simpan media lampiran ----
            if ($request->hasFile('question_media')) {
                foreach ($request->file('question_media') as $file) {
                    $path = $file->store('question-media', 'public');
                    $questionBank->media()->create([
                        'media_type' => str($file->getMimeType())->before('/')->toString(),
                        'file_path'  => $path,
                    ]);
                }
            }

            return $questionBank;
        });
    }

    private function assertExactlyOneCorrect(array $options): bool
    {
        $correctCount = collect($options)->filter(fn($o) => filter_var($o['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN))->count();

        if ($correctCount !== 1) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'options' => 'Harus ada tepat 1 opsi jawaban yang benar.',
            ]);
        }

        return true;
    }

    private function assertAtLeastOneCorrect(array $options): bool
    {
        $correctCount = collect($options)->filter(fn($o) => filter_var($o['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN))->count();

        if ($correctCount < 1) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'options' => 'Minimal 1 opsi jawaban harus ditandai benar.',
            ]);
        }

        return true;
    }

    private function formData(): array
    {
        return [
            'subjects'         => Subject::select('id', 'name')->get(),
            'questionTypes'    => QuestionType::select('id', 'name', 'code')->get(),
            'difficultyLevels' => DifficultyLevel::select('id', 'name')->get(),
            'cognitiveLevels'  => CognitiveLevel::select('id', 'name', 'code')->get(),
            'stimuli'          => QuestionStimulus::select('id', 'title')->get(),
        ];
    }
}
