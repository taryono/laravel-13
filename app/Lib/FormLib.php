<?php

namespace Lib;

use Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use View;

class FormLib
{
    protected const TARGET_AREA = [
        "country_id"     => "region_id",
        "region_id"      => "city_id",
        "city_id"        => "district_id",
        "district_id"    => "subdistrict_id",
        "subdistrict_id" => null,
    ];

    public $model;
    public $object;
    public $relations = [];
    public $names = [];
    public $forms = [];
    public $groupedForms = []; // FIX: dulu field ber-group dibuang, sekarang disimpan di sini
    public $comment;
    public $required;
    public $excludes = [];
    public $array_data;
    public $classes;
    public $default;
    public $attributes;
    public $default_class;
    public $custom_fields = [];
    public $groups = [];
    public $set_fields = [];

    protected SchemaColumnReader $columnReader;
    protected RelationDataResolver $relationResolver;

    public function __construct(
        $model = null,
        $object = null,
        array $custom_fields = [],
        ?SchemaColumnReader $columnReader = null,
        ?RelationDataResolver $relationResolver = null
    ) {
        $this->model = $model;
        $this->object = $object ?? $model;
        $this->custom_fields = $custom_fields;

        // Dependency injection dengan default, supaya class ini mudah dites
        // (tinggal inject mock/fake dari luar) tanpa mengubah cara pemakaian lama.
        $this->columnReader = $columnReader ?? new SchemaColumnReader();
        $this->relationResolver = $relationResolver ?? new RelationDataResolver();

        View::share('object', $this->object);
    }

    /**
     * Proses utama untuk menghasilkan field form.
     */
    public function getFields(): self
    {
        try {
            if (!$this->model) return $this;

            $all_fields = $this->columnReader->getFilteredFields($this->model);
            if (empty($all_fields)) return $this;

            $columns = $this->columnReader->getColumns($this->model);
            $darkModeClass = getkDarkMode() ? "bg-dark text-white" : 'bg-light text-black';
            $group_fields = [];

            $relationData = $this->relationResolver->preloadAll($all_fields, $this->model);

            foreach ($all_fields as $field) {
                if ($this->shouldSkipField($field)) continue;

                if (Str::endsWith($field, "_id")) {
                    $this->processRelationField($field, $columns, $relationData, $darkModeClass);
                    continue;
                }

                $this->processRegularField($field, $columns, $darkModeClass, $group_fields);
                if($field == "password"){
                    // add password confirmation field
                    $this->processRegularField("password_confirmation", $columns, $darkModeClass, $group_fields);
                }
            }

            $this->forms = array_filter($this->forms);

            // FIX: sebelumnya $group_fields (diisi processRegularField via reference
            // saat field masuk daftar $this->groups) tidak pernah disimpan ke mana pun,
            // sehingga field yang dikelompokkan hilang total dari hasil akhir.
            $this->groupedForms = array_filter($group_fields);

            return $this;

        } catch (\Throwable $e) {
            // FIX: sebelumnya error di production tidak pernah di-log sama sekali.
            report($e);

            if (app()->isLocal()) dd($e->getMessage(), $e->getLine());

            abort(response()->json([
                'status' => 'error',
                'message' => say("Sepertinya ada kesalahan sistem"),
            ], 400));
        }
    }

    protected function processRelationField(string $field, $columns, array $relationData, string $darkMode): void
    {
        $name = str_replace("_id", "", $field);
        $label = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : $name;
        $comment = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : '';
        $modelClass = $this->relationResolver->resolveModelClass($name);

        if (!class_exists($modelClass)) return;

        $relationType = $this->relationResolver->getRelationType($this->model, $name);
        $formName = $field;
        $multiple = [];

        if ($relationType === "hasMany") {
            $formName .= "[]";
            $multiple = ['multiple' => true];
        }

        // Sesuai perilaku asli: relasi belongsTo dilewati (di-handle di tempat lain).
        if ($relationType === "belongsTo") return;

        $this->names[$field] = $relationData[$field] ?? [];
        $default = $this->object?->{$field};

        if (array_key_exists($field, self::TARGET_AREA)) {
            $this->forms[$field] = $this->buildAreaSelect($field, $label, $comment, $relationData, $default, $multiple);
            return;
        }

        $this->forms[$field] = [
            'label' => ucfirst(say($label)),
            'content' => Form::select(
                $formName,
                collect(['' => say('-- Pilih --')])->merge($relationData[$field] ?? []),
                $default,
                $this->cleanAttributes([
                    'class' => "form-control selectpicker required {$darkMode}",
                    'id' => $field,
                    'data-title' => "-- Pilih " . ucfirst(say($label)) . "-- ",
                    'data-live-search' => 'true',
                    'required' => 'required',
                    'data-width' => '100%',
                ] + $multiple)
            ),
            'comment' => $comment,
        ];
    }

