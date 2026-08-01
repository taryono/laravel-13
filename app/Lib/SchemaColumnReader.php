<?php

namespace Lib;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Bertanggung jawab HANYA untuk membaca & meng-cache metadata kolom tabel.
 * Dipisah dari FormLib supaya bisa dites & dipakai ulang secara independen.
 */
class SchemaColumnReader
{
    protected const CACHE_PREFIX = 'formlib_';
    protected const CACHE_TTL = 3600; // 1 jam
    protected const DEFAULT_EXCEPTIONS = ['remember_token', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Ambil daftar nama field (kolom) yang sudah difilter dari exception, dengan cache.
     */
    public function getFilteredFields($model): array
    {
        $table = $model->getTable();
        $cacheKey = $this->fieldsCacheKey($table);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($model) {
            try {
                $fields = $model->getConnection()
                    ->getSchemaBuilder()
                    ->getColumnListing($model->getTable());

                return array_diff($fields, self::DEFAULT_EXCEPTIONS);
            } catch (\Throwable $e) {
                report($e);
                return [];
            }
        });
    }

    /**
     * Ambil detail metadata kolom (type, nullable, comment, dll), dengan cache.
     */
    public function getColumns($model, $table = null)
    {
        $table ??= $model->getTable();
        $cacheKey = $this->columnsCacheKey($table);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($model, $table) {
            $db = $model->getConnection()->getConfig()['database'];
            $columns = [];

            // NOTE: idealnya pakai binding/quoting nama tabel & db untuk hindari
            // masalah jika nama tabel mengandung karakter aneh. Karena nama
            // tabel biasanya berasal dari kode (bukan input user), risikonya rendah,
            // tapi tetap didokumentasikan di sini sebagai catatan.
            $results = DB::select("SHOW FULL COLUMNS FROM `{$db}`.`{$table}`");

            foreach ($results as $col) {
                if (in_array($col->Field, self::DEFAULT_EXCEPTIONS)) continue;

                $columns[$col->Field] = (object)[
                    "type"       => $col->Type,
                    "is_null"    => $col->Null,
                    "key"        => $col->Key,
                    "default"    => $col->Default,
                    "extra"      => $col->Extra,
                    "privileges" => $col->Privileges,
                    "comment"    => str_replace("\r\n", " ", $col->Comment),
                ];
            }

            return (object)$columns;
        });
    }

    /**
     * Update comment kolom & invalidate cache terkait (kolom + daftar field).
     */
    public function updateComment(string $table, string $field, string $comment): void
    {
        Schema::table($table, function (Blueprint $table) use ($field, $comment) {
            $table->string($field, 255)->comment($comment)->change();
        });

        $this->forgetCacheForTable($table);
    }

    /**
     * Hapus semua cache (kolom + daftar field) untuk sebuah tabel.
     * Dipanggil manual saat update comment, dan sebaiknya juga dipanggil
     * dari migration listener / observer saat skema berubah (lihat catatan refactor).
     */
    public function forgetCacheForTable(string $table): void
    {
        Cache::forget($this->columnsCacheKey($table));
        Cache::forget($this->fieldsCacheKey($table));
    }

    protected function columnsCacheKey(string $table): string
    {
        return self::CACHE_PREFIX . 'cols_' . $table;
    }

    protected function fieldsCacheKey(string $table): string
    {
        return self::CACHE_PREFIX . 'fields_' . $table;
    }
}