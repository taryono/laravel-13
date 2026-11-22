<?php

namespace App\Modules\Country\Controllers;

use App\DataTables\CountryDataTable;
use App\Http\Controllers\MainController;
use Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CountryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Country());
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
        CountryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /Country?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Country::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $country = new Country();
        return view(
            'Country::create',
            compact('country')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iso' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'printable_name' => ['required', 'string', 'max:255'],
            'iso3' => ['required', 'string', 'max:255'],
            'numcode' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Country::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Country berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Country.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Country gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $country = $this->model->findOrFail($id);
        return view(
            'Country::edit',
            compact('country')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'iso' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'printable_name' => ['required', 'string', 'max:255'],
            'iso3' => ['required', 'string', 'max:255'],
            'numcode' => ['required', 'string', 'max:255'],
        ]);

        try {
            $country = $this->model->findOrFail($id);
            DB::transaction(function () use ($country, $validated) {
                $country->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Country berhasil diperbarui.',
                $country
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Country.',
                [
                    'exception' => $e,
                    'Country_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Country gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $country = $this->model->findOrFail($id);

            DB::transaction(function () use ($country) {
                $country->delete();
            });

            return $this->successResponse(
                $request,
                'Data Country berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $country->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Country.',
                [
                    'exception' => $e,
                    'Country_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Country gagal dihapus.'
            );
        }
    }
}
