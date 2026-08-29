<?php

namespace App\Modules\ReportTemplate\Controllers;

use App\DataTables\ReportTemplateDataTable;
use App\Http\Controllers\MainController;
use Models\ReportTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
class ReportTemplateController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportTemplate());
    }

    public function index(Request $request, ReportTemplateDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::report_template.index');
    }

    public function create(): View
    {
        $template = new ReportTemplate();
        $result = $this->getForm($template);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::report_template.edit', compact('template', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_code' => 'required|string|max:255|unique:report_templates,report_code',
            'report_name' => 'required|string|max:255',
            'category' => 'required|in:payroll,financial,hr,tax,audit',
            'template' => 'required|json',
            'parameters' => 'nullable|json',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $template = DB::transaction(function () use ($validated) {
                if ($validated['is_default'] ?? false) {
                    ReportTemplate::where('category', $validated['category'])
                        ->where('is_default', 1)
                        ->update(['is_default' => 0]);
                }
                return ReportTemplate::create($validated);
            });

            return $this->successResponse($request, 'Data template report berhasil ditambahkan.', $template);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan template report.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data template report gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $template = $this->model->findOrFail($id);
        $result = $this->getForm($template);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::report_template.edit', compact('template', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $template = $this->model->findOrFail($id);

        $validated = $request->validate([
            'report_code' => 'required|string|max:255|unique:report_templates,report_code,' . $id,
            'report_name' => 'required|string|max:255',
            'category' => 'required|in:payroll,financial,hr,tax,audit',
            'template' => 'required|json',
            'parameters' => 'nullable|json',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($template, $validated) {
                if ($validated['is_default'] ?? false) {
                    ReportTemplate::where('category', $validated['category'])
                        ->where('is_default', 1)
                        ->where('id', '!=', $template->id)
                        ->update(['is_default' => 0]);
                }
                $template->update($validated);
            });

            return $this->successResponse($request, 'Data template report berhasil diperbarui.', $template);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui template report.', ['exception' => $e, 'template_id' => $id]);
            return $this->errorResponse($request, 'Data template report gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $template = $this->model->findOrFail($id);
            DB::transaction(function () use ($template) {
                $template->delete();
            });

            return $this->successResponse($request, 'Data template report berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus template report.', ['exception' => $e, 'template_id' => $id]);
            return $this->errorResponse($request, 'Data template report gagal dihapus.');
        }
    }

    public function getByCategory($category): JsonResponse
    {
        try {
            $templates = ReportTemplate::where('category', $category)
                ->where('is_active', 1)
                ->get();

            return response()->json(['success' => true, 'data' => $templates]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil template report.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function preview($id): JsonResponse
    {
        try {
            $template = $this->model->findOrFail($id);
            
            // Generate preview based on template configuration
            // Implementasi sesuai kebutuhan

            return response()->json([
                'success' => true,
                'data' => [
                    'template' => $template,
                    'preview' => 'Preview data here',
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal preview template report.', ['exception' => $e, 'template_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Gagal preview template.'], 500);
        }
    }
}
