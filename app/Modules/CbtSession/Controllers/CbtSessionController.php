<?php

namespace App\Modules\CbtSession\Controllers;

use App\DataTables\CbtSessionDataTable;
use App\Http\Controllers\MainController;
use Models\CbtSession;
use Models\ExamPackage;
use Models\Student;
use Models\Classroom;
use Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtSessionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtSession());
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
        CbtSessionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-session?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('CbtSession::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtSession = new CbtSession();
        $examPackages = ExamPackage::select('id', 'name')->get();
        $students = Student::select('id', 'name', 'nis')->get();
        $schedules = Schedule::select('id', 'name')->get();
        $classrooms = Classroom::select('id', 'name')->get();

        return view(
            'CbtSession::create',
            compact('cbtSession', 'examPackages', 'students', 'schedules', 'classrooms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_package_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'class_id' => ['required', 'integer'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_seconds' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtSession::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtSession.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtSession = $this->model->findOrFail($id);
        $examPackages = ExamPackage::select('id', 'name')->get();
        $students = Student::select('id', 'name', 'nis')->get();
        $schedules = Schedule::select('id', 'name')->get();
        $classrooms = Classroom::select('id', 'name')->get();

        return view('CbtSession::edit', [
            'session' => $cbtSession,
            'examPackages' => $examPackages,
            'students' => $students,
            'schedules' => $schedules,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validateData($request, $id);

        try {
            $cbtSession = $this->model->findOrFail($id);
            if ($request->filled('final_score')) {
                $validated['final_score'] = $request->final_score;
            }

            $validated['updated_by'] = request()->user()?->id;

            DB::transaction(function () use ($cbtSession, $validated) {
                $cbtSession->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil diperbarui.',
                $cbtSession
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CbtSession.',
                [
                    'exception' => $e,
                    'CbtSession_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtSession = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtSession) {
                $cbtSession->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtSession berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $cbtSession->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtSession.',
                [
                    'exception' => $e,
                    'CbtSession_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSession gagal dihapus.'
            );
        }
    }

    private function validateData(Request $request, $id = null): array
    {
        $data = $request->validate([
            'exam_package_id' => ['required', 'exists:exam_packages,id'],
            'student_id' => ['required', 'exists:students,id'],
            'schedule_id' => ['nullable', 'exists:schedules,id'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:pending,ongoing,submitted,expired'],
            'proctor_notes' => ['nullable', 'string'],
        ]);

        // checkbox is_active tidak terkirim kalau tidak dicentang
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
