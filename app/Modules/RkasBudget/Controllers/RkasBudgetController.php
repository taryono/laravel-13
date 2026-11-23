<?php

namespace App\Modules\RkasBudget\Controllers;

use App\DataTables\RkasBudgetDataTable;
use App\Http\Controllers\MainController;
use Models\RkasBudget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasBudgetController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasBudget());
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
        RkasBudgetDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-budget?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasBudget::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasBudget = new RkasBudget();
        $result = $this->getForm($rkasBudget);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasBudget::create',
            compact('rkasBudget', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_item_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasBudget::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasBudget berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasBudget.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasBudget gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasBudget = $this->model->findOrFail($id);
        $result = $this->getForm($rkasBudget);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasBudget::edit',
            compact('rkasBudget', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_item_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasBudget = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasBudget, $validated) {
                $rkasBudget->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasBudget berhasil diperbarui.',
                $rkasBudget
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasBudget.',
                [
                    'exception' => $e,
                    'RkasBudget_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasBudget gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasBudget = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasBudget) {
                $rkasBudget->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasBudget berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasBudget->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasBudget.',
                [
                    'exception' => $e,
                    'RkasBudget_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasBudget gagal dihapus.'
            );
        }
    }
}
