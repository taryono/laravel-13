<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class GlobalSearch
{
    /**
     * Tipe kolom yang diperlakukan sebagai integer.
     */
    protected static array $integerTypes = [
        'int',
        'integer',
        'tinyint',
        'smallint',
        'mediumint',
        'bigint',
    ];

    /**
     * Tipe kolom numerik.
     */
    protected static array $decimalTypes = [
        'decimal',
        'numeric',
        'float',
        'double',
        'real',
    ];

    /**
     * Tipe kolom tanggal.
     */
    protected static array $dateTypes = [
        'date',
        'datetime',
        'timestamp',
    ];

    /**
     * Search DataTables global.
     *
     * Contoh:
     *
     * GlobalSearch::apply(
     *     $query,
     *     [
     *         'code',
     *         'name',
     *         'description',
     *     ],
     *     [
     *         'parent' => [
     *             'name',
     *             'code',
     *         ],
     *     ]
     * );
     */
    public static function apply(
        Builder $query,
        array $fields = [],
        array $relations = []
    ): Builder {
        $search = trim(
            (string) request()->input('search.value', '')
        );

        if ($search === '') {
            return $query;
        }

        $model = $query->getModel();

        $query->where(function (Builder $builder) use (
            $model,
            $fields,
            $relations,
            $search
        ) {

            /*
             * ==========================================
             * LOCAL MODEL FIELDS
             * ==========================================
             */
            self::applyLocalFields(
                $builder,
                $model,
                $fields,
                $search
            );

            /*
             * ==========================================
             * RELATIONS
             * ==========================================
             */
            foreach ($relations as $relation => $relationFields) {

                self::applyRelationSearch(
                    $builder,
                    $model,
                    $relation,
                    $relationFields,
                    $search
                );
            }
        });

        return $query;
    }

    /**
     * Alias agar backward compatible
     * dengan code lama:
     *
     * GlobalSearch::search(...)
     */
    public static function search(
        Builder $query,
        array $relations = [],
        array $searchableFields = []
    ): Builder {
        return self::apply(
            $query,
            $searchableFields,
            self::normalizeRelations($relations)
        );
    }

    /**
     * Normalisasi konfigurasi relation.
     *
     * Support:
     *
     * ['parent']
     *
     * dan:
     *
     * [
     *     'parent' => ['name', 'code']
     * ]
     */
    protected static function normalizeRelations(
        array $relations
    ): array {
        $result = [];

        foreach ($relations as $key => $value) {

            if (is_int($key)) {
                $result[$value] = [];
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Search field pada model utama.
     */
    protected static function applyLocalFields(
        Builder $query,
        Model $model,
        array $fields,
        string $search
    ): void {
        if (empty($fields)) {
            return;
        }

        $table = $model->getTable();

        $columnTypes = self::getColumnTypes($model);

        $isNumeric = self::isNumericSearch($search);

        $first = true;

        foreach ($fields as $field) {

            /*
             * Field boleh:
             *
             * name
             * code
             * roles.name
             */
            $column = str_contains($field, '.')
                ? last(explode('.', $field))
                : $field;

            $type = $columnTypes[$column] ?? '';

            /*
             * Skip numeric column ketika
             * search bukan numeric.
             */
            if (
                !$isNumeric &&
                self::isNumericColumn($type)
            ) {
                continue;
            }

            $qualifiedField = str_contains($field, '.')
                ? $field
                : "{$table}.{$field}";

            if ($first) {

                $query->where(
                    $qualifiedField,
                    'like',
                    '%' . $search . '%'
                );

                $first = false;
            } else {

                $query->orWhere(
                    $qualifiedField,
                    'like',
                    '%' . $search . '%'
                );
            }
        }
    }

    /**
     * Search relation.
     *
     * Support:
     *
     * parent
     *
     * parent.parent
     *
     * user.role
     *
     * department.parent.parent
     */
    protected static function applyRelationSearch(
        Builder $query,
        Model $model,
        string $relationPath,
        array $fields,
        string $search
    ): void {
        $relations = explode('.', $relationPath);

        self::applyNestedRelationSearch(
            $query,
            $model,
            $relations,
            $fields,
            $search
        );
    }

    /**
     * Recursive relation search.
     */
    protected static function applyNestedRelationSearch(
        Builder $query,
        Model $model,
        array $relations,
        array $fields,
        string $search
    ): void {
        $relationName = array_shift($relations);

        if (!$relationName) {
            return;
        }

        /*
         * Pastikan relation benar-benar ada.
         */
        if (!method_exists($model, $relationName)) {
            return;
        }

        $relation = $model->{$relationName}();

        if (!$relation instanceof Relation) {
            return;
        }

        /*
         * Masih ada nested relation.
         *
         * Contoh:
         *
         * parent.parent
         */
        if (!empty($relations)) {

            $nestedPath = implode('.', $relations);

            $query->orWhereHas(
                $relationName,
                function (Builder $nestedQuery) use (
                    $nestedPath,
                    $fields,
                    $search
                ) {

                    /*
                     * Kita perlu mengambil model dari
                     * relation query untuk meneruskan
                     * recursive search.
                     */
                    $nestedModel =
                        $nestedQuery->getModel();

                    self::applyNestedRelationSearch(
                        $nestedQuery,
                        $nestedModel,
                        explode('.', $nestedPath),
                        $fields,
                        $search
                    );
                }
            );

            return;
        }

        /*
         * Relation terakhir.
         */
        $relatedModel = $relation->getRelated();

        $query->orWhereHas(
            $relationName,
            function (Builder $relatedQuery) use (
                $relatedModel,
                $fields,
                $search
            ) {

                $columnTypes =
                    self::getColumnTypes($relatedModel);

                $isNumeric =
                    self::isNumericSearch($search);

                $relatedTable =
                    $relatedModel->getTable();

                $first = true;

                /*
                 * Jika field tidak ditentukan,
                 * cari seluruh kolom yang aman.
                 */
                $searchFields = !empty($fields)
                    ? $fields
                    : array_keys($columnTypes);

                foreach ($searchFields as $field) {

                    $column = str_contains($field, '.')
                        ? last(explode('.', $field))
                        : $field;

                    $type =
                        $columnTypes[$column] ?? '';

                    if (
                        !$isNumeric &&
                        self::isNumericColumn($type)
                    ) {
                        continue;
                    }

                    $qualifiedField =
                        "{$relatedTable}.{$column}";

                    if ($first) {

                        $relatedQuery->where(
                            $qualifiedField,
                            'like',
                            '%' . $search . '%'
                        );

                        $first = false;
                    } else {

                        $relatedQuery->orWhere(
                            $qualifiedField,
                            'like',
                            '%' . $search . '%'
                        );
                    }
                }
            }
        );
    }

    /**
     * Ambil tipe seluruh kolom model.
     */
    protected static function getColumnTypes(
        Model $model
    ): array {
        $connection =
            $model->getConnection();

        $table =
            $model->getTable();

        $columns =
            $connection
            ->getSchemaBuilder()
            ->getColumns($table);

        $types = [];

        foreach ($columns as $column) {

            $types[$column['name']] =
                strtolower(
                    $column['type_name']
                        ?? $column['type']
                        ?? ''
                );
        }

        return $types;
    }

    /**
     * Apakah keyword berupa angka?
     */
    protected static function isNumericSearch(
        string $search
    ): bool {
        return is_numeric(
            str_replace('%', '', $search)
        );
    }

    /**
     * Apakah tipe kolom numerik?
     */
    protected static function isNumericColumn(
        string $type
    ): bool {
        $type = strtolower($type);

        foreach (
            array_merge(
                self::$integerTypes,
                self::$decimalTypes
            ) as $numericType
        ) {
            if (str_contains($type, $numericType)) {
                return true;
            }
        }

        return false;
    }
}
