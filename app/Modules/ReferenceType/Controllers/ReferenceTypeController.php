<?php

namespace App\Modules\ReferenceType\Controllers;

use App\DataTables\ReferenceTypeDataTable;
use App\Http\Controllers\MainController;
use Models\ReferenceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReferenceTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReferenceType());
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
        ReferenceTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /reference-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }
        $types = ReferenceType::with('values')->orderBy('name')->get();
        return $dataTable->render('ReferenceType::index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $referenceType = new ReferenceType();
        $types = ReferenceType::with('values')->orderBy('name')->get();

        return view(
            'ReferenceType::create',
            compact('referenceType', 'types')
        );
    }

    public function data()
    {
        $types = ReferenceType::with('values')->orderBy('name')->get();

        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReferenceType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReferenceType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReferenceType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $referenceType = $this->model->findOrFail($id);
        return view(
            'ReferenceType::edit',
            compact('referenceType')
        );
    }

    public function values($id)
    {
        $referenceType = ReferenceType::findOrFail($id);
        $values = $referenceType->values()->orderBy('sort_order')->get();

        return view(
            'ReferenceType::values',
            compact('values')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $referenceType = $this->model->findOrFail($id);
            DB::transaction(function () use ($referenceType, $validated) {
                $referenceType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReferenceType berhasil diperbarui.',
                $referenceType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReferenceType.',
                [
                    'exception' => $e,
                    'ReferenceType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $referenceType = $this->model->findOrFail($id);

            DB::transaction(function () use ($referenceType) {
                $referenceType->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReferenceType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $referenceType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReferenceType.',
                [
                    'exception' => $e,
                    'ReferenceType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReferenceType gagal dihapus.'
            );
        }
    }
}
