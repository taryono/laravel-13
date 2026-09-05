<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalSearch
{
    protected static array $integerTypes = [
        'int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint',
        'tinyinteger', 'smallinteger', 'mediuminteger', 'unsignedinteger',
        'unsignedbiginteger', 'unsignedtinyinteger', 'unsignedsmallinteger',
    ];

    protected static function isNumericSearch(string $search): bool
    {
        $keyword = str_replace('%', '', $search);
        return is_numeric($keyword);
    }

    /**
     * Ambil tipe kolom dari sebuah model + nama tabel/alias tertentu.
     * Dipisah dari Builder supaya bisa dipakai untuk tabel beralias juga.
     */
    protected static function getColumnTypesFor(Model $model): array
    {
        $connection = $model->getConnection();
        $table      = $model->getTable();

        $columns = $connection->getSchemaBuilder()->getColumns($table);

        $types = [];
        foreach ($columns as $column) {
            $types[$column['name']] = strtolower($column['type_name'] ?? $column['type'] ?? '');
        }

        return $types;
    }

    protected static function getColumnTypes(Builder $query): array
    {
        return self::getColumnTypesFor($query->getModel());
    }

    protected static function isIntegerColumn(string $columnType): bool
    {
        foreach (self::$integerTypes as $intType) {
            if (str_contains($columnType, $intType)) {
                return true;
            }
        }
        return false;
    }

    public static function search(Builder $builder, array $relations, array $searchableFields): Builder
    {
        if (request()->filled('search.value')) {
            $search    = '%' . request()->input('search.value') . '%';
            $model     = $builder->getModel();
            $mainTable = $model->getTable();
            $isNumeric = self::isNumericSearch($search);

            $builder->where(function ($query) use ($searchableFields, $relations, $search, $mainTable, $isNumeric, $model) {

                $columnTypes = self::getColumnTypes($query);
                $firstCondition = true;

                foreach ($searchableFields as $value) {
                    $field      = str_contains($value, '.') ? $value : "{$mainTable}.{$value}";
                    $columnName = str_contains($value, '.') ? explode('.', $value)[1] : $value;
                    $columnType = $columnTypes[$columnName] ?? '';

                    if (!$isNumeric && self::isIntegerColumn($columnType)) {
                        continue;
                    }

                    if ($firstCondition) {
                        $query->where($field, 'like', $search);
                        $firstCondition = false;
                    } else {
                        $query->orWhere($field, 'like', $search);
                    }
                }

                // Search lewat relasi
                foreach ($relations as $relationName) {
                    $relationInstance = $model->{$relationName}();
                    $relatedModel     = $relationInstance->getRelated();
                    $relatedTable     = $relatedModel->getTable();

                    if ($relatedTable === $mainTable) {
                        dd($relatedTable,$mainTable);
                        // Relasi self-referencing (mis. parent/child) -> pakai alias
                        self::applySelfReferencingSearch(
                            $query,
                            $relationInstance,
                            $relationName,
                            $mainTable,
                            $search,
                            $isNumeric
                        );
                    } else {
                        $query->orWhereHas($relationName, function ($query2) use ($search, $isNumeric) {
                            $relatedTable   = $query2->getModel()->getTable();
                            $columnTypes    = self::getColumnTypes($query2);
                            $firstCondition = true;

                            foreach (array_keys($columnTypes) as $column) {
                                $columnType = $columnTypes[$column] ?? '';

                                if (!$isNumeric && self::isIntegerColumn($columnType)) {
                                    continue;
                                }

                                $field = "{$relatedTable}.{$column}";

                                if ($firstCondition) {
                                    $query2->where($field, 'like', $search);
                                    $firstCondition = false;
                                } else {
                                    $query2->orWhere($field, 'like', $search);
                                }
                            }
                        });
                    }
                }
            });
        }

        return $builder;
    }

    /**
     * Tangani relasi yang tabel-nya sama dengan tabel utama (self-referencing),
     * misalnya parent()/children() pada model Department.
     * Menggunakan whereExists + alias eksplisit supaya tidak bentrok nama tabel.
     */
    protected static function applySelfReferencingSearch(
        $query,
        Relation $relationInstance,
        string $relationName,
        string $mainTable,
        string $search,
        bool $isNumeric
    ): void {
        $relatedModel = $relationInstance->getRelated();
        $alias = $mainTable . '_' . $relationName . '_srch'; // alias unik per relasi

        $query->orWhereExists(function ($sub) use (
            $relationInstance, $relatedModel, $alias, $mainTable, $search, $isNumeric
        ) {
            $sub->from($relatedModel->getTable() . ' as ' . $alias);

            // Tentukan kondisi join berdasarkan jenis relasi
            if ($relationInstance instanceof BelongsTo) {
                // mis. parent(): departments.parent_id = parent.id
                $foreignKey = $relationInstance->getForeignKeyName(); // kolom di tabel utama, mis. parent_id
                $ownerKey   = $relationInstance->getOwnerKeyName();   // biasanya 'id'
                $sub->whereColumn("{$alias}.{$ownerKey}", "{$mainTable}.{$foreignKey}");
            } elseif ($relationInstance instanceof HasMany || $relationInstance instanceof HasOne) {
                // mis. children(): departments(child).parent_id = departments(parent).id
                $foreignKey = $relationInstance->getForeignKeyName(); // kolom di tabel terkait
                $localKey   = $relationInstance->getLocalKeyName();
                $sub->whereColumn("{$alias}.{$foreignKey}", "{$mainTable}.{$localKey}");
            } else {
                // Jenis relasi self-referencing lain belum didukung, aman-kan saja
                $sub->whereRaw('1 = 0');
                return;
            }

            // Soft delete guard, kalau modelnya pakai SoftDeletes
            if (in_array(SoftDeletes::class, class_uses_recursive($relatedModel))) {
                $sub->whereNull("{$alias}.deleted_at");
            }

            // Search di semua kolom tabel terkait (pakai alias)
            $columnTypes    = self::getColumnTypesFor($relatedModel);
            $firstCondition = true;

            foreach (array_keys($columnTypes) as $column) {
                $columnType = $columnTypes[$column] ?? '';

                if (!$isNumeric && self::isIntegerColumn($columnType)) {
                    continue;
                }

                $field = "{$alias}.{$column}";

                if ($firstCondition) {
                    $sub->where($field, 'like', $search);
                    $firstCondition = false;
                } else {
                    $sub->orWhere($field, 'like', $search);
                }
            }
        });
    }
}