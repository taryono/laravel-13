<?php

namespace App\Modules\Parent\Controllers;

use App\DataTables\ParentDataTable;
use App\Http\Controllers\MainController;
use Models\ParentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\Student;

class ParentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ParentModel());
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
        ParentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /parent?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Parent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parent = new ParentModel();
        $students = Student::orderBy('name')->get();

        return view(
            'Parent::create',
            compact('parent', 'students')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'work' => 'nullable|string|max:100',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'relationships' => 'nullable|array',
            'relationships.*' => 'in:FATHER,MOTHER,GUARDIAN',
            'is_primary' => 'nullable|array',
            'is_primary.*' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $organizationId = $user->hasRole('super-admin')
                ? $request->input('organization_id', $user->organization_id)
                : $user->organization_id;

            // Simpan data Orang Tua
            $parent = ParentModel::create([
                'organization_id' => $organizationId,
                'user_id' => null, // isi jika ada akun login
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'work' => $validated['work'],
                'created_by' => $user->id,
            ]);

            // Lampirkan ke Siswa
            if (!empty($validated['student_ids'])) {
                foreach ($validated['student_ids'] as $index => $studentId) {
                    // Cegah >1 utama per siswa
                    $isPrimary = !empty($validated['is_primary'][$index]);
                    if ($isPrimary) {
                        DB::table('student_parents')
                            ->where('student_id', $studentId)
                            ->update(['is_primary' => 0]);
                    }

                    $parent->students()->attach($studentId, [
                        'relationship' => $validated['relationships'][$index] ?? null,
                        'is_primary' => $isPrimary ? 1 : 0,
                        'created_by' => $user->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('parents.index')
                ->with('success', 'Data Orang Tua berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $parent = $this->model->findOrFail($id);
        $parent->load('students');
        $students = Student::orderBy('name')->get();

        // Daftar siswa yang sudah terhubung
        $attachedIds = $parent->students->pluck('id')->toArray();

        return view('Parent::edit', compact('parent', 'students', 'attachedIds'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'work' => 'nullable|string|max:100',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'relationships' => 'nullable|array',
            'relationships.*' => 'in:FATHER,MOTHER,GUARDIAN',
            'is_primary' => 'nullable|array',
            'is_primary.*' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $parent = $this->model->findOrFail($id);
            // Update data utama
            $parent->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'work' => $validated['work'],
                'updated_by' => $user->id,
            ]);

            // Sinkronisasi relasi siswa
            $syncData = [];
            if (!empty($validated['student_ids'])) {
                foreach ($validated['student_ids'] as $index => $studentId) {
                    $isPrimary = !empty($validated['is_primary'][$index]);
                    if ($isPrimary) {
                        DB::table('student_parents')
                            ->where('student_id', $studentId)
                            ->where('parent_id', '!=', $parent->id)
                            ->update(['is_primary' => 0]);
                    }

                    $syncData[$studentId] = [
                        'relationship' => $validated['relationships'][$index] ?? null,
                        'is_primary' => $isPrimary ? 1 : 0,
                        'updated_by' => $user->id,
                    ];
                }
            }

            $parent->students()->sync($syncData);

            DB::commit();
            return $this->successResponse(
                $request,
                'Data Parent berhasil dihapus.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                $request,
                'Data Parent gagal dihapus.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $parent = $this->model->findOrFail($id);

            DB::transaction(function () use ($parent) {
                $parent->delete();
            });

            return $this->successResponse(
                $request,
                'Data Parent berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $parent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Parent.',
                [
                    'exception' => $e,
                    'Parent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parent gagal dihapus.'
            );
        }
    }
}