    /**
     * Bangun select untuk field wilayah berjenjang (country/region/city/district).
     *
     * FIX BUG: sebelumnya untuk city_id/district_id, opsi select selalu dikosongkan
     * (mengandalkan AJAX cascading), sehingga saat form EDIT dibuka, value yang
     * tersimpan tidak muncul di opsi -> dropdown terlihat kosong/salah meski data
     * ada di database. Sekarang jika ada default value tersimpan, kita pastikan
     * opsi tersebut tetap disediakan (walau daftar lengkap tetap dimuat via AJAX).
     */
    protected function buildAreaSelect(string $field, string $label, string $comment, array $relationData, $default, array $multiple): array
    {
        $isEagerLoaded = in_array($field, ["country_id", "region_id"]);
        $selectData = $isEagerLoaded ? ($relationData[$field] ?? []) : [];

        // Jika field belum di-eager-load tapi ada default value tersimpan (mode edit),
        // ambil minimal SATU opsi (value tersimpan) supaya tidak hilang dari tampilan.
        if (!$isEagerLoaded && $default && isset($relationData[$field][$default])) {
            $selectData = [$default => $relationData[$field][$default]];
        }

        $target = self::TARGET_AREA[$field];

        return [
            'label' => ucfirst(say($label)),
            'content' => Form::select(
                $field,
                collect(['' => say('-- Pilih --')])->merge($selectData),
                $default,
                $this->cleanAttributes([
                    'class' => 'selectpicker form-select form-control text-p-black font-weight-600 p-shadow-3 area',
                    'id' => $field,
                    'model' => $target ? str_replace("_id", "", $target) : null,
                    'data-target' => $target ? "#{$target}" : null,
                    'data-url' => '/filterArea',
                ] + $multiple)
            ),
            'comment' => $comment,
        ];
    }

    protected function processRegularField(string $field, $columns, string $darkMode, array &$group_fields): void
    {
        if($field === "password_confirmation"){ 
            $required = null;
            $label = "Password Confirmation";
            $comment = '';
            $originType = "text";
            $type = "password";
        }elseif($field === "password"){
            $required = null;
            $label = "Password";
            $comment = '';
            $originType = "text";
            $type = "password";
        }else{
            $required = $columns->{$field}->is_null === 'NO' ? "required" : null;
            $label = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : $field;
            $comment = !empty($columns->{$field}->comment) ? $columns->{$field}->comment : '';
            $originType = Schema::getColumnType($this->model->getTable(), $field);
            $type = Config::get("data_types.{$originType}", 'text');
        }
        

        // FIX: sebelumnya $required (bisa null) dimasukkan sebagai elemen array
        // numerik mentah ("$required" langsung, tanpa key), yang berisiko
        // menghasilkan attribute HTML aneh tergantung implementasi Form::.
        // Sekarang pakai key eksplisit lalu dibersihkan lewat cleanAttributes().
        $attributes = $this->cleanAttributes([
            'class' => "form-control font-size-100 {$field}",
            'id' => $field,
            'rows' => 3,
            'cols' => 10,
            'required' => $required,
        ]);

        if (in_array($type, ['text', 'textarea']) &&  !in_array($field, ['password', 'password_confirmation'])) {
            $attributes['class'] .= ' comment';
        }
        $value = null;
        if( !in_array($field, ['password', 'password_confirmation'])){
            $value = $this->object?->{$field};
        }
        $customRender = renderFields()[$field] ?? null;

        if (isset($this->set_fields[$field])) {
            $content = $this->set_fields[$field];
        } elseif ($customRender) {
            $content = Blade::render($customRender);
        } elseif (is_array($value)) {
            $content = Form::select($field, ['' => say('-- Pilih --')], $value, $this->cleanAttributes([
                'class' => "form-control selectpicker required {$darkMode}",
                'id' => $field,
                'data-title' => "-- Pilih " . ucfirst(say($label)) . "-- ",
                'data-live-search' => 'true',
                'required' => 'required',
                'data-width' => '100%',
                'multiple' => 'multiple',
            ]));
        } else {
            $content = in_array($field, ['password', 'password_confirmation'])
            ? Form::password($field, $attributes)
            : Form::{$type}($field, $value, $attributes);
        }

        $fieldData = [
            'label' => ucfirst(say($label)),
            'content' => $content,
            'comment' => $comment,
        ];

        if (!empty($this->groups) && is_array($this->groups) && in_array($field, $this->groups)) {
            $group_fields[$field] = $fieldData;
        } else {
            $this->forms[$field] = $fieldData;
        }
    }

    /**
     * Hilangkan attribute bernilai null supaya tidak lolos ke helper Form::,
     * yang bisa berperilaku tidak terduga terhadap elemen array ber-value null.
     */
    protected function cleanAttributes(array $attributes): array
    {
        return array_filter($attributes, fn($value) => $value !== null);
    }

    protected function shouldSkipField(string $field): bool
    {
        if ($field === "id") return true;
        if (!empty($this->custom_fields) && !in_array($field, $this->custom_fields)) return true;
        if (!empty($this->excludes) && in_array($field, $this->excludes)) return true;

        return false;
    }

    /**
     * Ambil info kolom tabel (delegasi ke SchemaColumnReader, dipertahankan
     * di sini agar signature publik lama tetap kompatibel).
     */
    public function getColums($table = null)
    {
        return $this->columnReader->getColumns($this->model, $table);
    }

    public function getRelationType(string $method): string
    {
        return $this->relationResolver->getRelationType($this->model, $method);
    }

    public function updateComment(string $table, string $field, string $comment): void
    {
        $this->columnReader->updateComment($table, $field, $comment);
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
    public function getGroupedForms() { return $this->groupedForms; }
    public function setGroupedForms($groupedForms): self { $this->groupedForms = $groupedForms; return $this; }
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