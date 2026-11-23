<?php

namespace App\Modules\Student\Controllers;

use App\DataTables\StudentDataTable;
use App\Http\Controllers\MainController;
use Models\Student;
use Models\School;
use Models\Applicant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Student());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        StudentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Student::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $student = new Student();
        $user = Auth::user();
        $schools = School::orderBy('name')->get();
        $applicants = Applicant::orderBy('name')->get();
        return view(
            'Student::create',
            compact('student', 'schools', 'applicants')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $user = Auth::user();
        $orgId = $user->hasRole('super_admin')
            ? $request->input('organization_id', $user->organization_id)
            : $user->organization_id;
        try {
            $validated['organization_id'] = $orgId;
            $validated['created_by'] = $user->id;

            $model = DB::transaction(function () use ($validated) {
                return Student::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Student.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $student = $this->model->findOrFail($id);
        return view(
            'Student::edit',
            compact('student')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validated($request);
        $student = $this->model->findOrFail($id);
        $user = Auth::user();
        $orgId = $user->hasRole('super-admin')
            ? $request->input('organization_id', $student->organization_id)
            : $student->organization_id;
        $validated['updated_by'] = $user->id;
        try {
            DB::transaction(function () use ($student, $validated) {
                $student->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil diperbarui.',
                $student
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Student.',
                [
                    'exception' => $e,
                    'Student_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $student = $this->model->findOrFail($id);

            DB::transaction(function () use ($student) {
                $student->delete();
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil dihapus.'
            );
        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $student->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Student.',
                [
                    'exception' => $e,
                    'Student_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal dihapus.'
            );
        }
    }

    private function validated($request)
    {
        return [
            'school_id' => 'nullable|exists:schools,id',
            'user_id' => 'nullable|exists:users,id',
            'applicant_id' => 'nullable|exists:applicants,id',
            'nis' => 'required|string|max:50|unique:students,nis,NULL,id,organization_id,' . $orgId,
            'nisn' => 'nullable|string|max:50|unique:students,nisn,NULL,id,organization_id,' . $orgId,
            'registration_number' => 'nullable|string|max:50',
            'name' => 'required|string|max:150',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:MALE,FEMALE',
            'address' => 'nullable|string',
            'religion' => 'nullable|string|max:20',
            'parents_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'medical_notes' => 'nullable|string',
            'join_date' => 'nullable|date',
            'status' => 'required|in:ACTIVE,GRADUATED,TRANSFERRED,DROPPED,SUSPENDED',
            'admission_status' => 'nullable|in:NEW,TRANSFER,RETURNING',
            'enrollment_year' => 'nullable|integer|digits:4',

        ];
    }
}
