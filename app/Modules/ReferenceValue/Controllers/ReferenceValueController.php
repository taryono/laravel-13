<?php

namespace App\Modules\ReferenceValue\Controllers;

use App\DataTables\ReferenceValueDataTable;
use App\Http\Controllers\MainController;
use Models\ReferenceValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReferenceValueController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReferenceValue());
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
        ReferenceValueDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /reference-value?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReferenceValue::index'
        );
    }

    public function data($id)
   {    $referenceValue = ReferenceValue::findOrFail($id);
        $values = $referenceType->values()->orderBy('sort_order')->get();

        return response()->json(['data' => $values]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $referenceValue = new ReferenceValue(); 
        return view(
            'ReferenceValue::create',
            compact('referenceValue')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'reference_type_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'badge_color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'text_color' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReferenceValue::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReferenceValue berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReferenceValue.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceValue gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $referenceValue = $this->model->findOrFail($id);
        $result = $this->getForm($referenceValue);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReferenceValue::edit',
            compact('referenceValue', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'reference_type_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'badge_color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'text_color' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $referenceValue = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $referenceValue,
                $validated
            ) {
                $referenceValue->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReferenceValue berhasil diperbarui.',
                $referenceValue
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ReferenceValue.',
                [
                    'exception' => $e,
                    'ReferenceValue_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceValue gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $referenceValue = $this->model->findOrFail($id);

            DB::transaction(function () use ($referenceValue) {
                $referenceValue->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReferenceValue berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $referenceValue->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReferenceValue.',
                [
                    'exception' => $e,
                    'ReferenceValue_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceValue gagal dihapus.'
            );
        }
    } 
}
