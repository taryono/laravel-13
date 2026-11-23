<?php

namespace App\Modules\AdmissionAnnouncement\Controllers;

use App\DataTables\AdmissionAnnouncementDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionAnnouncement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionAnnouncementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionAnnouncement());
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
        AdmissionAnnouncementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-announcement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionAnnouncement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionAnnouncement = new AdmissionAnnouncement();
        $result = $this->getForm($admissionAnnouncement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionAnnouncement::create',
            compact('admissionAnnouncement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'announcement_type' => ['required', 'string', 'max:255'],
            'announcement_date' => ['required', 'string', 'max:255'],
            'publish_date' => ['required', 'string', 'max:255'],
            'expired_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'requires_login' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'external_url' => ['required', 'string', 'max:255'],
            'show_ranking' => ['required', 'string', 'max:255'],
            'show_score' => ['required', 'string', 'max:255'],
            'total_participants' => ['required', 'string', 'max:255'],
            'total_passed' => ['required', 'string', 'max:255'],
            'total_failed' => ['required', 'string', 'max:255'],
            'total_waitlist' => ['required', 'string', 'max:255'],
            'notification_sent' => ['required', 'string', 'max:255'],
            'notification_sent_at' => ['required', 'string', 'max:255'],
            'notification_channels' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'views_count' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionAnnouncement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionAnnouncement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionAnnouncement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionAnnouncement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionAnnouncement = $this->model->findOrFail($id);
        $result = $this->getForm($admissionAnnouncement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionAnnouncement::edit',
            compact('admissionAnnouncement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'announcement_type' => ['required', 'string', 'max:255'],
            'announcement_date' => ['required', 'string', 'max:255'],
            'publish_date' => ['required', 'string', 'max:255'],
            'expired_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'requires_login' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'external_url' => ['required', 'string', 'max:255'],
            'show_ranking' => ['required', 'string', 'max:255'],
            'show_score' => ['required', 'string', 'max:255'],
            'total_participants' => ['required', 'string', 'max:255'],
            'total_passed' => ['required', 'string', 'max:255'],
            'total_failed' => ['required', 'string', 'max:255'],
            'total_waitlist' => ['required', 'string', 'max:255'],
            'notification_sent' => ['required', 'string', 'max:255'],
            'notification_sent_at' => ['required', 'string', 'max:255'],
            'notification_channels' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'views_count' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionAnnouncement = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionAnnouncement, $validated) {
                $admissionAnnouncement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionAnnouncement berhasil diperbarui.',
                $admissionAnnouncement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionAnnouncement.',
                [
                    'exception' => $e,
                    'AdmissionAnnouncement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionAnnouncement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionAnnouncement = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionAnnouncement) {
                $admissionAnnouncement->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionAnnouncement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionAnnouncement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionAnnouncement.',
                [
                    'exception' => $e,
                    'AdmissionAnnouncement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionAnnouncement gagal dihapus.'
            );
        }
    }
}
