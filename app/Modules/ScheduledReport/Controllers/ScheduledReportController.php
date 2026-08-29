<?php

namespace App\Modules\ScheduledReport\Controllers;

use App\DataTables\ScheduledReportDataTable;
use App\Http\Controllers\MainController;
use Models\ScheduledReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
class ScheduledReportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScheduledReport());
    }

    public function index(Request $request, ScheduledReportDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::scheduled_report.index');
    }

    public function create(): View
    {
        $schedule = new ScheduledReport();
        $templates = ReportTemplate::where('is_active', 1)->pluck('report_name', 'id');

        $result = $this->getForm($schedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::scheduled_report.edit', compact('schedule', 'forms', 'groupedForms', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_template_id' => 'required|exists:report_templates,id',
            'schedule_name' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'day_of_week' => 'nullable|integer|min:1|max:7|required_if:frequency,weekly',
            'day_of_month' => 'nullable|integer|min:1|max:31|required_if:frequency,monthly',
            'time' => 'required|date_format:H:i',
            'recipients' => 'required|json',
            'format' => 'required|in:pdf,excel,csv',
            'parameters' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        try {
            $schedule = DB::transaction(function () use ($validated) {
                // Calculate next run date
                $validated['next_run_at'] = $this->calculateNextRun($validated);
                return ScheduledReport::create($validated);
            });

            return $this->successResponse($request, 'Data jadwal report berhasil ditambahkan.', $schedule);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan jadwal report.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data jadwal report gagal ditambahkan.');
        }
    }

    private function calculateNextRun($schedule)
    {
        $now = now();
        
        switch ($schedule['frequency']) {
            case 'daily':
                return $now->setTimeFromTimeString($schedule['time'])->addDay();
            case 'weekly':
                $dayOfWeek = $schedule['day_of_week'] ?? 1;
                return $now->next($this->getDayName($dayOfWeek))->setTimeFromTimeString($schedule['time']);
            case 'monthly':
                $dayOfMonth = $schedule['day_of_month'] ?? 1;
                return $now->startOfMonth()->addDays($dayOfMonth - 1)->setTimeFromTimeString($schedule['time']);
            case 'quarterly':
                return $now->addMonths(3)->setTimeFromTimeString($schedule['time']);
            case 'yearly':
                return $now->addYear()->setTimeFromTimeString($schedule['time']);
            default:
                return $now->addDay();
        }
    }

    private function getDayName($day)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return $days[$day - 1] ?? 'Monday';
    }

    public function edit($id): View
    {
        $schedule = $this->model->findOrFail($id);
        $templates = ReportTemplate::where('is_active', 1)->pluck('report_name', 'id');

        $result = $this->getForm($schedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::scheduled_report.edit', compact('schedule', 'forms', 'groupedForms', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $schedule = $this->model->findOrFail($id);

        $validated = $request->validate([
            'report_template_id' => 'required|exists:report_templates,id',
            'schedule_name' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'day_of_week' => 'nullable|integer|min:1|max:7|required_if:frequency,weekly',
            'day_of_month' => 'nullable|integer|min:1|max:31|required_if:frequency,monthly',
            'time' => 'required|date_format:H:i',
            'recipients' => 'required|json',
            'format' => 'required|in:pdf,excel,csv',
            'parameters' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($schedule, $validated) {
                if ($schedule->is_active != ($validated['is_active'] ?? true)) {
                    $validated['next_run_at'] = $this->calculateNextRun($validated);
                }
                $schedule->update($validated);
            });

            return $this->successResponse($request, 'Data jadwal report berhasil diperbarui.', $schedule);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui jadwal report.', ['exception' => $e, 'schedule_id' => $id]);
            return $this->errorResponse($request, 'Data jadwal report gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $schedule = $this->model->findOrFail($id);
            DB::transaction(function () use ($schedule) {
                $schedule->delete();
            });

            return $this->successResponse($request, 'Data jadwal report berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus jadwal report.', ['exception' => $e, 'schedule_id' => $id]);
            return $this->errorResponse($request, 'Data jadwal report gagal dihapus.');
        }
    }

    public function toggleActive(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $schedule = $this->model->findOrFail($id);
            $newStatus = !$schedule->is_active;
            
            $schedule->update([
                'is_active' => $newStatus,
                'next_run_at' => $newStatus ? $this->calculateNextRun($schedule) : null,
            ]);

            return $this->successResponse(
                $request,
                'Status jadwal report berhasil diubah.',
                $schedule
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengubah status jadwal report.', ['exception' => $e, 'schedule_id' => $id]);
            return $this->errorResponse($request, 'Gagal mengubah status jadwal report.');
        }
    }

    public function runNow(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $schedule = $this->model->findOrFail($id);
            
            // Execute report generation
            // $this->reportService->generateAndSend($schedule);

            $schedule->update(['last_run_at' => now()]);

            return $this->successResponse($request, 'Report berhasil dijalankan.');
        } catch (Throwable $e) {
            Log::error('Gagal menjalankan report.', ['exception' => $e, 'schedule_id' => $id]);
            return $this->errorResponse($request, 'Gagal menjalankan report.');
        }
    }
}
