<?php

namespace App\Modules\BloodType\Controllers;

use App\DataTables\BloodTypeDataTable;
use App\Http\Controllers\MainController;
use Models\BloodType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BloodTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BloodType());
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
        BloodTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /blood-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BloodType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bloodType = new BloodType();
        $result = $this->getForm($bloodType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BloodType::create',
            compact('bloodType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'rhesus' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BloodType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BloodType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BloodType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BloodType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bloodType = $this->model->findOrFail($id);
        $result = $this->getForm($bloodType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BloodType::edit',
            compact('bloodType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'rhesus' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bloodType = $this->model->findOrFail($id);
            DB::transaction(function () use ($bloodType, $validated) {
                $bloodType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BloodType berhasil diperbarui.',
                $bloodType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BloodType.',
                [
                    'exception' => $e,
                    'BloodType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BloodType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bloodType = $this->model->findOrFail($id);

            DB::transaction(function () use ($bloodType) {
                $bloodType->delete();
            });

            return $this->successResponse(
                $request,
                'Data BloodType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bloodType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BloodType.',
                [
                    'exception' => $e,
                    'BloodType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BloodType gagal dihapus.'
            );
        }
    }
}
