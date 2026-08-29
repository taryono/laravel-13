<?php

namespace App\Modules\PayrollSetting\Controllers;

use App\DataTables\PayrollSettingDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 

class PayrollSettingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollSetting());
    }

    public function index(Request $request, PayrollSettingDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::payroll_setting.index');
    }

    public function create(): View
    {
        $setting = new PayrollSetting();
        $result = $this->getForm($setting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::payroll_setting.edit', compact('setting', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:payroll_settings,key',
            'value' => 'nullable|string',
            'group' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $setting = DB::transaction(function () use ($validated) {
                return PayrollSetting::create($validated);
            });

            return $this->successResponse($request, 'Data pengaturan berhasil ditambahkan.', $setting);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan pengaturan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data pengaturan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $setting = $this->model->findOrFail($id);
        $result = $this->getForm($setting);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::payroll_setting.edit', compact('setting', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $setting = $this->model->findOrFail($id);

        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:payroll_settings,key,' . $id,
            'value' => 'nullable|string',
            'group' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($setting, $validated) {
                $setting->update($validated);
            });

            return $this->successResponse($request, 'Data pengaturan berhasil diperbarui.', $setting);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui pengaturan.', ['exception' => $e, 'setting_id' => $id]);
            return $this->errorResponse($request, 'Data pengaturan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $setting = $this->model->findOrFail($id);
            DB::transaction(function () use ($setting) {
                $setting->delete();
            });

            return $this->successResponse($request, 'Data pengaturan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus pengaturan.', ['exception' => $e, 'setting_id' => $id]);
            return $this->errorResponse($request, 'Data pengaturan gagal dihapus.');
        }
    }

    public function getByGroup($group): JsonResponse
    {
        try {
            $settings = PayrollSetting::where('group', $group)
                ->where('is_active', 1)
                ->get();

            return response()->json(['success' => true, 'data' => $settings]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil pengaturan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
