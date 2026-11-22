<?php

namespace App\Modules\City\Controllers;

use App\DataTables\CityDataTable;
use App\Http\Controllers\MainController;
use Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new City());
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
        CityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /city?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'City::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $city = new City();
        $result = $this->getForm($city);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'City::create',
            compact('city', 'forms', 'groupedForms')
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
            'region_id' => ['required', 'integer'],
            'postal_code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return City::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data City berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan City.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data City gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $city = $this->model->findOrFail($id);
        $result = $this->getForm($city);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'City::edit',
            compact('city', 'forms', 'groupedForms')
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
            'region_id' => ['required', 'integer'],
            'postal_code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'is_free_text' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'is_user_input' => ['required', 'string', 'max:255'],
        ]);

        try {
            $city = $this->model->findOrFail($id);
            DB::transaction(function () use ($city, $validated) {
                $city->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data City berhasil diperbarui.',
                $city
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui City.',
                [
                    'exception' => $e,
                    'City_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data City gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $city = $this->model->findOrFail($id);

            DB::transaction(function () use ($city) {
                $city->delete();
            });

            return $this->successResponse(
                $request,
                'Data City berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $city->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus City.',
                [
                    'exception' => $e,
                    'City_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data City gagal dihapus.'
            );
        }
    }
}
