<?php

namespace App\Modules\CmsContactMessage\Controllers;

use App\DataTables\CmsContactMessageDataTable;
use App\Http\Controllers\MainController;
use Models\CmsContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsContactMessageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsContactMessage());
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
        CmsContactMessageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-contact-message?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsContactMessage::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsContactMessage = new CmsContactMessage();
        $result = $this->getForm($cmsContactMessage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsContactMessage::create',
            compact('cmsContactMessage', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsContactMessage::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsContactMessage berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsContactMessage.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsContactMessage gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsContactMessage = $this->model->findOrFail($id);
        $result = $this->getForm($cmsContactMessage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsContactMessage::edit',
            compact('cmsContactMessage', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsContactMessage = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsContactMessage,
                $validated
            ) {
                $cmsContactMessage->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsContactMessage berhasil diperbarui.',
                $cmsContactMessage
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsContactMessage.',
                [
                    'exception' => $e,
                    'CmsContactMessage_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsContactMessage gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsContactMessage = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsContactMessage) {
                $cmsContactMessage->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsContactMessage berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsContactMessage->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsContactMessage.',
                [
                    'exception' => $e,
                    'CmsContactMessage_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsContactMessage gagal dihapus.'
            );
        }
    } 
}
