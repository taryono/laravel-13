<?php

namespace App\Modules\FeeMaster\Controllers;

use App\DataTables\FeeMasterDataTable;
use App\Http\Controllers\MainController;
use Models\FeeMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FeeMasterController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FeeMaster());
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
        FeeMasterDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fee-master?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FeeMaster::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $feeMaster = new FeeMaster();
        $result = $this->getForm($feeMaster);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FeeMaster::create',
            compact('feeMaster', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'fee_category' => ['required', 'string', 'max:255'],
            'fee_name' => ['required', 'string', 'max:255'],
            'fee_code' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FeeMaster::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FeeMaster berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FeeMaster.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeMaster gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $feeMaster = $this->model->findOrFail($id);
        $result = $this->getForm($feeMaster);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FeeMaster::edit',
            compact('feeMaster', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'fee_category' => ['required', 'string', 'max:255'],
            'fee_name' => ['required', 'string', 'max:255'],
            'fee_code' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $feeMaster = $this->model->findOrFail($id);
            DB::transaction(function () use ($feeMaster, $validated) {
                $feeMaster->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FeeMaster berhasil diperbarui.',
                $feeMaster
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FeeMaster.',
                [
                    'exception' => $e,
                    'FeeMaster_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeMaster gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $feeMaster = $this->model->findOrFail($id);

            DB::transaction(function () use ($feeMaster) {
                $feeMaster->delete();
            });

            return $this->successResponse(
                $request,
                'Data FeeMaster berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $feeMaster->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FeeMaster.',
                [
                    'exception' => $e,
                    'FeeMaster_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FeeMaster gagal dihapus.'
            );
        }
    }
}
