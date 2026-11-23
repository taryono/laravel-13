<?php

namespace App\Modules\Navigation\Controllers;

use App\DataTables\NavigationDataTable;
use App\Http\Controllers\MainController;
use Models\Navigation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class NavigationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Navigation());
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
        NavigationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /navigation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Navigation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $navigation = new Navigation();
        $result = $this->getForm($navigation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Navigation::create',
            compact('navigation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Navigation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Navigation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Navigation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Navigation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $navigation = $this->model->findOrFail($id);
        $result = $this->getForm($navigation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Navigation::edit',
            compact('navigation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $navigation = $this->model->findOrFail($id);
            DB::transaction(function () use ($navigation, $validated) {
                $navigation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Navigation berhasil diperbarui.',
                $navigation
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Navigation.',
                [
                    'exception' => $e,
                    'Navigation_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Navigation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $navigation = $this->model->findOrFail($id);

            DB::transaction(function () use ($navigation) {
                $navigation->delete();
            });

            return $this->successResponse(
                $request,
                'Data Navigation berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $navigation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Navigation.',
                [
                    'exception' => $e,
                    'Navigation_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Navigation gagal dihapus.'
            );
        }
    }
}
