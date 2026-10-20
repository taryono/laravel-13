<?php

namespace App\Modules\StudentRfidCard\Controllers;

use App\DataTables\StudentRfidCardDataTable;
use App\Http\Controllers\MainController;
use Models\StudentRfidCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentRfidCardController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentRfidCard());
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
        StudentRfidCardDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-rfid-card?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentRfidCard::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentRfidCard = new StudentRfidCard();
        $result = $this->getForm($studentRfidCard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRfidCard::create',
            compact('studentRfidCard', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'card_uid' => ['required', 'string', 'max:255'],
            'pin_hash' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'string', 'max:255'],
            'blocked_at' => ['required', 'string', 'max:255'],
            'blocked_reason' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentRfidCard::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRfidCard berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentRfidCard.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRfidCard gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentRfidCard = $this->model->findOrFail($id);
        $result = $this->getForm($studentRfidCard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRfidCard::edit',
            compact('studentRfidCard', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'card_uid' => ['required', 'string', 'max:255'],
            'pin_hash' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'string', 'max:255'],
            'blocked_at' => ['required', 'string', 'max:255'],
            'blocked_reason' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentRfidCard = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentRfidCard,
                $validated
            ) {
                $studentRfidCard->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRfidCard berhasil diperbarui.',
                $studentRfidCard
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentRfidCard.',
                [
                    'exception' => $e,
                    'StudentRfidCard_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRfidCard gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentRfidCard = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentRfidCard) {
                $studentRfidCard->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentRfidCard berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentRfidCard->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentRfidCard.',
                [
                    'exception' => $e,
                    'StudentRfidCard_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRfidCard gagal dihapus.'
            );
        }
    } 
}
