<?php

namespace App\Modules\TrialRegistration\Controllers;

use App\DataTables\TrialRegistrationDataTable;
use App\Http\Controllers\MainController;
use Models\TrialRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TrialRegistrationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TrialRegistration());
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
        TrialRegistrationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /trial-registration?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TrialRegistration::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $trialRegistration = new TrialRegistration();
        $result = $this->getForm($trialRegistration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TrialRegistration::edit',
            compact('trialRegistration', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TrialRegistration::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TrialRegistration berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TrialRegistration.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TrialRegistration gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $trialRegistration = $this->model->findOrFail($id);
        $result = $this->getForm($trialRegistration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TrialRegistration::edit',
            compact('trialRegistration', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $trialRegistration = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $trialRegistration,
                $validated
            ) {
                $trialRegistration->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TrialRegistration berhasil diperbarui.',
                $trialRegistration
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $trialRegistration->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $trialRegistration belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui TrialRegistration.',
                [
                    'exception' => $e,
                    'TrialRegistration_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TrialRegistration gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $trialRegistration = $this->model->findOrFail($id);

            DB::transaction(function () use ($trialRegistration) {
                $trialRegistration->delete();
            });

            return $this->successResponse(
                $request,
                'Data TrialRegistration berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $trialRegistration->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TrialRegistration.',
                [
                    'exception' => $e,
                    'TrialRegistration_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TrialRegistration gagal dihapus.'
            );
        }
    }

    /**
     * Return successful response.
     */
    protected function successResponse(
        Request $request,
        string $message,
        $data = null
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response);
        }

        return redirect()
            ->route('trial_registration.index')
            ->with('success', $message);
    }

    /**
     * Return failed response.
     */
    protected function errorResponse(
        Request $request,
        string $message,
        int $status = 500
    ): JsonResponse|RedirectResponse {

        if ($this->isAjaxRequest($request)) {

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }

    /**
     * Determine whether the request expects an AJAX response.
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->expectsJson();
    }
}
