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
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Throwable;
use Illuminate\Support\Str;

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
        // dump(auth()->user()->organizationIds());
        $tables = $this->listModulesToGenerate(); 
        return view('CrudGenerator::index', compact('icons', 'tables'));
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
            $command .= " --no-menu --model=\App\{$modelName}";
        }
        try { 
    
            // Simpan ke session untuk tracking
            $processId = uniqid('crud_', true);
            session()->put("crud_process_{$processId}", [
                'command' => $command,
                'status' => 'running',
                'started_at' => now(),
            ]);
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

            return redirect()->route('crud_generator.index')
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

            return redirect()->route('crud_generator.index')
                ->with('error', "Gagal generate CRUD: " . $e->getMessage());
        }
    }

    /**
     * Jalankan command secara async (background)
     * # Jalankan worker untuk queue spesifik
     * php artisan queue:work --queue=default 
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
        $createMenu = $request->boolean('create_menu');
        
        // Gunakan Artisan::queue() untuk menjalankan di background
        $command = "make:crud-yajra {$modelName} --model=\\App\\Models\\{$modelName} --columns={$columns}";
        
        if (!$createMenu) {
            $command .= " --no-menu";
        }
        Artisan::call('queue:work --queue=default --stop-when-empty');
        // Artisan::queue akan otomatis mendispatch ke queue
        Artisan::queue($command, [], 'default');
        
        return redirect()->route('crud_generator.index')
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

    protected function listModulesToGenerate(){
        $database = DB::getDatabaseName();

        $columns = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ?
            ORDER BY TABLE_NAME, ORDINAL_POSITION
        ", [$database]);
        $excluded = ['CrudGenerator', 'Controllers', 'VIews', 'Module','partials', 'JobBatch','Job','MenuRole','Migration','Module','CacheLock','Cache',"RoleUser","Session","FailedJob", "ActionUser","PasswordResetToken"]; // nama yang mau di-exclude
        $excluded_to_snake = array_map(function($item) {
            return Str::plural(Str::snake($item));
        }, $excluded);
         
        $tables = collect($columns)
            ->groupBy('TABLE_NAME')
            ->map(function ($columns, $tableName) {
                return [
                    'name' => $tableName,
                    'label' => Str::studly(Str::singular($tableName)),
                    'columns' => $columns->pluck('COLUMN_NAME')->values()->toArray(),
                ];
            });
        
        $modules = (new \Lib\Directories(app_path('Modules')))->getModules();
        // Atau lebih simpel
        $names = $modules->map('basename');
        $filtered = $names->reject(function($name) use ($excluded) {
            return in_array($name, $excluded);
        })->toArray(); 
        $rejected = explode(",","action_user,cache,cache_locks,failed_jobs,job_batches,jobs,menu_role,migrations,modules,password_reset_tokens,role_user,sessions");
        $tables = $tables->filter(function ($table) use ($filtered, $rejected) { 
            return !in_array(Str::singular(Str::studly($table['name'])) , $filtered) && !in_array($table['name'] , $rejected);
        }); 
        return $tables;
    }

    // Di Controller
    public function checkQueueStatus()
    {
        $isRunning = $this->isQueueWorkerRunning();
        
        return response()->json([
            'running' => $isRunning,
            'message' => $isRunning ? 'Queue worker is running' : 'Queue worker is not running'
        ]);
    }

    private function isQueueWorkerRunning()
    {
        // Cek di Linux
        $output = shell_exec("pgrep -f 'queue:work'");
        return !empty(trim($output));
        
        // Atau cek via Supervisor
        // $output = shell_exec("supervisorctl status laravel-worker:* | grep RUNNING");
        // return str_contains($output, 'RUNNING');
    }
}
