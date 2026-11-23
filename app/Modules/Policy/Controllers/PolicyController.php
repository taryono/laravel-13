<?php

namespace App\Modules\Policy\Controllers;

use App\DataTables\PolicyDataTable;
use App\Http\Controllers\MainController;
use Models\Policy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PolicyController extends MainController
{

    public function __construct()
    {
        parent::__construct(new Policy());
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
        PolicyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /policy?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Policy::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $policy = new Policy();
        $forms = $this->getForm($policy);
        return view(
            'Policy::edit',
            compact('policy', 'forms')
        );
        return view('Policy::create');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Policy::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Policy berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Policy.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Policy gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $policy = $this->model->findOrFail($id);
        $forms = $this->getForm($policy);
        return view(
            'Policy::edit',
            compact('policy', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $policy = $this->model->findOrFail($id);
            DB::transaction(function () use ($policy, $validated) {
                $policy->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Policy berhasil diperbarui.',
                $policy
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Policy.',
                [
                    'exception' => $e,
                    'Policy_id' => $policy->getKey(),
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Policy gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $policy = $this->model->findOrFail($id);
            DB::transaction(function () use ($policy) {
                $policy->delete();
            });

            return $this->successResponse(
                $request,
                'Data Policy berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Policy.',
                [
                    'exception' => $e,
                    'Policy_id' => $policy->getKey(),
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Policy gagal dihapus.'
            );
        }
    }
}