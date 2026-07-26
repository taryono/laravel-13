<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudYajraCommand extends Command
{
    protected $signature = 'make:crud-yajra {name : Nama Model (contoh: Product)}';
    protected $description = 'Otomatis membuat CRUD komplit dengan struktur Modular 100% terisolasi';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        
        // Cek semua file stub yang dibutuhkan
        if (!file_exists(base_path('stubs/yajra/controller.yajra.stub')) || 
            !file_exists(base_path('stubs/yajra/index.yajra.stub')) ||
            !file_exists(base_path('stubs/yajra/route.yajra.stub'))) {
            $this->error('File stub tidak lengkap! Pastikan controller.yajra.stub, index.yajra.stub, dan route.yajra.stub sudah ada di folder stubs/.');
            return Command::FAILURE;
        }

        // Setup path lokasi folder Modul
        $modulePath = app_path("Modules/{$name}");
        $controllerNamespace = "App\Modules\\{$name}\Controllers";
        $viewFolderKebab = Str::kebab($name); // contoh: Product -> product

        $replacements = [
            '{{modelName}}'           => $name,
            '{{controllerNamespace}}' => $controllerNamespace,
            '{{modelVariable}}'       => Str::camel($name),
            '{{modelViewFolder}}'     => $viewFolderKebab,
            // '{{modelRoutePrefix}}'    => Str::kebab(Str::plural($name)),
            '{{modelRoutePrefix}}'    => Str::kebab(Str::single($name)),
            '{{modelTableId}}'        => Str::kebab(Str::plural($name)),
            '{{title}}'      => Str::kebab(Str::plural($name))
        ];

        $this->info("Memulai scaffolding Full Modular untuk {$name}...");

        // 1. Buat Model & Migration bawaan Laravel (Masih di folder default core)
        $this->call('make:model', ['name' => $name, '-m' => true]);

        // 2. Buat Yajra DataTable Service Class
        $this->call('datatables:make', ['name' => $name]);

        // 3. Buat Folder & File Controller di dalam modul
        $this->generateController($name, $modulePath, $replacements);

        // 4. Buat Folder & File Views di dalam modul
        $this->generateViews($modulePath, $replacements);

        // 5. Buat File routes.php di dalam modul
        $this->generateRoutes($modulePath, $replacements);

        $this->newLine();
        $this->info("✨ CRUD Modular {$name} sukses dibuat di App/Modules/{$name}!");
        $this->line("👉 Agar Laravel mendeteksi View dan Route dari modul ini, pastikan Anda mendaftarkannya.");
        
        return Command::SUCCESS;
    }

    protected function generateController($name, $modulePath, $replacements)
    {
        $dir = "{$modulePath}/Controllers";
        if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);

        $stub = File::get(base_path('stubs/yajra/controller.yajra.stub'));
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        File::put("{$dir}/{$name}Controller.php", $template);
        $this->info("✔ Controller: {$dir}/{$name}Controller.php");
    }

    protected function generateViews($modulePath, $replacements)
    {
        $dir = "{$modulePath}/Views";
        if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);

        // Generate index.blade.php
        $stubIndex = File::get(base_path('stubs/yajra/index.yajra.stub'));
        $templateIndex = str_replace(array_keys($replacements), array_values($replacements), $stubIndex);
        File::put("{$dir}/index.blade.php", $templateIndex); 

        // Generate index.blade.php
        $stubCreate = File::get(base_path('stubs/yajra/create.yajra.stub'));
        $templateCreate = str_replace(array_keys($replacements), array_values($replacements), $stubCreate);
        File::put("{$dir}/create.blade.php", $templateCreate); 

        // Generate index.blade.php
        $stubEdit = File::get(base_path('stubs/yajra/edit.yajra.stub'));
        $templateEdit = str_replace(array_keys($replacements), array_values($replacements), $stubEdit);
        File::put("{$dir}/edit.blade.php", $templateEdit);

        $this->info("✔ Views: Berhasil dibuat di {$dir}/");
    }

    protected function generateRoutes($modulePath, $replacements)
    {
        $stub = File::get(base_path('stubs/yajra/route.yajra.stub'));
        $template = str_replace(array_keys($replacements), array_values($replacements), $stub);
        
        File::put("{$modulePath}/routes.php", $template);
        $this->info("✔ Routes: {$modulePath}/routes.php");
    }
}
