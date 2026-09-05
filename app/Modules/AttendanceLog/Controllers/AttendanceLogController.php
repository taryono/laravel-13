<?php

namespace App\Modules\AttendanceLog\Controllers;

use App\DataTables\AttendanceLogDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceLog;
use Models\AttendanceImport;
use App\Services\Attendance\FingerprintImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceLogController extends MainController
{
    protected $importer;

    public function __construct(FingerprintImporter $importer)
    {
        parent::__construct(new AttendanceLog());
        $this->importer = $importer;
    }

    /**
     * Display a listing of attendance logs.
     */
    public function index(Request $request, AttendanceLogDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        $totalUnprocessed = AttendanceLog::where('is_processed', false)->count();
        $batches = AttendanceImport::orderBy('created_at', 'desc')->limit(10)->get();

        return $dataTable->render('AttendanceImport::index', compact('totalUnprocessed', 'batches'));
    }

    /**
     * Show import form
     */
    public function importForm(): View
    {
        return view('AttendanceImport::import');
    }

    /**
     * Import fingerprint data from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'machine_id' => 'nullable|string|max:50',
        ]);

        try {
            $batch = DB::transaction(function () use ($request) {
                $file = $request->file('file');
                $path = $file->store('attendance-imports', 'public');

                $batch = AttendanceImport::create([
                    'batch_code' => 'BATCH-' . date('YmdHis'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_path' => $path,
                    'machine_source' => $request->machine_id,
                    'status' => 'uploaded',
                    'imported_by' => auth()->id(),
                ]);

                return $batch;
            });

            // Proses import secara asynchronous atau langsung
            $this->importer->import($batch, $request->file('file'));

            return $this->successResponse($request, 'Data absensi berhasil diimport.', $batch);
        } catch (Throwable $e) {
            Log::error('Gagal import absensi.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal mengimport data absensi: ' . $e->getMessage());
        }
    }

    /**
     * Preview import data before processing
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        try {
            $preview = $this->importer->preview($request->file('file'));
            
            return response()->json([
                'success' => true,
                'data' => $preview,
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal preview import.', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal preview data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process unprocessed attendance logs
     */
    public function processUnprocessed(Request $request)
    {
        try {
            $count = AttendanceLog::where('is_processed', false)->count();
            
            if ($count === 0) {
                return $this->errorResponse($request, 'Tidak ada data yang perlu diproses.');
            }

            // Panggil service untuk memproses
            $processed = app(AttendanceProcessor::class)->processBatch();

            return $this->successResponse(
                $request, 
                "Berhasil memproses {$processed['processed']} data absensi. " .
                "Gagal: {$processed['failed']} data."
            );
        } catch (Throwable $e) {
            Log::error('Gagal proses data absensi.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal memproses data absensi.');
        }
    }

    /**
     * Get attendance logs for a specific employee
     */
    public function getByEmployee(Request $request, $employeeId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $logs = AttendanceLog::where('employee_id', $employeeId)
            ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
            ->orderBy('scan_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Delete specific log
     */
    public function destroy(Request $request, $id)
    {
        try {
            $log = $this->model->findOrFail($id);

            if ($log->is_processed) {
                return $this->errorResponse($request, 'Data sudah diproses dan tidak dapat dihapus.');
            }

            DB::transaction(function () use ($log) {
                $log->delete();
            });

            return $this->successResponse($request, 'Data log absensi berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus log absensi.', ['exception' => $e, 'log_id' => $id]);
            return $this->errorResponse($request, 'Gagal menghapus log absensi.');
        }
    }
}