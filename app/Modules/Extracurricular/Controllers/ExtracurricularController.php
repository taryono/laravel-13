<?php

namespace App\Modules\Extracurricular\Controllers;

use App\DataTables\ExtracurricularDataTable;
use App\Http\Controllers\MainController;
use Models\Extracurricular;
use Models\School;
use Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Models\ExtracurricularDay;
use Models\Student;
use Throwable;

class ExtracurricularController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Extracurricular());
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
        ExtracurricularDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Extracurricular::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricular = new Extracurricular();
        $schools = School::pluck('name', 'id');
        $teachers = Teacher::pluck('name', 'id');

        return view(
            'Extracurricular::create',
            compact('extracurricular', 'schools', 'teachers')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id'      => 'required|exists:schools,id',
            'name'           => 'required|string|max:100',
            'code'           => [
                'required',
                'string',
                'max:20',
                Rule::unique('extracurriculars')->where(
                    fn($q) =>
                    $q->where('school_id', $request->school_id)
                )
            ],
            'teacher_id'     => 'nullable|exists:teachers,id',
            'description'    => 'nullable|string',
            'max_students'   => 'required|integer|min:1',
            'is_active'      => 'boolean',
            'days'           => 'required|array|min:1',
            'days.*.selected' => 'accepted',
            'days.*.day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'days.*.start_time'  => 'nullable|date_format:H:i',
            'days.*.end_time'    => 'nullable|date_format:H:i|after:days.*.start_time',
            'days.*.location'    => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $ec = Extracurricular::create([
                'school_id'    => $validated['school_id'],
                'name'         => $validated['name'],
                'code'         => $validated['code'],
                'teacher_id'   => $validated['teacher_id'],
                'description'  => $validated['description'],
                'max_students' => $validated['max_students'],
                'is_active'    => $validated['is_active'] ?? 1,
                'created_by'   => auth()->id(),
            ]);

            foreach ($validated['days'] as $day) {
                if (!empty($day['selected'])) {
                    $ec->days()->create([
                        'day_of_week' => $day['day_of_week'],
                        'start_time'  => $day['start_time'] ?? null,
                        'end_time'    => $day['end_time'] ?? null,
                        'location'    => $day['location'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil ditambahkan.',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal ditambahkan.'
            );
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricular = $this->model->findOrFail($id);
        $schools = School::pluck('name', 'id');
        $teachers = Teacher::pluck('name', 'id');

        return view(
            'Extracurricular::edit',
            compact('extracurricular', 'schools', 'teachers')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $extracurricular = $this->model->findOrFail($id);
        $validated = $request->validate([
            'school_id'      => 'required|exists:schools,id',
            'name'           => 'required|string|max:100',
            'code'           => [
                'required',
                'string',
                'max:20',
                Rule::unique('extracurriculars')->where(
                    fn($q) =>
                    $q->where('school_id', $request->school_id)
                )->ignore($extracurricular->id)
            ],
            'teacher_id'     => 'nullable|exists:teachers,id',
            'description'    => 'nullable|string',
            'max_students'   => 'required|integer|min:1',
            'is_active'      => 'boolean',
            'days'           => 'required|array|min:1',
            'days.*.start_time' => 'nullable|date_format:H:i',
            'days.*.end_time'   => 'nullable|date_format:H:i|after:days.*.start_time',
            'days.*.location'   => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $extracurricular->update([
                'school_id'    => $validated['school_id'],
                'name'         => $validated['name'],
                'code'         => $validated['code'],
                'teacher_id'   => $validated['teacher_id'],
                'description'  => $validated['description'],
                'max_students' => $validated['max_students'],
                'is_active'    => $validated['is_active'] ?? 1,
                'updated_by'   => auth()->id(),
            ]);

            // Ganti semua jadwal — hapus lama, simpan baru
            $extracurricular->days()->delete();
            foreach ($validated['days'] as $day) {
                if (!empty($day['selected'])) {
                    $extracurricular->days()->create([
                        'day_of_week' => $day['day_of_week'],
                        'start_time'  => $day['start_time'],
                        'end_time'    => $day['end_time'],
                        'location'    => $day['location'],
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil diupdate.',
            );
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal ditambahkan.'
            );
        }
    }


    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricular = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricular) {
                $extracurricular->delete();
            });

            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $extracurricular->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Extracurricular.',
                [
                    'exception' => $e,
                    'Extracurricular_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal dihapus.'
            );
        }
    }
}
