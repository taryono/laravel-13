<?php

namespace App\Modules\TeacherComment\Controllers;

use App\DataTables\TeacherCommentDataTable;
use App\Http\Controllers\MainController;
use Models\TeacherComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TeacherCommentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TeacherComment());
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
        TeacherCommentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /teacher-comment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TeacherComment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teacherComment = new TeacherComment();
        $result = $this->getForm($teacherComment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TeacherComment::create',
            compact('teacherComment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'comment' => ['required', 'string', 'max:255'],
            'comment_type' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TeacherComment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TeacherComment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TeacherComment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeacherComment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $teacherComment = $this->model->findOrFail($id);
        $result = $this->getForm($teacherComment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TeacherComment::edit',
            compact('teacherComment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'comment' => ['required', 'string', 'max:255'],
            'comment_type' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $teacherComment = $this->model->findOrFail($id);
            DB::transaction(function () use ($teacherComment, $validated) {
                $teacherComment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TeacherComment berhasil diperbarui.',
                $teacherComment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TeacherComment.',
                [
                    'exception' => $e,
                    'TeacherComment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeacherComment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $teacherComment = $this->model->findOrFail($id);

            DB::transaction(function () use ($teacherComment) {
                $teacherComment->delete();
            });

            return $this->successResponse(
                $request,
                'Data TeacherComment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $teacherComment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TeacherComment.',
                [
                    'exception' => $e,
                    'TeacherComment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeacherComment gagal dihapus.'
            );
        }
    }
}
