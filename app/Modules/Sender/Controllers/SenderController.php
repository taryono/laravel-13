<?php

namespace App\Modules\Sender\Controllers;

use App\DataTables\SenderDataTable;
use App\Http\Controllers\MainController;
use Models\Sender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SenderController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Sender());
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
        SenderDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sender?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Sender::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sender = new Sender();
        $result = $this->getForm($sender);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Sender::create',
            compact('sender', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Sender::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Sender berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Sender.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Sender gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $sender = $this->model->findOrFail($id);
        $result = $this->getForm($sender);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Sender::edit',
            compact('sender', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $sender = $this->model->findOrFail($id);
            DB::transaction(function () use ($sender, $validated) {
                $sender->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Sender berhasil diperbarui.',
                $sender
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Sender.',
                [
                    'exception' => $e,
                    'Sender_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Sender gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $sender = $this->model->findOrFail($id);

            DB::transaction(function () use ($sender) {
                $sender->delete();
            });

            return $this->successResponse(
                $request,
                'Data Sender berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $sender->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Sender.',
                [
                    'exception' => $e,
                    'Sender_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Sender gagal dihapus.'
            );
        }
    }
}
