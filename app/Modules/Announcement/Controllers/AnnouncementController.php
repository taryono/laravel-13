<?php

namespace App\Modules\Announcement\Controllers;

use App\DataTables\AnnouncementDataTable;
use App\Http\Controllers\MainController;
use Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AnnouncementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Announcement());
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
        AnnouncementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /announcement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Announcement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $announcement = new Announcement();
        $result = $this->getForm($announcement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Announcement::create',
            compact('announcement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'audience' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'expired_at' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Announcement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Announcement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Announcement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Announcement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $announcement = $this->model->findOrFail($id);
        $result = $this->getForm($announcement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Announcement::edit',
            compact('announcement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'audience' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'expired_at' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $announcement = $this->model->findOrFail($id);
            DB::transaction(function () use ($announcement, $validated) {
                $announcement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Announcement berhasil diperbarui.',
                $announcement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Announcement.',
                [
                    'exception' => $e,
                    'Announcement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Announcement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $announcement = $this->model->findOrFail($id);

            DB::transaction(function () use ($announcement) {
                $announcement->delete();
            });

            return $this->successResponse(
                $request,
                'Data Announcement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $announcement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Announcement.',
                [
                    'exception' => $e,
                    'Announcement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Announcement gagal dihapus.'
            );
        }
    }
}
