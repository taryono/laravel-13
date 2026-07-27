<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudYajraCommand extends Command
{
    protected $signature = 'make:crud-yajra
                            {name : Nama Model (contoh: Product)}';

    protected $description = 'Otomatis membuat CRUD lengkap dengan Yajra DataTables dan struktur Modular';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        /*
        |--------------------------------------------------------------------------
        | 1. Validasi Stub
        |--------------------------------------------------------------------------
        */

        $requiredStubs = [
            'controller.yajra.stub',
            'datatables.service.stub',
            'index.yajra.stub',
            'create.yajra.stub',
            'edit.yajra.stub',
            'route.yajra.stub',
        ];

        foreach ($requiredStubs as $stub) {
            $path = base_path("stubs/yajra/{$stub}");

            if (!File::exists($path)) {
                $this->error(
                    "Stub tidak ditemukan: stubs/yajra/{$stub}"
                );

                return Command::FAILURE;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Setup Module
        |--------------------------------------------------------------------------
        */

        $modulePath = app_path("Modules/{$name}");

        $controllerNamespace =
            "App\\Modules\\{$name}\\Controllers";

        $viewFolderKebab = Str::kebab($name);

        $modelVariable = Str::camel($name);

        /*
        |--------------------------------------------------------------------------
        | Route Prefix
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | Asesi -> asesi
        | Asesor -> asesor
        | AcademicYear -> academic-year
        |
        */

        $modelRoutePrefix = Str::kebab($name);

        /*
        |--------------------------------------------------------------------------
        | DataTable ID
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | Asesi -> asesi-table
        | Asesor -> asesor-table
        |
        */

        $modelTableId = "{$modelRoutePrefix}-table";

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        $title = Str::title(
            str_replace('-', ' ', $modelRoutePrefix)
        );

        /*
        |--------------------------------------------------------------------------
        | Replacements
        |--------------------------------------------------------------------------
        */

        $replacements = [
            '{{modelName}}'           => $name,
            '{{modelVariable}}'       => $modelVariable,
            '{{controllerNamespace}}' => $controllerNamespace,
            '{{modelViewFolder}}'     => $viewFolderKebab,
            '{{modelRoutePrefix}}'    => $modelRoutePrefix,
            '{{modelTableId}}'        => $modelTableId,
            '{{title}}'               => $title,
        ];

        $this->newLine();

        $this->info(
            "Memulai scaffolding CRUD Yajra untuk {$name}..."
        );

        /*
        |--------------------------------------------------------------------------
        | 3. Model & Migration
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Membuat Model & Migration...');

        $this->call('make:model', [
            'name' => $name,
            '-m'   => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. Generate Yajra DataTable
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->generateDataTable(
            $name,
            $replacements
        );

        /*
        |--------------------------------------------------------------------------
        | 5. Generate Controller
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->generateController(
            $name,
            $modulePath,
            $replacements
        );

        /*
        |--------------------------------------------------------------------------
        | 6. Generate Views
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->generateViews(
            $modulePath,
            $replacements
        );

        /*
        |--------------------------------------------------------------------------
        | 7. Generate Routes
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->generateRoutes(
            $modulePath,
            $replacements
        );

        /*
        |--------------------------------------------------------------------------
        | Done
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            "✨ CRUD Yajra {$name} berhasil dibuat!"
        );

        $this->line('');
        $this->line(
            "📁 Module   : app/Modules/{$name}"
        );

        $this->line(
            "📊 DataTable: app/DataTables/{$name}DataTable.php"
        );

        $this->line(
            "🗃 Model    : app/Models/{$name}.php"
        );

        $this->line(
            "🌐 Route    : {$modelRoutePrefix}"
        );

        $this->line(
            "📋 Table ID : {$modelTableId}"
        );

        $this->newLine();
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('optimize');
        return Command::SUCCESS;
    }

    /**
     * Generate Yajra DataTable Service Class.
     */
    protected function generateDataTable(
        string $name,
        array $replacements
    ): void {
        $dir = app_path('DataTables');

        if (!File::exists($dir)) {
            File::makeDirectory(
                $dir,
                0755,
                true
            );
        }

        $stubPath = base_path(
            'stubs/yajra/datatables.service.stub'
        );

        $stub = File::get($stubPath);

        $template = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );

        $file = "{$dir}/{$name}DataTable.php";

        if (File::exists($file)) {
            $this->warn(
                "⚠ DataTable sudah ada, dilewati: {$file}"
            );

            return;
        }

        File::put(
            $file,
            $template
        );

        $this->info(
            "✔ DataTable: {$file}"
        );
    }

    /**
     * Generate Controller.
     */
    protected function generateController(
        string $name,
        string $modulePath,
        array $replacements
    ): void {
        $dir = "{$modulePath}/Controllers";

        if (!File::exists($dir)) {
            File::makeDirectory(
                $dir,
                0755,
                true
            );
        }

        $stubPath = base_path(
            'stubs/yajra/controller.yajra.stub'
        );

        $stub = File::get($stubPath);

        $template = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );

        $file = "{$dir}/{$name}Controller.php";

        if (File::exists($file)) {
            $this->warn(
                "⚠ Controller sudah ada, dilewati: {$file}"
            );

            return;
        }

        File::put(
            $file,
            $template
        );

        $this->info(
            "✔ Controller: {$file}"
        );
    }

    /**
     * Generate Views.
     */
    protected function generateViews(
        string $modulePath,
        array $replacements
    ): void {
        $dir = "{$modulePath}/Views";

        if (!File::exists($dir)) {
            File::makeDirectory(
                $dir,
                0755,
                true
            );
        }

        $views = [
            'index.yajra.stub'  => 'index.blade.php',
            'create.yajra.stub' => 'create.blade.php',
            'edit.yajra.stub'   => 'edit.blade.php',
        ];

        foreach ($views as $stubName => $fileName) {

            $stubPath = base_path(
                "stubs/yajra/{$stubName}"
            );

            $stub = File::get($stubPath);

            $template = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $stub
            );

            $file = "{$dir}/{$fileName}";

            if (File::exists($file)) {
                $this->warn(
                    "⚠ View sudah ada, dilewati: {$file}"
                );

                continue;
            }

            File::put(
                $file,
                $template
            );

            $this->info(
                "✔ View: {$file}"
            );
        }
    }

    /**
     * Generate Routes.
     */
    protected function generateRoutes(
        string $modulePath,
        array $replacements
    ): void {
        $stubPath = base_path(
            'stubs/yajra/route.yajra.stub'
        );

        $stub = File::get($stubPath);

        $template = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );

        $file = "{$modulePath}/routes.php";

        if (File::exists($file)) {
            $this->warn(
                "⚠ Routes sudah ada, dilewati: {$file}"
            );

            return;
        }

        File::put(
            $file,
            $template
        );

        $this->info(
            "✔ Routes: {$file}"
        );
    }
}