<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DatatableFilter
{
    /**
     * Apply kumpulan filter yang diizinkan.
     *
     * Contoh:
     *
     * DatatableFilter::apply($query, [
     *     'is_active' => 'boolean',
     *     'is_system' => 'boolean',
     *     'parent_id' => 'integer',
     * ]);
     */
    public static function apply(
        Builder $query,
        array $filters = []
    ): Builder {

        foreach ($filters as $field => $config) {

            /*
             * Support shorthand:
             *
             * 'is_active' => 'boolean'
             */
            if (is_string($config)) {

                $config = [
                    'type' => $config,
                ];
            }

            $type = $config['type'] ?? 'string';

            $operator =
                $config['operator'] ?? '=';

            $requestKey =
                $config['request'] ?? $field;

            /*
             * Jangan proses jika request
             * tidak mengandung filter.
             */
            if (!request()->has($requestKey)) {
                continue;
            }

            $value =
                request()->input($requestKey);

            /*
             * Empty value berarti
             * filter tidak aktif.
             */
            if (
                $value === null ||
                $value === ''
            ) {
                continue;
            }

            /*
             * Relation filter.
             *
             * Contoh:
             *
             * 'parent.name' => ...
             */
            if (str_contains($field, '.')) {

                self::applyRelationFilter(
                    $query,
                    $field,
                    $value,
                    $type,
                    $operator
                );

                continue;
            }

            /*
             * Normal field.
             */
            self::applyFieldFilter(
                $query,
                $field,
                $value,
                $type,
                $operator,
                $config
            );
        }

        return $query;
    }

    /**
     * Filter normal column.
     */
    protected static function applyFieldFilter(
        Builder $query,
        string $field,
        mixed $value,
        string $type,
        string $operator,
        array $config = []
    ): void {

        $value = self::normalizeValue(
            $value,
            $type
        );

        switch ($type) {

            case 'boolean':

                $query->where(
                    $field,
                    '=',
                    (int) $value
                );

                break;


            case 'integer':

                if (!is_numeric($value)) {
                    return;
                }

                $query->where(
                    $field,
                    $operator,
                    (int) $value
                );

                break;


            case 'decimal':

                if (!is_numeric($value)) {
                    return;
                }

                $query->where(
                    $field,
                    $operator,
                    (float) $value
                );

                break;


            case 'date':
            case 'datetime':

                $query->where(
                    $field,
                    $operator,
                    $value
                );

                break;


            case 'like':

                $query->where(
                    $field,
                    'like',
                    '%' . $value . '%'
                );

                break;


            case 'in':

                $values =
                    is_array($value)
                    ? $value
                    : explode(',', $value);

                if (!empty($values)) {

                    $query->whereIn(
                        $field,
                        $values
                    );
                }

                break;


            case 'not_in':

                $values =
                    is_array($value)
                    ? $value
                    : explode(',', $value);

                if (!empty($values)) {

                    $query->whereNotIn(
                        $field,
                        $values
                    );
                }

                break;


            case 'null':

                /*
                 * Filter "kolom X kosong".
                 *
                 * Tidak butuh value spesifik, cukup
                 * flag aktif dari frontend, contoh:
                 *
                 * <input type="checkbox"
                 *        data-column="deleted_at"
                 *        value="1">
                 *
                 * 'deleted_at' => 'null',
                 */
                $query->whereNull($field);

                break;


            case 'not_null':

                /*
                 * Filter "kolom X terisi".
                 *
                 * 'approved_at' => 'not_null',
                 */
                $query->whereNotNull($field);

                break;


            case 'between':

                /*
                 * Terima juga string comma-separated,
                 * contoh dari <input> biasa atau query
                 * string manual:
                 *
                 * "2024-01-01,2024-12-31"
                 * "10,50"
                 */
                if (is_string($value)) {

                    $value = array_map(
                        'trim',
                        explode(',', $value)
                    );
                }

                if (
                    !is_array($value) ||
                    count($value) !== 2
                ) {
                    return;
                }

                /*
                 * Buang jika salah satu sisi kosong,
                 * mis. "2024-01-01," -> incomplete,
                 * jangan sampai lolos ke whereBetween.
                 */
                if (
                    $value[0] === '' ||
                    $value[1] === '' ||
                    $value[0] === null ||
                    $value[1] === null
                ) {
                    return;
                }

                /*
                 * Optional: cast tiap sisi sesuai tipe
                 * aslinya sebelum dibandingkan, karena
                 * 'between' sendiri bukan tipe data,
                 * cuma bentuk kondisinya.
                 *
                 * Contoh config:
                 *
                 * 'price' => [
                 *     'type' => 'between',
                 *     'cast' => 'decimal',
                 * ],
                 *
                 * 'created_at' => [
                 *     'type' => 'between',
                 *     'cast' => 'date',
                 * ],
                 */
                $cast = $config['cast'] ?? null;

                if ($cast) {

                    $value = array_map(
                        fn($item) => self::normalizeValue(
                            $item,
                            $cast
                        ),
                        $value
                    );
                }

                $query->whereBetween(
                    $field,
                    $value
                );

                break;


            default:

                $query->where(
                    $field,
                    $operator,
                    $value
                );

                break;
        }
    }

    /**
     * Filter berdasarkan relation.
     *
     * Contoh:
     *
     * parent.name
     *
     * user.role_id
     */
    protected static function applyRelationFilter(
        Builder $query,
        string $field,
        mixed $value,
        string $type,
        string $operator
    ): void {

        $segments =
            explode('.', $field);

        $column =
            array_pop($segments);

        $relation =
            implode('.', $segments);

        $query->whereHas(
            $relation,
            function (Builder $relationQuery) use (
                $column,
                $value,
                $type,
                $operator
            ) {

                self::applyFieldFilter(
                    $relationQuery,
                    $column,
                    $value,
                    $type,
                    $operator
                );
            }
        );
    }

    /**
     * Normalisasi value berdasarkan type.
     */
    protected static function normalizeValue(
        mixed $value,
        string $type
    ): mixed {

        if (is_array($value)) {
            return array_map(
                fn($item) =>
                self::normalizeValue(
                    $item,
                    $type
                ),
                $value
            );
        }

        switch ($type) {

            case 'boolean':

                return filter_var(
                    $value,
                    FILTER_VALIDATE_BOOLEAN
                );

            case 'integer':

                return (int) $value;

            case 'decimal':

                return (float) $value;

            case 'date':

                try {
                    return Carbon::parse($value)
                        ->toDateString();
                } catch (\Throwable) {
                    return $value;
                }

            case 'datetime':

                try {
                    return Carbon::parse($value)
                        ->format('Y-m-d H:i:s');
                } catch (\Throwable) {
                    return $value;
                }

            default:

                return $value;
        }
    }
}
