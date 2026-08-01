<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudYajraCommand extends Command
{
    protected $signature = 'make:crud-yajra
                            {name : Nama Model (contoh: Product)}
                            {--columns= : Daftar kolom untuk DataTable & validasi, pisahkan dengan koma (contoh: id,name,email)}
                            {--no-menu : Jangan buat menu otomatis untuk super-admin}';

    protected $description = 'Otomatis membuat CRUD lengkap dengan Yajra DataTables dan struktur Modular';

    /**
     * Kolom bertipe _id dianggap foreign key -> rule: required, integer.
     * Kolom dengan nama ini dapat rule khusus di luar default string.
     * Ini heuristik best-effort karena migration BELUM di-run saat command
     * ini jalan, jadi tipe kolom sesungguhnya belum bisa dibaca dari DB.
     * Developer tetap perlu review ulang rules yang digenerate.
     */
    protected const COLUMN_TYPE_RULES = [
        'date'  => ['required', 'date'],
        'start' => ['required', 'date_format:H:i'],
        'end'   => ['required', 'date_format:H:i', 'after:start'],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $modelNamespace = $this->option('model-namespace') ?: 'Models';
        // FIX: sanitasi kolom dari --columns (trim, buang kosong, snake_case,
        // buang karakter selain huruf/angka/underscore) supaya tidak ada
        // input aneh yang lolos mentah ke dalam stub.
        $columnsOption = $this->option('columns');
        $columnsArray = $columnsOption
            ? $this->sanitizeColumns($columnsOption)
            : ['id', 'name'];

        if (empty($columnsArray)) {
            $this->error('Daftar kolom (--columns) tidak valid / kosong setelah sanitasi.');
            return Command::FAILURE;
        }

        $columnsString = implode("', '", $columnsArray); // untuk stub datatable, mis: ['id', 'name']
        $columnsListHtml = implode(', ', $columnsArray);  // untuk tampilan blade

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
            'model.yajra.stub', // ✅ Tambahkan ini
        ];

        foreach ($requiredStubs as $stub) {
            $path = base_path("stubs/yajra/{$stub}");

            if (!File::exists($path)) {
                $this->error("Stub tidak ditemukan: stubs/yajra/{$stub}");
                return Command::FAILURE;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Setup Module
        |--------------------------------------------------------------------------
        */

        $modulePath = app_path("Modules/{$name}");
        $controllerNamespace = "App\\Modules\\{$name}\\Controllers";
        $viewFolderKebab = Str::kebab($name);
        $modelVariable = Str::camel($name);
        $modelRoutePrefix = Str::kebab($name);
        $modelTableId = "{$modelRoutePrefix}-table";
        $title = Str::title(str_replace('-', ' ', $modelRoutePrefix));

        // FIX: bangun validation rules dari --columns, dipakai di controller.yajra.stub
        // untuk menggantikan rule hardcoded 'name' saja.
        $validationRules = $this->buildValidationRules($columnsArray);

        $replacements = [
            '{{modelName}}'           => $name,
            '{{modelVariable}}'       => $modelVariable,
            '{{controllerNamespace}}' => $controllerNamespace,
            '{{modelViewFolder}}'     => $viewFolderKebab,
            '{{modelRoutePrefix}}'    => $modelRoutePrefix,
            '{{modelTableId}}'        => $modelTableId,
            '{{title}}'               => $title,
            '{{columnsArray}}'        => $columnsString,
            '{{columnsList}}'         => $columnsListHtml,
            '{{validationRules}}'     => $validationRules,
            '{{namespace}}'           => $modelNamespace, // ✅ Tambahkan ini
            '{{tableName}}'           => $tableName,      // ✅ Tambahkan ini
        ];

        $this->newLine();
        $this->info("Memulai scaffolding CRUD Yajra untuk {$name}...");
        $this->info('Kolom yang dipakai: ' . implode(', ', $columnsArray));

        /*
        |--------------------------------------------------------------------------
        | 3. Model & Migration
        |--------------------------------------------------------------------------
        */
        $this->newLine();
        $this->info('Membuat Model & Migration...');
        // $this->call('make:model', [
        //     'name' => $name,
        //     '-m'   => true,
        // ]);
        $this->generateModelWithCustomNamespace($name, $modelNamespace, $replacements);
        // Generate migration terpisah
        $this->call('make:migration', [
            'name' => "create_{$this->getTableName($name)}_table",
            '--create' => $this->getTableName($name),
        ]);

    $this->warn("⚠ Model dibuat dengan namespace: {$modelNamespace}\\{$name}");
        // PERHATIAN: make:model default membuat model di Models\{$name}.
        // Jika project ini pakai namespace root "Models\" (tanpa App\), seperti
        // yang diasumsikan controller.yajra.stub, pastikan composer.json sudah
        // punya psr-4 mapping "Models\\": "Models/" DAN model di-generate ke
        // folder yang benar -- kalau tidak, controller hasil generate akan
        // "Class not found" saat dipanggil. Sesuaikan opsi make:model atau
        // pindahkan file model secara manual sesuai konvensi project.
        $this->warn("⚠ Cek namespace model {$name}: pastikan sesuai dengan 'use Models\\{$name};' di controller.");

        $this->newLine();
        $this->generateDataTable($name, $replacements);

        $this->newLine();
        $this->generateController($name, $modulePath, $replacements);

        $this->newLine();
        $this->generateViews($modulePath, $replacements);

        $this->newLine();
        $this->generateRoutes($modulePath, $replacements);

         // ============= TAMBAHKAN INI =============
        // Register menu untuk super-admin
        $this->newLine();
        $this->info('Mendaftarkan menu untuk super-admin...'); 
        if (!$this->option('no-menu')) {
            $this->newLine();
            $this->info('Mendaftarkan menu untuk super-admin...');
            $this->registerMenuForSuperAdmin($name, $replacements);
        }

        // =========================================
 
        $this->newLine();
        $this->info("✨ CRUD Yajra {$name} berhasil dibuat!");
        $this->line('');
        $this->line("📁 Module   : app/Modules/{$name}");
        $this->line("📊 DataTable: app/DataTables/{$name}DataTable.php");
        $this->line("🗃 Model    : Models/{$name}.php");
        $this->line("🌐 Route    : {$modelRoutePrefix}");
        $this->line("📋 Table ID : {$modelTableId}");
        $this->line('📌 Kolom    : ' . implode(', ', $columnsArray));

        $this->newLine();
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('optimize');

        return Command::SUCCESS;
    }

    /**
     * Bersihkan input --columns: trim, buang entri kosong, snake_case,
     * dan buang karakter selain huruf/angka/underscore.
     */
    protected function sanitizeColumns(string $columnsOption): array
    {
        $columns = array_map('trim', explode(',', $columnsOption));

        $columns = array_map(function ($col) {
            $col = Str::snake($col);
            return preg_replace('/[^a-z0-9_]/', '', $col);
        }, $columns);

        return array_values(array_filter($columns, fn($col) => $col !== ''));
    }

    /**
     * Generate potongan kode PHP untuk validation rules berdasarkan daftar
     * kolom. Kolom "id" dikecualikan (auto increment, tidak divalidasi dari
     * request). Heuristik sederhana per nama/suffix kolom; developer tetap
     * perlu review manual setelah migration final dibuat.
     */
    protected function buildValidationRules(array $columnsArray): string
    {
        $lines = [];

        foreach ($columnsArray as $column) {
            if ($column === 'id') continue;

            $rules = self::COLUMN_TYPE_RULES[$column]
                ?? (Str::endsWith($column, '_id')
                    ? ['required', 'integer']
                    : ['required', 'string', 'max:255']);

            $rulesExport = "['" . implode("', '", $rules) . "']";
            $lines[] = "            '{$column}' => {$rulesExport},";
        }

        return implode("\n", $lines);
    }

    /**
     * Generate Yajra DataTable Service Class.
     */
    protected function generateDataTable(string $name, array $replacements): void
    {
        $dir = app_path('DataTables');

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $stubPath = base_path('stubs/yajra/datatables.service.stub');
        $stub = File::get($stubPath);
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $file = "{$dir}/{$name}DataTable.php";

        if (File::exists($file)) {
            $this->warn("⚠ DataTable sudah ada, dilewati: {$file}");
            return;
        }

        File::put($file, $template);
        $this->info("✔ DataTable: {$file}");
    }

    /**
     * Generate Controller.
     */
    protected function generateController(string $name, string $modulePath, array $replacements): void
    {
        $dir = "{$modulePath}/Controllers";

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $stubPath = base_path('stubs/yajra/controller.yajra.stub');
        $stub = File::get($stubPath);
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $file = "{$dir}/{$name}Controller.php";

        if (File::exists($file)) {
            $this->warn("⚠ Controller sudah ada, dilewati: {$file}");
            return;
        }

        File::put($file, $template);
        $this->info("✔ Controller: {$file}");
    }

    /**
     * Generate Views.
     */
    protected function generateViews(string $modulePath, array $replacements): void
    {
        $dir = "{$modulePath}/Views";

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $views = [
            'index.yajra.stub'  => 'index.blade.php',
            'create.yajra.stub' => 'create.blade.php',
            'edit.yajra.stub'   => 'edit.blade.php',
        ];

        foreach ($views as $stubName => $fileName) {
            $stubPath = base_path("stubs/yajra/{$stubName}");
            $stub = File::get($stubPath);
            $template = str_replace(array_keys($replacements), array_values($replacements), $stub);

            $file = "{$dir}/{$fileName}";

            if (File::exists($file)) {
                $this->warn("⚠ View sudah ada, dilewati: {$file}");
                continue;
            }

            File::put($file, $template);
            $this->info("✔ View: {$file}");
        }
    }

    /**
     * Generate Routes.
     */
    protected function generateRoutes(string $modulePath, array $replacements): void
    {
        $stubPath = base_path('stubs/yajra/route.yajra.stub');
        $stub = File::get($stubPath);
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $file = "{$modulePath}/routes.php";

        if (File::exists($file)) {
            $this->warn("⚠ Routes sudah ada, dilewati: {$file}");
            return;
        }

        File::put($file, $template);
        $this->info("✔ Routes: {$file}");
    }

    /**
     * Register menu untuk module yang baru dibuat
     */
    protected function registerMenuForSuperAdmin(string $name, array $replacements): void
    {
        $superAdminRoleName = 'super-admin'; // Sesuaikan dengan role name Anda
        
        // Cek apakah model Role dan Menu tersedia
        if (!class_exists(\Models\Role::class) || !class_exists(\Models\Menu::class)) {
            $this->warn('⚠ Model Role atau Menu tidak ditemukan, lewati registrasi menu.');
            return;
        }

        $role = \Models\Role::where('name', $superAdminRoleName)->first();
        
        if (!$role) {
            $this->warn("⚠ Role '{$superAdminRoleName}' tidak ditemukan, lewati registrasi menu.");
            return;
        }

        // Data menu dari command
        $menuData = [
            'slug' => $replacements['{{modelRoutePrefix}}'],
            'parent_id' => null,
            'title' => $replacements['{{title}}'],
            'flyout_title' => null,
            'icon' => $this->getIconForModel($name), // Method untuk ikon
            'icon_type' => 'material',
            'route' => "{$replacements['{{modelRoutePrefix}}']}.index",
            'url' => $replacements['{{modelRoutePrefix}}'],
            'type' => 'sidebar',
            'sort_order' => (\Models\Menu::max('sort_order') ?? 0) + 1,
            'is_active' => 1,
        ];

        // Gunakan updateOrCreate agar tidak duplicate
        $menu = \Models\Menu::updateOrCreate(
            ['slug' => $menuData['slug']],
            $menuData
        );

        // Assign ke role super-admin
        \DB::table('menu_role')->updateOrInsert(
            ['menu_id' => $menu->id, 'role_id' => $role->id],
            [
                'is_view' => 1,
                'is_create' => 1,
                'is_edit' => 1,
                'is_delete' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->info("✔ Menu '{$menuData['title']}' terdaftar untuk role '{$superAdminRoleName}'");
    }

    /**
     * Mendapatkan ikon berdasarkan nama model
     */
    protected function getIconForModel(string $name): string
    {
        // Mapping ikon berdasarkan nama model
        $icons = [
            'Product' => 'inventory_2',
            'Category' => 'category',
            'User' => 'people',
            'Role' => 'badge',
            'Permission' => 'lock',
            'Setting' => 'settings',
            'Report' => 'assessment',
            'Order' => 'shopping_cart',
            'Payment' => 'payments',
            'Customer' => 'person',
            'Supplier' => 'local_shipping',
            'Employee' => 'groups',
        ];

        return $icons[$name] ?? 'list_alt';
    }

    /**
     * Generate model dengan namespace kustom
     */
    protected function generateModelWithCustomNamespace(string $name, string $namespace, array $replacement): void
    {
        $modelPath = app_path("Models/{$name}.php");
        
        // Buat folder jika belum ada
        if (!File::exists(app_path('Models'))) {
            File::makeDirectory(app_path('Models'), 0755, true);
        }
        
        // Gunakan stub custom atau buat manual
        $stub = $this->getModelStub($name, $namespace, $replacement);
        File::put($modelPath, $stub);
        
        $this->info("✔ Model: {$modelPath} dengan namespace {$namespace}");
    }

    /**
     * Generate stub model dengan namespace kustom
     */
    protected function getModelStub(string $name, string $namespace, array $replacement): string
    {
        $tableName = $this->getTableName($name);
        
        $stubPath = base_path('stubs/yajra/model.yajra.stub');
        $stub = File::get($stubPath);
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Get table name from model name
     */
    protected function getTableName(string $name): string
    {
        return Str::snake(Str::plural($name));
    }
}