<?php

namespace App\Modules\AttendanceImport\Controllers;

use App\DataTables\AttendanceImportDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceImportController extends MainController
{

 protected FingerprintImporter $importer;
    protected AttendanceProcessor $processor;

    public function __construct(
        FingerprintImporter $importer,
        AttendanceProcessor $processor
    ) {
        $this->importer = $importer;
        $this->processor = $processor;
        parent::__construct(new AttendanceImport());
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
        AttendanceImportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /attendance-import?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AttendanceImport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attendanceImport = new AttendanceImport();
        $result = $this->getForm($attendanceImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImport::create',
            compact('attendanceImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AttendanceImport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AttendanceImport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attendanceImport = $this->model->findOrFail($id);
        $result = $this->getForm($attendanceImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImport::edit',
            compact('attendanceImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        try {
            $attendanceImport = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $attendanceImport,
                $validated
            ) {
                $attendanceImport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImport berhasil diperbarui.',
                $attendanceImport
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $attendanceImport->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $attendanceImport belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui AttendanceImport.',
                [
                    'exception' => $e,
                    'AttendanceImport_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attendanceImport = $this->model->findOrFail($id);

            // Delete associated logs
            \Models\AttendanceLog::where('import_batch', $attendanceImport->batch_code)
                ->delete();

            // Delete file
            if ($attendanceImport->file_path && Storage::disk('public')->exists($attendanceImport->file_path)) {
                Storage::disk('public')->delete($attendanceImport->file_path);
            }

            DB::transaction(function () use ($attendanceImport) {
                $attendanceImport->delete();
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImport berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $attendanceImport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AttendanceImport.',
                [
                    'exception' => $e,
                    'AttendanceImport_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImport gagal dihapus.'
            );
        }
    } 

    /**
     * Import fingerprint data
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls,json|max:10240',
            'machine_id' => 'nullable|string|max:50',
        ]);

        try {
            // Validate file first
            $validation = $this->importer->validateFile($request->file('file'));
            
            if (!$validation['valid']) {
                return redirect()->back()
                    ->with('error', 'File tidak valid. Field yang hilang: ' . 
                        implode(', ', $validation['missing_fields'] ?? ['unknown']))
                    ->withInput();
            }

            // Create batch
            $batch = DB::transaction(function () use ($request, $validation) {
                $file = $request->file('file');
                $path = $file->store('attendance-imports', 'public');

                return AttendanceImportBatch::create([
                    'batch_code' => 'BATCH-' . date('YmdHis') . '-' . uniqid(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_path' => $path,
                    'machine_source' => $request->machine_id ?? $validation['format'] ?? 'unknown',
                    'total_records' => $validation['total_rows'] ?? 0,
                    'status' => 'uploaded',
                    'imported_by' => auth()->id(),
                ]);
            });

            // Process import
            $result = $this->importer->import($batch, $request->file('file'));

            // Process attendance if there were successful imports
            if ($result['processed'] > 0) {
                $processResult = $this->processor->processBatch();
                
                $message = "Berhasil mengimport {$result['processed']} data absensi. " .
                          "Gagal: {$result['failed']} data. " .
                          "Berhasil memproses {$processResult['processed']} data absensi.";
            } else {
                $message = "Import selesai. Tidak ada data yang berhasil diimport. " .
                          "Gagal: {$result['failed']} data.";
            }

            return redirect()->route('attendance.import.index')
                ->with('success', $message);

        } catch (\Throwable $e) {
            Log::error('Gagal import absensi.', [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Preview import file
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls,json|max:10240',
        ]);

        try {
            $preview = $this->importer->preview($request->file('file'));

            return response()->json([
                'success' => true,
                'data' => $preview,
            ]);

        } catch (\Throwable $e) {
            Log::error('Gagal preview import.', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal preview data: ' . $e->getMessage(),
            ], 500);
        }
    }

     /**
     * Get batch details
     */
    public function show($id)
    {
        $batch = AttendanceImportBatch::with(['importedBy'])->findOrFail($id);
        
        // Get logs for this batch
        $logs = \App\Modules\Attendance\Models\AttendanceLog::where('import_batch', $batch->batch_code)
            ->with(['employee'])
            ->limit(100)
            ->get();

        return view('AttendanceImport.detail', compact('batch', 'logs'));
    }
}
