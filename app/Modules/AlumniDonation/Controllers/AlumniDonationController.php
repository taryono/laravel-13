<?php

namespace App\Modules\AlumniDonation\Controllers;

use App\DataTables\AlumniDonationDataTable;
use App\Http\Controllers\MainController;
use Models\AlumniDonation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AlumniDonationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AlumniDonation());
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
        AlumniDonationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /alumni-donation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AlumniDonation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $alumniDonation = new AlumniDonation();
        $result = $this->getForm($alumniDonation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AlumniDonation::create',
            compact('alumniDonation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'alumni_id' => ['required', 'integer'],
            'donation_number' => ['required', 'string', 'max:255'],
            'campaign_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AlumniDonation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AlumniDonation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AlumniDonation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AlumniDonation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $alumniDonation = $this->model->findOrFail($id);
        $result = $this->getForm($alumniDonation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AlumniDonation::edit',
            compact('alumniDonation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'alumni_id' => ['required', 'integer'],
            'donation_number' => ['required', 'string', 'max:255'],
            'campaign_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $alumniDonation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $alumniDonation,
                $validated
            ) {
                $alumniDonation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AlumniDonation berhasil diperbarui.',
                $alumniDonation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AlumniDonation.',
                [
                    'exception' => $e,
                    'AlumniDonation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AlumniDonation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $alumniDonation = $this->model->findOrFail($id);

            DB::transaction(function () use ($alumniDonation) {
                $alumniDonation->delete();
            });

            return $this->successResponse(
                $request,
                'Data AlumniDonation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $alumniDonation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AlumniDonation.',
                [
                    'exception' => $e,
                    'AlumniDonation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AlumniDonation gagal dihapus.'
            );
        }
    } 
}
