<?php

namespace App\Modules\PayrollIntegration\Controllers;

use App\DataTables\PayrollIntegrationDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollIntegration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollIntegrationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollIntegration());
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
        PayrollIntegrationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payroll-integration?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PayrollIntegration::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $payrollIntegration = new PayrollIntegration();
        $result = $this->getForm($payrollIntegration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollIntegration::create',
            compact('payrollIntegration', 'forms', 'groupedForms')
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
                return PayrollIntegration::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollIntegration berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PayrollIntegration.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollIntegration gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $payrollIntegration = $this->model->findOrFail($id);
        $result = $this->getForm($payrollIntegration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollIntegration::edit',
            compact('payrollIntegration', 'forms', 'groupedForms')
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
            $payrollIntegration = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollIntegration, $validated) {
                $payrollIntegration->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollIntegration berhasil diperbarui.',
                $payrollIntegration
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $payrollIntegration->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $payrollIntegration belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui PayrollIntegration.',
                [
                    'exception' => $e,
                    'PayrollIntegration_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollIntegration gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollIntegration = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollIntegration) {
                $payrollIntegration->delete();
            });

            return $this->successResponse(
                $request,
                'Data PayrollIntegration berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $payrollIntegration->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PayrollIntegration.',
                [
                    'exception' => $e,
                    'PayrollIntegration_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollIntegration gagal dihapus.'
            );
        }
    }

    /**
     * Return successful response.
     */
    protected function successResponse(
        Request $request,
        string $message,
        $data = null,
        ?string $redirectTo = null
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
            ->route('payroll_integration.index')
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
