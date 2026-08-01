<?php

namespace App\Modules\CrudGenerator\Controllers;

use App\DataTables\CrudGeneratorDataTable;
use App\Http\Controllers\Controller;
use Models\CrudGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CrudGeneratorController extends Controller
{
     /**
     * Tampilkan form generator CRUD
     */
    public function index()
    {
        // Daftar ikon yang tersedia untuk dropdown
        $icons = [
            'inventory_2' => 'Product',
            'category' => 'Category',
            'people' => 'User',
            'badge' => 'Role',
            'lock' => 'Permission',
            'settings' => 'Setting',
            'assessment' => 'Report',
            'shopping_cart' => 'Order',
            'payments' => 'Payment',
            'person' => 'Customer',
            'local_shipping' => 'Supplier',
            'groups' => 'Employee',
            'list_alt' => 'Default',
        ];

        return view('CrudGenerator::index', compact('icons'));
    }

    /**
     * Proses generate CRUD
     */
    public function generate(Request $request)
    {
        $request->validate([
            'model_name' => 'required|string|max:100|regex:/^[a-zA-Z]+$/',
            'columns' => 'required|string|max:500',
            'icon' => 'nullable|string',
            'create_menu' => 'boolean',
            'parent_menu' => 'nullable|string',
        ]);

        $modelName = Str::studly($request->model_name);
        $columns = $request->columns;
        
        // Bangun command
        $command = "make:crud-yajra {$modelName} --columns={$columns}";
        
        // Tambahkan opsi no-menu jika tidak ingin membuat menu
        if (!$request->boolean('create_menu')) {
            $command .= " --no-menu";
        }

        // Simpan ke session untuk tracking
        $processId = uniqid('crud_', true);
        session()->put("crud_process_{$processId}", [
            'command' => $command,
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            // Eksekusi command dengan output capture
            $output = new \Symfony\Component\Console\Output\BufferedOutput();
            
            Artisan::call($command, [], $output);
            
            $result = [
                'success' => true,
                'message' => "CRUD {$modelName} berhasil dibuat!",
                'output' => $output->fetch(),
                'command' => $command,
            ];

            // Update session
            session()->put("crud_process_{$processId}", [
                'command' => $command,
                'status' => 'completed',
                'completed_at' => now(),
                'result' => $result,
            ]);

            Log::info("CRUD Generated via Web", [
                'model' => $modelName,
                'columns' => $columns,
                'user' => auth()->user()->email ?? 'system',
                'command' => $command,
            ]);

            return redirect()->route('CrudGenerator::index')
                ->with('success', $result['message'])
                ->with('output', $result['output']);

        } catch (\Exception $e) {
            Log::error("CRUD Generator Error", [
                'error' => $e->getMessage(),
                'command' => $command,
                'trace' => $e->getTraceAsString(),
            ]);

            session()->put("crud_process_{$processId}", [
                'command' => $command,
                'status' => 'failed',
                'error' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return redirect()->route('CrudGenerator::index')
                ->with('error', "Gagal generate CRUD: " . $e->getMessage());
        }
    }

    /**
     * Jalankan command secara async (background)
     */
    public function generateAsync(Request $request)
    {
        $request->validate([
            'model_name' => 'required|string|max:100|regex:/^[a-zA-Z]+$/',
            'columns' => 'required|string|max:500',
            'create_menu' => 'boolean',
        ]);

        $modelName = Str::studly($request->model_name);
        $columns = $request->columns;
        
        $command = "make:crud-yajra {$modelName} --columns={$columns}";
        
        if (!$request->boolean('create_menu')) {
            $command .= " --no-menu";
        }

        // Gunakan queue untuk async processing
        dispatch(new \App\Jobs\GenerateCrudJob($command, auth()->id()));

        return redirect()->route('CrudGenerator::index')
            ->with('info', "CRUD {$modelName} sedang diproses di background. Anda akan diberitahu saat selesai.");
    }

    /**
     * Cek status proses
     */
    public function status($processId)
    {
        $process = session()->get("crud_process_{$processId}");
        
        if (!$process) {
            return response()->json(['error' => 'Process not found'], 404);
        }

        return response()->json($process);
    }

    /**
     * Daftar history generate
     */
    public function history()
    {
        // Ambil dari log atau database
        $logs = Log::getLogger();
        
        return view('CrudGenerator::history');
    }
}
