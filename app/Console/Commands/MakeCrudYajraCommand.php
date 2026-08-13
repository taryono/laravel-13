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
                            {--namespace=Models : Namespace kustom untuk Model, mis. Models atau App\\Models}
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

        // FIX: sebelumnya memanggil $this->option('model-namespace') yang
        // tidak terdaftar di $signature -> InvalidArgumentException.
        // Sekarang pakai opsi --namespace yang sudah didaftarkan, default 'Models'.
        $modelNamespace = $this->option('namespace') ?: 'Models';

        // FIX: sanitasi kolom dari --columns (trim, buang kosong, snake_case,
        // buang karakter selain huruf/angka/underscore) supaya tidak ada
        // input aneh yang lolos mentah ke dalam stub.
        //
        // Kolom "id" SENGAJA tidak pernah dimasukkan ke daftar kolom
        // tampilan -- baik saat default maupun saat diminta eksplisit lewat
        // --columns=id,name,... -- karena nomor urut baris sudah disediakan
        // oleh kolom "No" (DT_RowIndex) di getColumns(). Penyaringannya
        // dilakukan di dalam sanitizeColumns() supaya berlaku konsisten di
        // kedua jalur (default & eksplisit) tanpa duplikasi logic.
        $columnsOption = $this->option('columns');
        $columnsArray = $columnsOption
            ? $this->sanitizeColumns($columnsOption)
            : ['name'];

        if (empty($columnsArray)) {
            $this->error('Daftar kolom (--columns) tidak valid / kosong setelah sanitasi (kolom "id" selalu diabaikan karena sudah diwakili kolom "No").');
            return Command::FAILURE;
        }

        // FIX: sebelumnya $columnsString dibangun tanpa kurung siku/kutip
        // pembuka-penutup (mis. "id', 'name"), yang hanya valid jika stub
        // membungkusnya manual dengan explode() dari string literal --
        // pola yang sama rapuhnya dengan bug yang sudah diperbaiki di
        // UserDataTable.php. Sekarang kita generate array literal PHP yang
        // lengkap dan valid, supaya stub tinggal assign langsung ke
        // property: `protected array $columns = {{columnsArray}};`
        $columnsPhpArray = "['" . implode("', '", $columnsArray) . "']";
        $columnsListHtml = implode(', ', $columnsArray); // untuk tampilan blade

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
            'model.yajra.stub',
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
        $routePrefix = Str::snake($name);
        $modelTableId = "{$modelRoutePrefix}-table";
        $title = Str::title(str_replace('-', ' ', $modelRoutePrefix));

        // FIX: $tableName sebelumnya dipakai di $replacements tanpa pernah
        // didefinisikan -> Undefined variable $tableName (fatal).
        $tableName = $this->getTableName($name);

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
            '{{columnsArray}}'        => $columnsPhpArray,
            '{{columnsList}}'         => $columnsListHtml,
            '{{validationRules}}'     => $validationRules,
            '{{namespace}}'           => $modelNamespace,
            '{{tableName}}'           => $tableName,
            '{{routePrefix}}'         => $routePrefix,
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
        $this->generateModelWithCustomNamespace($name, $modelNamespace, $replacements);

        // Generate migration terpisah
        $this->call('make:migration', [
            'name' => "create_{$tableName}_table",
            '--create' => $tableName,
        ]);

        $this->warn("⚠ Model dibuat dengan namespace: {$modelNamespace}\\{$name}");
        // PERHATIAN: pastikan composer.json sudah punya psr-4 mapping yang
        // sesuai untuk namespace ini (mis. "Models\\": "Models/" jika pakai
        // namespace root "Models\", atau default "App\\": "app/" jika pakai
        // "App\Models"). Kalau tidak sesuai, controller hasil generate akan
        // "Class not found" saat dipanggil.
        $this->warn("⚠ Cek namespace model {$name}: pastikan sesuai dengan 'use {$modelNamespace}\\{$name};' di controller.");

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
        $this->line("🗃 Model    : {$modelNamespace}\\{$name}");
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
     * buang karakter selain huruf/angka/underscore, dan buang kolom "id"
     * (nomor urut baris sudah disediakan lewat kolom "No" / DT_RowIndex,
     * jadi "id" tidak pernah perlu tampil sebagai kolom sendiri -- baik
     * dari default maupun saat diminta eksplisit lewat --columns=id,...).
     */
    protected function sanitizeColumns(string $columnsOption): array
    {
        $columns = array_map('trim', explode(',', $columnsOption));

        $columns = array_map(function ($col) {
            $col = Str::snake($col);
            return preg_replace('/[^a-z0-9_]/', '', $col);
        }, $columns);

        $columns = array_filter($columns, fn($col) => $col !== '' && $col !== 'id');

        return array_values($columns);
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
            'icon' => $this->getIconForModel($name),
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
     * Generate model dengan namespace kustom.
     *
     * FIX: sebelumnya file model SELALU ditulis ke app_path("Models/...")
     * (yaitu app/Models/), padahal warning di handle() secara eksplisit
     * mengasumsikan namespace root "Models\" (tanpa "App\") yang mestinya
     * dipetakan psr-4 ke folder "Models/" di ROOT project, bukan di dalam
     * app/. Sekarang path file ditentukan dari namespace-nya sendiri lewat
     * resolveModelPath(), supaya konsisten dengan opsi --namespace.
     */
    protected function generateModelWithCustomNamespace(string $name, string $namespace, array $replacements): void
    {
        $modelPath = $this->resolveModelPath($namespace, $name);
        $modelDir = dirname($modelPath);

        if (!File::exists($modelDir)) {
            File::makeDirectory($modelDir, 0755, true);
        }

        $stub = $this->getModelStub($replacements);
        File::put($modelPath, $stub);

        $this->info("✔ Model: {$modelPath} dengan namespace {$namespace}");
    }

    /**
     * Tentukan path file model dari namespace-nya.
     *
     * - Jika namespace diawali "App\", dianggap mengikuti struktur standar
     *   Laravel -> dipetakan ke app/<sisa-namespace>/.
     * - Selain itu, diasumsikan psr-4 mapping root-level (mis. namespace
     *   "Models" -> folder "Models/" di root project), sesuai warning yang
     *   ditampilkan ke user di handle().
     *
     * Catatan: ini heuristik. Kalau project punya psr-4 mapping custom di
     * luar dua pola di atas, sesuaikan method ini atau pindahkan file model
     * secara manual setelah generate.
     */
    protected function resolveModelPath(string $namespace, string $name): string
    {
        if (Str::startsWith($namespace, 'App\\')) {
            $relative = Str::after($namespace, 'App\\');
            $relativePath = $relative !== '' ? str_replace('\\', '/', $relative) . '/' : '';
            return app_path("{$relativePath}{$name}.php");
        }

        $relativePath = str_replace('\\', '/', $namespace);
        return base_path("{$relativePath}/{$name}.php");
    }

    /**
     * Generate stub model dengan namespace kustom.
     *
     * FIX: sebelumnya method ini:
     * 1. Menerima parameter $replacement (singular) tapi memakai variabel
     *    $replacements (plural, tidak pernah didefinisikan di scope ini)
     *    -> Undefined variable.
     * 2. Tidak punya `return`, sehingga selalu balikin null dan file model
     *    yang dihasilkan kosong.
     * Kedua bug ini sudah diperbaiki di bawah.
     */
    protected function getModelStub(array $replacements): string
    {
        $stubPath = base_path('stubs/yajra/model.yajra.stub');
        $stub = File::get($stubPath);

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Get table name from model name
     */
    protected function getTableName(string $name): string
    {
        return Str::snake(Str::plural($name));
    }
}