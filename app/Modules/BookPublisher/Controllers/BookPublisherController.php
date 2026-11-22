<?php

namespace App\Modules\BookPublisher\Controllers;

use App\DataTables\BookPublisherDataTable;
use App\Http\Controllers\MainController;
use Models\BookPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BookPublisherController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BookPublisher());
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
        BookPublisherDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /book-publisher?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BookPublisher::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bookPublisher = new BookPublisher();
        $result = $this->getForm($bookPublisher);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookPublisher::create',
            compact('bookPublisher', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BookPublisher::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookPublisher berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BookPublisher.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookPublisher gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bookPublisher = $this->model->findOrFail($id);
        $result = $this->getForm($bookPublisher);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookPublisher::edit',
            compact('bookPublisher', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bookPublisher = $this->model->findOrFail($id);
            DB::transaction(function () use ($bookPublisher, $validated) {
                $bookPublisher->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookPublisher berhasil diperbarui.',
                $bookPublisher
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BookPublisher.',
                [
                    'exception' => $e,
                    'BookPublisher_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookPublisher gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bookPublisher = $this->model->findOrFail($id);

            DB::transaction(function () use ($bookPublisher) {
                $bookPublisher->delete();
            });

            return $this->successResponse(
                $request,
                'Data BookPublisher berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bookPublisher->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BookPublisher.',
                [
                    'exception' => $e,
                    'BookPublisher_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookPublisher gagal dihapus.'
            );
        }
    }
}
