<?php

namespace Lib;

use Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use View;

class FormLib
{
    // Konstanta agar mudah diubah & terpusat
    protected const CACHE_PREFIX = 'formlib_';
    protected const CACHE_TTL = 3600; // 1 jam
    protected const DEFAULT_EXCEPTIONS = ['remember_token', 'created_at', 'updated_at', 'deleted_at'];
    protected const FIELD_AREA = ['country_id', 'region_id', 'city_id', 'district_id'];
    protected const TARGET_AREA = [
        "country_id" => "region_id",
        "region_id" => "city_id",
        "city_id" => "district_id",
        "district_id" => "subdistrict_id",
        "subdistrict_id" => null
    ];

    // Properti dengan tipe jelas
    public $model;
    public $object;
    public $relations = [];
    public $names = [];
    public $forms = [];
    public $comment;
    public $required;
    public $excludes = [];
    public $array_data;
    public $classes;
    public $default;
    public $attributes; // Perbaiki typo: attributtes → attributes
    public $default_class;
    public $custom_fields = [];
    public $groups = [];
    public $set_fields = [];

    public function __construct($model = null, $object = null, array $custom_fields = [])
    {
        $this->model = $model;
        $this->object = $object ?? $model;
        $this->custom_fields = $custom_fields;
        
        View::share('object', $this->object);
    }

    /**
     * Proses utama untuk menghasilkan field form
     */
    public function getFields(): self
    {
        try {
            
            if (!$this->model) return $this;

            // Ambil daftar field dengan cache
            $all_fields = $this->getFilteredFields();
            if (empty($all_fields)) return $this;
            
            // Ambil info kolom SEKALI saja (hindari query berulang)
            $columns = $this->getColums();
            $darkModeClass = getkDarkMode() ? "bg-dark text-white" : 'bg-light text-black';
            $group_fields = [];

            // Ambil SEMUA data relasi SEKALI saja (HILANGKAN N+1)
            $relationData = $this->preloadAllRelationData($all_fields);

            foreach ($all_fields as $field) {
                // Skip field yang tidak perlu
                if ($this->shouldSkipField($field)) continue;

                // Proses field relasi (berakhiran _id)
                if (Str::endsWith($field, "_id")) {
                    $this->processRelationField(
                        $field, 
                        $columns, 
                        $relationData, 
                        $darkModeClass
                    );
                    continue;
                }

                // Proses field biasa
                $this->processRegularField(
                    $field, 
                    $columns, 
                    $darkModeClass, 
                    $group_fields
                );
            }

            // Gabungkan hasil
            $this->forms = array_filter($this->forms) + ['group_fields' => array_filter($group_fields)];
            
            return $this;

        } catch (\Throwable $e) {
            if (app()->isLocal()) dd($e->getMessage(), $e->getLine());
            abort(response()->json([
                'status' => 'error',
                'message' => say("Sepertinya ada kesalahan sistem")
            ], 400));
        }
    }

