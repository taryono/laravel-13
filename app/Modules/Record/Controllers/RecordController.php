<?php

namespace App\Modules\Record\Controllers;

use App\DataTables\RecordDataTable;
use App\Http\Controllers\MainController;
use Models\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RecordController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Record());
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
        RecordDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /record?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Record::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $record = new Record();
        $result = $this->getForm($record);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Record::create',
            compact('record', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'record_type' => ['required', 'string', 'max:255'],
            'record_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'meta' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Record::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Record berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Record.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Record gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = $this->model->findOrFail($id);
        $result = $this->getForm($record);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Record::edit',
            compact('record', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'record_type' => ['required', 'string', 'max:255'],
            'record_key' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'meta' => ['required', 'string', 'max:255'],
        ]);

        try {
            $record = $this->model->findOrFail($id);
            DB::transaction(function () use ($record, $validated) {
                $record->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Record berhasil diperbarui.',
                $record
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Record.',
                [
                    'exception' => $e,
                    'Record_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Record gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $record = $this->model->findOrFail($id);

            DB::transaction(function () use ($record) {
                $record->delete();
            });

            return $this->successResponse(
                $request,
                'Data Record berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $record->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Record.',
                [
                    'exception' => $e,
                    'Record_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Record gagal dihapus.'
            );
        }
    }
}
