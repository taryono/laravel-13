<?php

namespace App\Modules\Region\Controllers;

use App\DataTables\RegionDataTable;
use App\Http\Controllers\MainController;
use Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RegionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Region());
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
        RegionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /region?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Region::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $region = new Region();
        $result = $this->getForm($region);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Region::create',
            compact('region', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer'],
            'postal_code' => ['required', 'string', 'max:255'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Region::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Region berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Region.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Region gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $region = $this->model->findOrFail($id);
        $result = $this->getForm($region);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Region::edit',
            compact('region', 'forms', 'groupedForms')
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
            'country_id' => ['required', 'integer'],
            'postal_code' => ['required', 'string', 'max:255'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        try {
            $region = $this->model->findOrFail($id);
            DB::transaction(function () use ($region, $validated) {
                $region->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Region berhasil diperbarui.',
                $region
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Region.',
                [
                    'exception' => $e,
                    'Region_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Region gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $region = $this->model->findOrFail($id);

            DB::transaction(function () use ($region) {
                $region->delete();
            });

            return $this->successResponse(
                $request,
                'Data Region berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $region->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Region.',
                [
                    'exception' => $e,
                    'Region_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Region gagal dihapus.'
            );
        }
    }
}