    /**
     * Ambil daftar field yang sudah difilter dengan cache
     */
    protected function getFilteredFields(): array
    {
        $cacheKey = self::CACHE_PREFIX . 'fields_' . $this->model->getTable();
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            try {
                $fields = $this->model->getConnection()
                    ->getSchemaBuilder()
                    ->getColumnListing($this->model->getTable());

                return array_diff($fields, self::DEFAULT_EXCEPTIONS);
            } catch (\Throwable $e) {
                report($e);
                return [];
            }
        });
    }

    /**
     * Ambil SEMUA data relasi sekaligus (SOLUSI N+1)
     */
    protected function preloadAllRelationData(array $fields): array
    {
        $data = [];
        
        foreach ($fields as $field) {
            if (!Str::endsWith($field, "_id")) continue;
            if (in_array($field, ["parent_id", "node_id", "object_id"])) continue;

            $name = str_replace("_id", "", $field);
            $modelClass = $this->resolveModelClass($name);
            
            if (!class_exists($modelClass)) continue;

            // Cache setiap data relasi
            $cacheKey = self::CACHE_PREFIX . 'rel_' . Str::snake(class_basename($modelClass));
            
            $data[$field] = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($modelClass, $field, $name) {
                $model = new $modelClass;
                
                // Logika khusus model
                if ($model->getTable() == "statuses") {
                    return $model->where('group', Str::singular($this->model->getTable()))
                                ->pluck('name', 'id')->toArray();
                }
                
                if ($field == "role_id") {
                    return $model->where('id', '!=', 1)->pluck('name', 'id')->toArray();
                }

                return $model->pluck('name', 'id')->toArray();
            });
        }

        return $data;
    }

    /**
     * Proses field relasi
     */
    protected function processRelationField(string $field, $columns, array $relationData, string $darkMode): void
{
    $name = str_replace("_id", "", $field);
    $label = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : $name;
    $modelClass = $this->resolveModelClass($name);

    if (!class_exists($modelClass)) return;

    $relationType = $this->getRelationType($name);
    $formName = $field;
    $multiple = [];

    if ($relationType === "hasMany") {
        $formName .= "[]";
        $multiple = ['multiple' => true];
    }

    // === PENTING: Sesuai kode asli, jika belongsTo langsung lewati ===
    if ($relationType === "belongsTo") return;

    $this->names[$field] = $relationData[$field] ?? [];
    $default = $this->object?->{$field};

    if (array_key_exists($field, self::TARGET_AREA)) {
        // Jika ingin semua field area langsung punya data, hapus kondisi in_array dibawah
        $selectData = in_array($field, ["country_id", "region_id"]) ? ($relationData[$field] ?? []) : [];
        
        $this->forms[$field] = [
            'label' => ucfirst(say($label)),
            'content' => Form::select($formName, 
                collect(['' => say('-- Pilih --')])->merge($selectData ?? []),
                $default, 
                [
                    'class' => 'selectpicker form-select form-control text-p-black font-weight-600 p-shadow-3 area',
                    'id' => $field,
                    'model' => self::TARGET_AREA[$field] ? str_replace("_id", "", self::TARGET_AREA[$field]) : null,
                    'data-target' => self::TARGET_AREA[$field] ? "#" . self::TARGET_AREA[$field] : null,
                    'data-url' => '/filterArea',
                ] + $multiple)
        ];
        return;
    }

    $this->forms[$field] = [
        'label' => ucfirst(say($label)),
        'content' => Form::select($formName, 
            collect(['' => say('-- Pilih --')])->merge($relationData[$field] ?? []),
            $default, 
            [
                'class' => "form-control selectpicker required {$darkMode}",
                'id' => $field,
                'data-title' => "-- Pilih " . ucfirst(say($label)) . "-- ",
                'data-live-search' => 'true',
                'required',
                'data-width' => '100%',
            ] + $multiple)
    ];
}

