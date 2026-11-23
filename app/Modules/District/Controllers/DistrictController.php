<?php

namespace App\Modules\District\Controllers;

use App\DataTables\DistrictDataTable;
use App\Http\Controllers\MainController;
use Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DistrictController extends MainController
{
    public function __construct()
    {
        parent::__construct(new District());
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
        DistrictDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /district?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'District::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $district = new District();
        $result = $this->getForm($district);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'District::create',
            compact('district', 'forms', 'groupedForms')
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
            'city_id' => ['required', 'integer'],
            'last_id' => ['required', 'integer'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return District::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data District berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan District.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data District gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $district = $this->model->findOrFail($id);
        $result = $this->getForm($district);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'District::edit',
            compact('district', 'forms', 'groupedForms')
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
            'city_id' => ['required', 'integer'],
            'last_id' => ['required', 'integer'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $district = $this->model->findOrFail($id);
            DB::transaction(function () use ($district, $validated) {
                $district->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data District berhasil diperbarui.',
                $district
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui District.',
                [
                    'exception' => $e,
                    'District_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data District gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $district = $this->model->findOrFail($id);

            DB::transaction(function () use ($district) {
                $district->delete();
            });

            return $this->successResponse(
                $request,
                'Data District berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $district->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus District.',
                [
                    'exception' => $e,
                    'District_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data District gagal dihapus.'
            );
        }
    }
}
