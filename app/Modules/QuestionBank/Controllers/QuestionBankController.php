<?php

namespace App\Modules\QuestionBank\Controllers;

use App\DataTables\QuestionBankDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionBank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionBankController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionBank());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        QuestionBankDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-bank?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionBank::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionBank = new QuestionBank();
        $forms = $this->formData();
        return view(
            'QuestionBank::create',
            compact('questionBank', 'forms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {


        try {
            $questionBank = $this->saveQuestion($request);

            return $this->successResponse(
                $request,
                'Data QuestionBank berhasil ditambahkan.',
                $questionBank
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionBank.',
                [
                    'exception' => $e,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBank gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionBank = $this->model->findOrFail($id);
        $questionBank->load(['options', 'matchingPairs']);

        return view('QuestionBank::edit', array_merge(
            $this->formData(),
            ['question' => $questionBank]
        ));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {

        try {
            $questionBank = $this->model->findOrFail($id);
            $this->saveQuestion($request, $questionBank);

            return $this->successResponse(
                $request,
                'Data QuestionBank berhasil diperbarui.',
                $questionBank
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionBank.',
                [
                    'exception' => $e,
                    'QuestionBank_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBank gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionBank = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionBank) {
                $questionBank->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionBank berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $questionBank->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionBank.',
                [
                    'exception' => $e,
                    'QuestionBank_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBank gagal dihapus.'
            );
        }
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
}
