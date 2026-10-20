<?php

namespace App\Modules\StudentTransportSubscription\Controllers;

use App\DataTables\StudentTransportSubscriptionDataTable;
use App\Http\Controllers\MainController;
use Models\StudentTransportSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentTransportSubscriptionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentTransportSubscription());
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
        StudentTransportSubscriptionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-transport-subscription?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentTransportSubscription::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentTransportSubscription = new StudentTransportSubscription();
        $result = $this->getForm($studentTransportSubscription);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentTransportSubscription::create',
            compact('studentTransportSubscription', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_id' => ['required', 'integer'],
            'transport_route_id' => ['required', 'integer'],
            'transport_stop_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'service_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentTransportSubscription::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentTransportSubscription berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentTransportSubscription.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTransportSubscription gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentTransportSubscription = $this->model->findOrFail($id);
        $result = $this->getForm($studentTransportSubscription);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentTransportSubscription::edit',
            compact('studentTransportSubscription', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_id' => ['required', 'integer'],
            'transport_route_id' => ['required', 'integer'],
            'transport_stop_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'service_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentTransportSubscription = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentTransportSubscription,
                $validated
            ) {
                $studentTransportSubscription->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentTransportSubscription berhasil diperbarui.',
                $studentTransportSubscription
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentTransportSubscription.',
                [
                    'exception' => $e,
                    'StudentTransportSubscription_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTransportSubscription gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentTransportSubscription = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentTransportSubscription) {
                $studentTransportSubscription->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentTransportSubscription berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentTransportSubscription->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentTransportSubscription.',
                [
                    'exception' => $e,
                    'StudentTransportSubscription_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentTransportSubscription gagal dihapus.'
            );
        }
    } 
}
