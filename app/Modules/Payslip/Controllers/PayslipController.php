<?php

namespace App\Modules\Payslip\Controllers;

use App\DataTables\PayslipDataTable;
use App\Http\Controllers\MainController;
use Models\Employee;
use Models\PayrollDetail;
use Models\PayrollPeriod;
use Models\PayrollRun;
use Models\Payslip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class PayslipController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Payslip(), 'payslip');
    }

    /**
     * Display a listing of payslips.
     */
    public function index(Request $request, PayslipDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payslip::index');
    }

    /**
     * Show form to create new payslip.
     */
    public function create(): View
    {
        $payslip = new Payslip();
        $payslip->payslip_number = $payslip->generateUniqueCode();
        $payslip->currency = 'IDR';
        $payslip->status = 'draft';
        $payslip->generated_at = now();

        $employees = Employee::orderBy('name')->pluck('name', 'id')->toArray();
        $payroll_periods = PayrollPeriod::orderBy('id', 'desc')->pluck('period', 'id')->toArray();
        $payroll_details = PayrollDetail::with('employee')->orderBy('id', 'desc')->get()->mapWithKeys(function ($item) {
            $empName = $item->employee_name_snapshot ?? $item->employee?->name ?? ('Karyawan #' . $item->employee_id);
            return [$item->id => $empName . ' (Net: Rp ' . number_format((float) $item->net_salary, 0, ',', '.') . ')'];
        })->toArray();

        return view(
            'Payslip::create',
            compact('payslip', 'employees', 'payroll_periods', 'payroll_details')
        );
    }

    /**
     * Store a newly created payslip.
     */
    public function store(Request $request)
    {
        $orgId = $request->input('organization_id')
            ?? session('active_organization_id')
            ?? auth()->user()?->active_organization_id
            ?? 1;

        $validated = $request->validate([
            'payroll_detail_id' => [
                'nullable',
                'integer',
                'exists:payroll_details,id',
                'unique:payslips,payroll_detail_id',
            ],
            'employee_id' => 'required|integer|exists:employees,id',
            'payroll_period_id' => 'required|integer|exists:payroll_periods,id',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'payslip_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('payslips', 'payslip_number')->where(function ($query) use ($orgId) {
                    return $query->where('organization_id', $orgId);
                }),
            ],
            'total_allowance' => 'nullable|numeric|min:0',
            'total_deduction' => 'nullable|numeric|min:0',
            'gross_salary'    => 'nullable|numeric|min:0',
            'net_salary'      => 'nullable|numeric|min:0',
            'currency'        => 'nullable|string|max:3',
            'status'          => 'required|in:draft,generated,sent,downloaded,void',
            'generated_at'    => 'nullable|date',
            'pdf_path'        => 'nullable|string|max:1000',
            'html_content'    => 'nullable|string',
            'is_sent'         => 'nullable|boolean',
            'sent_at'         => 'nullable|date',
            'is_downloaded'   => 'nullable|boolean',
            'downloaded_at'   => 'nullable|date',
        ]);

        try {
            $validated['organization_id'] = $orgId;

            // Generate otomatis payslip_number jika kosong
            if (empty($validated['payslip_number'])) {
                $payslipModel = new Payslip();
                $validated['payslip_number'] = $payslipModel->generateUniqueCode($orgId);
            }

            // Normalisasi nilai default
            $validated['total_allowance'] = $validated['total_allowance'] ?? 0.00;
            $validated['total_deduction'] = $validated['total_deduction'] ?? 0.00;
            $validated['gross_salary']    = $validated['gross_salary'] ?? 0.00;
            $validated['net_salary']      = $validated['net_salary'] ?? 0.00;
            $validated['currency']        = $validated['currency'] ?? 'IDR';
            $validated['status']          = $validated['status'] ?? 'draft';
            $validated['is_sent']         = $request->boolean('is_sent');
            $validated['is_downloaded']   = $request->boolean('is_downloaded');

            if ($validated['is_sent'] && empty($validated['sent_at'])) {
                $validated['sent_at'] = now();
            }
            if ($validated['is_downloaded'] && empty($validated['downloaded_at'])) {
                $validated['downloaded_at'] = now();
            }
            if (empty($validated['generated_at'])) {
                $validated['generated_at'] = now();
            }

            $payslip = DB::transaction(function () use ($validated, $orgId) {
                // Auto-provision PayrollDetail jika belum disediakan
                if (empty($validated['payroll_detail_id'])) {
                    $existingDetail = PayrollDetail::where('employee_id', $validated['employee_id'])
                        ->whereDoesntHave('payslip')
                        ->first();

                    if ($existingDetail) {
                        $validated['payroll_detail_id'] = $existingDetail->id;
                    } else {
                        $payrollRun = PayrollRun::firstOrCreate(
                            [
                                'payroll_period_id' => $validated['payroll_period_id'],
                                'organization_id'   => $orgId,
                            ],
                            [
                                'run_number'  => 'PR/' . date('Ym') . '/001',
                                'run_type'    => 'regular',
                                'status'      => 'draft',
                                'total_gross' => $validated['gross_salary'],
                                'total_net'   => $validated['net_salary'],
                            ]
                        );

                        $employee = Employee::find($validated['employee_id']);

                        $newDetail = PayrollDetail::create([
                            'payroll_run_id'           => $payrollRun->id,
                            'employee_id'              => $validated['employee_id'],
                            'employee_number_snapshot' => $employee?->employee_number ?? ('EMP-' . $validated['employee_id']),
                            'employee_name_snapshot'   => $employee?->name ?? ('Employee #' . $validated['employee_id']),
                            'gross_salary'             => $validated['gross_salary'],
                            'total_deduction'          => $validated['total_deduction'],
                            'net_salary'               => $validated['net_salary'],
                            'status'                   => 'calculated',
                        ]);

                        $validated['payroll_detail_id'] = $newDetail->id;
                    }
                }

                return Payslip::create($validated);
            });

            return $this->successResponse(
                $request,
                'Payslip berhasil ditambahkan.',
                $payslip
            );
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan payslip.', [
                'exception' => $e,
                'data'      => $validated,
                'user_id'   => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payslip gagal ditambahkan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show detailed payslip view.
     */
    public function show($id): View
    {
        $payslip = $this->model->with(['employee', 'payrollPeriod', 'payrollDetail', 'organization'])->findOrFail($id);

        return view('Payslip::show', compact('payslip'));
    }

    /**
     * Preview payslip.
     */
    public function preview($id): View
    {
        return $this->show($id);
    }

    /**
     * Print payslip.
     */
    public function print($id): View
    {
        $payslip = $this->model->with(['employee', 'payrollPeriod', 'payrollDetail', 'organization'])->findOrFail($id);

        return view('Payslip::print', compact('payslip'));
    }

    /**
     * Edit payslip.
     */
    public function edit($id): View
    {
        $payslip = $this->model->with(['employee', 'payrollPeriod', 'payrollDetail'])->findOrFail($id);

        $employees = Employee::orderBy('name')->pluck('name', 'id')->toArray();
        $payroll_periods = PayrollPeriod::orderBy('id', 'desc')->pluck('period', 'id')->toArray();
        $payroll_details = PayrollDetail::with('employee')->orderBy('id', 'desc')->get()->mapWithKeys(function ($item) {
            $empName = $item->employee_name_snapshot ?? $item->employee?->name ?? ('Karyawan #' . $item->employee_id);
            return [$item->id => $empName . ' (Net: Rp ' . number_format((float) $item->net_salary, 0, ',', '.') . ')'];
        })->toArray();

        return view(
            'Payslip::edit',
            compact('payslip', 'employees', 'payroll_periods', 'payroll_details')
        );
    }

    /**
     * Update payslip.
     */
    public function update(Request $request, $id)
    {
        $payslip = $this->model->findOrFail($id);

        $validated = $request->validate([
            'payroll_detail_id' => [
                'nullable',
                'integer',
                'exists:payroll_details,id',
                Rule::unique('payslips', 'payroll_detail_id')->ignore($id),
            ],
            'employee_id' => 'required|integer|exists:employees,id',
            'payroll_period_id' => 'required|integer|exists:payroll_periods,id',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'payslip_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payslips', 'payslip_number')
                    ->where(fn($q) => $q->where('organization_id', $payslip->organization_id))
                    ->ignore($id),
            ],
            'total_allowance' => 'nullable|numeric|min:0',
            'total_deduction' => 'nullable|numeric|min:0',
            'gross_salary'    => 'nullable|numeric|min:0',
            'net_salary'      => 'nullable|numeric|min:0',
            'currency'        => 'nullable|string|max:3',
            'status'          => 'required|in:draft,generated,sent,downloaded,void',
            'generated_at'    => 'nullable|date',
            'pdf_path'        => 'nullable|string|max:1000',
            'html_content'    => 'nullable|string',
            'is_sent'         => 'nullable|boolean',
            'sent_at'         => 'nullable|date',
            'is_downloaded'   => 'nullable|boolean',
            'downloaded_at'   => 'nullable|date',
        ]);

        try {
            $validated['total_allowance'] = $validated['total_allowance'] ?? 0.00;
            $validated['total_deduction'] = $validated['total_deduction'] ?? 0.00;
            $validated['gross_salary']    = $validated['gross_salary'] ?? 0.00;
            $validated['net_salary']      = $validated['net_salary'] ?? 0.00;
            $validated['currency']        = $validated['currency'] ?? 'IDR';
            $validated['is_sent']         = $request->boolean('is_sent');
            $validated['is_downloaded']   = $request->boolean('is_downloaded');

            if ($validated['is_sent'] && empty($validated['sent_at'])) {
                $validated['sent_at'] = now();
            }
            if ($validated['is_downloaded'] && empty($validated['downloaded_at'])) {
                $validated['downloaded_at'] = now();
            }

            DB::transaction(function () use ($payslip, $validated) {
                $payslip->update($validated);
            });

            return $this->successResponse(
                $request,
                'Payslip berhasil diperbarui.',
                $payslip
            );
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui payslip.', [
                'exception'  => $e,
                'payslip_id' => $id,
                'data'       => $validated,
                'user_id'    => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payslip gagal diperbarui: ' . $e->getMessage()
            );
        }
    }

    /**
     * Delete payslip.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payslip = $this->model->findOrFail($id);

            DB::transaction(function () use ($payslip) {
                $payslip->delete();
            });

            return $this->successResponse(
                $request,
                'Payslip berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menghapus payslip.', [
                'exception'  => $e,
                'payslip_id' => $id,
                'user_id'    => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payslip gagal dihapus: ' . $e->getMessage()
            );
        }
    }

    /**
     * Search payslips for autocomplete / select2.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $items = Payslip::with(['employee', 'payrollPeriod'])
            ->where('payslip_number', 'LIKE', "%{$q}%")
            ->orWhereHas('employee', function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%");
            })
            ->limit(20)
            ->get(['id', 'payslip_number', 'employee_id', 'payroll_period_id', 'net_salary', 'status']);

        return response()->json($items);
    }
}
