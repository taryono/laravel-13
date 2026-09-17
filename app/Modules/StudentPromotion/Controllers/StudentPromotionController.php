<?php

namespace App\Modules\StudentPromotion\Controllers;

use App\DataTables\StudentPromotionDataTable;
use App\Http\Controllers\MainController;
use Models\StudentPromotion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use App\Services\PromotionService;
use Models\AcademicYear;
use Illuminate\Support\Facades\Auth;


class StudentPromotionController extends MainController
{
    public function __construct(protected PromotionService $promotionService)
    {
        parent::__construct(new StudentPromotion());
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
        StudentPromotionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-promotion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentPromotion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentPromotion = new StudentPromotion();
        $academic_years = AcademicYear::orderByDesc('start_date')->get();


        return view(
            'StudentPromotion::create',
            compact('studentPromotion', 'academic_years')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'from_classroom_id' => ['required', 'integer'],
            'to_classroom_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'decision' => ['required', 'string', 'max:255'],
            'decision_date' => ['required', 'string', 'max:255'],
            'meeting_notes' => ['required', 'string', 'max:255'],
            'final_score_average' => ['required', 'string', 'max:255'],
            'attendance_percentage' => ['required', 'string', 'max:255'],
            'decided_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $batch = $this->promotionService->createBatch($validated);


            return $this->successResponse(
                $request,
                'Data StudentPromotion berhasil ditambahkan.',
                $batch
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentPromotion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPromotion gagal ditambahkan.'
            );
        }
    }

    /**
     * Detail batch + daftar siswa.
     */
    public function show($id)
    {
        $promotion = $this->model->findOrFail($id);
        $promotion->load(['student', 'batch', 'fromClassroom', 'toClassroom']);

        $classrooms = \Models\Classroom::where('academic_year_id', $promotion->to_academic_year_id)
            ->with('classLevel')
            ->get();

        return view('StudentPromotion::show', compact('promotion', 'classrooms'));
    }

    /**
     * Generate draft promosi.
     */
    public function generate($id)
    {
        try {
            $batch = $this->model->findOrFail($id);
            $count = $this->promotionService->generateDrafts($batch);

            return back()->with('success', "Berhasil generate {$count} draft promosi.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal generate: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $promotion = $this->model->findOrFail($id);
        $promotion->load(['student', 'batch', 'fromClassroom', 'toClassroom']);

        $classrooms = \Models\Classroom::where('academic_year_id', $promotion->to_academic_year_id)
            ->with('classLevel')
            ->get();

        return view(
            'StudentPromotion::edit',
            compact('promotion', 'classrooms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'promotion_status' => 'required|in:promoted,retained,graduated,transferred,dropped_out',
            'to_classroom_id' => 'nullable|exists:classrooms,id',
            'decision_reason' => 'nullable|string',
        ]);




        try {
            $promotion = $this->model->findOrFail($id);
            $this->promotionService->overrideDecision(
                $promotion,
                $validated['promotion_status'],
                $validated['to_classroom_id'] ?? null,
                $validated['decision_reason'] ?? null,
            );

            return $this->successResponse(
                $request,
                'Data StudentPromotion berhasil diperbarui.',
                $promotion
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentPromotion.',
                [
                    'exception' => $e,
                    'StudentPromotion_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPromotion gagal diperbarui.'
            );
        }
    }

    /**
     * Finalize batch.
     */
    public function finalize($id)
    {
        $promotion = $this->model->findOrFail($id);
        try {
            $stats = $this->promotionService->finalizeBatch($promotion);

            $message = "Berhasil finalize {$stats['processed']} siswa.";
            if ($stats['errors'] > 0) {
                $message .= " ({$stats['errors']} error)";
            }

            return back()->with('success', $message);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal finalize: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentPromotion = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentPromotion) {
                $studentPromotion->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentPromotion berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $studentPromotion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentPromotion.',
                [
                    'exception' => $e,
                    'StudentPromotion_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentPromotion gagal dihapus.'
            );
        }
    }
}