protected function processRegularField(string $field, $columns, string $darkMode, array &$group_fields): void
{
    $required = $columns->{$field}->is_null === 'NO' ? "required" : null;
    $label = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : $field;
    
    $originType = Schema::getColumnType($this->model->getTable(), $field);
    $type = Config::get("data_types.{$originType}", 'text');
    
    $attributes = [
        'class' => "form-control font-size-100 {$field}",
        'id' => $field,
        'rows' => 3,
        'cols' => 10,
        $required
    ];

    if (in_array($type, ['text', 'textarea'])) {
        $attributes['class'] .= ' comment';
    }

    $value = $this->object?->{$field};
    $customRender = renderFields()[$field] ?? null;

    // Ambil konten
    if (isset($this->set_fields[$field])) {
        $content = $this->set_fields[$field];
    } elseif ($customRender) {
        $content = Blade::render($customRender);
    } elseif (is_array($value)) {
        $content = Form::select($field, ['' => say('-- Pilih --')], $value, [
            'class' => "form-control selectpicker required {$darkMode}",
            'id' => $field,
            'data-title' => "-- Pilih " . ucfirst(say($label)) . "-- ",
            'data-live-search' => 'true',
            'required',
            'data-width' => '100%',
            'multiple'
        ]);
    } else {
        $content = Form::{$type}($field, $value, $attributes);
    }

    // === LOGIKA PENYIMPANAN KEMBALI SEPERTI ASLI ===
    if (!empty($this->groups) && is_array($this->groups) && in_array($field, $this->groups)) {
        $group_fields[$field] = [
            'label' => ucfirst(say($label)),
            'content' => $content
        ];
    } else {
        $this->forms[$field] = [
            'label' => ucfirst(say($label)),
            'content' => $content
        ];
    }
}

    /**
     * Cek apakah field harus dilewati
     */
    protected function shouldSkipField(string $field): bool
    {
        if ($field === "id") return true;
        if (!empty($this->custom_fields) && !in_array($field, $this->custom_fields)) return true;
        if (!empty($this->excludes) && in_array($field, $this->excludes)) return true;
        
        return false;
    }

    /**
     * Tentukan namespace model secara otomatis
     */
    protected function resolveModelClass(string $name): string
    {
        $studly = ucwords(Str::camel($name));
        return $name === "user" 
            ? "\App\Models\\{$studly}" 
            : "\Models\\{$studly}";
    }

    /**
     * Ambil info kolom tabel dengan cache
     */
    public function getColums($table = null)
    {
        $table ??= $this->model->getTable();
        $cacheKey = self::CACHE_PREFIX . 'cols_' . $table;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($table) {
            $db = $this->model->getConnection()->getConfig()['database'];
            $columns = [];

            $results = DB::select("SHOW FULL COLUMNS FROM {$db}.{$table}");

            foreach ($results as $col) {
                if (in_array($col->Field, self::DEFAULT_EXCEPTIONS)) continue;
                
                $columns[$col->Field] = (object)[
                    "type" => $col->Type,
                    "is_null" => $col->Null,
                    "key" => $col->Key,
                    "default" => $col->Default,
                    "extra" => $col->Extra,
                    "privileges" => $col->Privileges,
                    "comment" => str_replace("\r\n", " ", $col->Comment),
                ];
            }

            return (object)$columns;
        });
    }

    /**
     * Tentukan tipe relasi model
     */
    public function getRelationType(string $method): string
    {
        $relation = $this->model->{$method}();
        $class = get_class($relation);
        
        return str_replace("Illuminate\Database\Eloquent\Relations\\", "", $class);
    }

    /**
     * Update komentar kolom
     */
    public function updateComment(string $table, string $field, string $comment): void
    {
        Schema::table($table, function (Blueprint $table) use ($field, $comment) {
            $table->string($field, 255)->comment($comment)->change();
        });

        // Hapus cache setelah update
        Cache::forget(self::CACHE_PREFIX . 'cols_' . $table);
    }

    // --- Getter & Setter (dipertahankan agar kompatibel dengan kode lama) ---
    public function getModel() { return $this->model; }
    public function setModel($model): self { $this->model = $model; return $this; }
    public function getEloquent() { return $this->model?->getRelations() ?? []; }
    public function getRelations() { return $this->relations; }
    public function setRelations($relations): self { $this->relations = $relations; return $this; }
    public function getNames() { return $this->names; }
    public function setNames($names): self { $this->names = $names; return $this; }
    public function getForms() { return $this->forms; }
    public function setForms($forms): self { $this->forms = $forms; return $this; }
    public function getComment() { return $this->comment; }
    public function setComment($comment): self { $this->comment = $comment; return $this; }
    public function getRequired() { return $this->required; }
    public function setRequired($required): self { $this->required = $required; return $this; }
    public function getArrayData() { return $this->array_data; }
    public function setArrayData($array_data): self { $this->array_data = $array_data; return $this; }
    public function getClasses() { return $this->classes; }
    public function setClasses($classes): self { $this->classes = $classes; return $this; }
    public function getDefault() { return $this->default; }
    public function setDefault($default): self { $this->default = $default; return $this; }
    public function getAttributes() { return $this->attributes; }
    public function setAttributes($attributes): self { $this->attributes = $attributes; return $this; }
    public function getDefaultClass() { return $this->default_class; }
    public function setDefaultClass($default_class): self { $this->default_class = $default_class; return $this; }
    public function getCustomFields() { return $this->custom_fields; }
    public function setCustomFields($custom_fields): self { $this->custom_fields = $custom_fields; return $this; }
    public function getGroups() { return $this->groups; }
    public function setGroups($groups): self { $this->groups = $groups; return $this; }
    public function getSetFields() { return $this->set_fields; }
    public function setSetFields($set_fields): self { $this->set_fields = $set_fields; return $this; }
    public function getExcludes() { return $this->excludes; }
    public function setExcludes($excludes): self { $this->excludes = $excludes; return $this; }
}