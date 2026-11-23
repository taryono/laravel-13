<?php

namespace App\Modules\ExtracurricularAttendance\Controllers;

use App\DataTables\ExtracurricularAttendanceDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Models\Extracurricular;
use Models\Student;
use Throwable;

class ExtracurricularAttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularAttendance());
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
        ExtracurricularAttendanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-attendance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularAttendance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attendance = new ExtracurricularAttendance();
        return view(
            'ExtracurricularAttendance::create',
            array_merge(
                $this->formData(),
                compact('attendance')
            )
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'extracurricular_day_id' => 'required|exists:extracurricular_days,id',
            'student_id' => 'required|exists:students,id',
            'attendance_date' => 'required|date',
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alfa'])],
            'score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Cek duplikasi: siswa + jadwal + tanggal sudah ada?
        $exists = ExtracurricularAttendance::where([
            ['extracurricular_day_id', $validated['extracurricular_day_id']],
            ['student_id', $validated['student_id']],
            ['attendance_date', $validated['attendance_date']],
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Absensi untuk siswa ini pada tanggal tersebut sudah ada'
            ], 422);
        }

        $attendance = ExtracurricularAttendance::create(array_merge($validated, [
            'created_by' => request()->user()?->id,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan',
            'reload' => true,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attendance = $this->model->findOrFail($id);

        return view(
            'ExtracurricularAttendance::edit',
            array_merge(
                $this->formData(),
                compact('attendance')
            )
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, ExtracurricularAttendance $attendance)
    {
        $validated = $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'extracurricular_day_id' => 'required|exists:extracurricular_days,id',
            'student_id' => 'required|exists:students,id',
            'attendance_date' => 'required|date',
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alfa'])],
            'score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $attendance->update(array_merge($validated, [
            'updated_by' => request()->user()?->id,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil diperbarui',
            'reload' => true,
        ]);
    }


    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularAttendance = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularAttendance) {
                $extracurricularAttendance->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAttendance berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $extracurricularAttendance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularAttendance.',
                [
                    'exception' => $e,
                    'ExtracurricularAttendance_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAttendance gagal dihapus.'
            );
        }
    }

    protected function formData(): array
    {
        $extracurriculars = Extracurricular::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        // Susun daftar jadwal per kegiatan untuk JS
        $scheduleMapping = [];
        Extracurricular::with('days')->get()->each(function ($ec) use (&$scheduleMapping) {
            $scheduleMapping[$ec->id] = $ec->days->map(function ($day) {
                return [
                    'id' => $day->id,
                    'label' => "{$day->day_of_week} " .
                        ($day->start_time ? "({$day->start_time}_{$day->end_time})" : '') .
                        ($day->location ? " — {$day->location}" : ''),
                ];
            });
        });

        $students = Student::orderBy('name')->pluck('name', 'id');

        return [
            'extracurriculars' => $extracurriculars,
            'scheduleMapping' => $scheduleMapping,
            'students' => $students,
        ];
    }
}
