<?php

namespace Lib;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Bertanggung jawab HANYA untuk resolve model class dari nama field,
 * menentukan tipe relasi, dan preload+cache data relasi (dropdown options).
 *
 * FIX BUG: sebelumnya cache key untuk relasi "status_id" tidak menyertakan
 * konteks tabel model pemilik (group), sehingga model A dan model B yang
 * sama-sama punya status_id bisa saling "menimpa" cache satu sama lain.
 * Sekarang cache key selalu menyertakan konteks yang relevan.
 */
class RelationDataResolver
{
    protected const CACHE_PREFIX = 'formlib_';
    protected const CACHE_TTL = 3600; // 1 jam

    // Field _id yang secara eksplisit BUKAN foreign key relasi (jangan di-resolve)
    protected const IGNORED_ID_FIELDS = ['parent_id', 'node_id', 'object_id'];

    /**
     * Preload semua data relasi (dropdown options) untuk field-field _id
     * dalam satu proses, sekaligus menghindari N+1 query.
     *
     * @param array $fields daftar nama kolom pada tabel model
     * @param object $ownerModel model utama (dipakai untuk konteks, mis. tabel status)
     */
    public function preloadAll(array $fields, $ownerModel): array
    {
        $data = [];

        foreach ($fields as $field) {
            if (!Str::endsWith($field, "_id")) continue;
            if (in_array($field, self::IGNORED_ID_FIELDS)) continue;

            $name = str_replace("_id", "", $field);
            $modelClass = $this->resolveModelClass($name);

            if (!class_exists($modelClass)) continue;

            $data[$field] = $this->loadForField($field, $modelClass, $ownerModel);
        }

        return $data;
    }

    /**
     * Ambil data relasi untuk SATU field, dengan cache yang cache-key-nya
     * sudah menyertakan seluruh konteks yang memengaruhi hasil query.
     */
    protected function loadForField(string $field, string $modelClass, $ownerModel): array
    {
        $model = new $modelClass;
        $cacheKey = $this->cacheKeyFor($field, $modelClass, $model, $ownerModel);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($model, $field, $ownerModel) {
            // Kasus khusus: tabel "statuses" difilter berdasarkan group = nama tabel owner.
            if ($model->getTable() === "statuses") {
                return $model->where('group', Str::singular($ownerModel->getTable()))
                    ->pluck('name', 'id')
                    ->toArray();
            }

            // Kasus khusus: role_id tidak menyertakan role id=1 (superadmin, dsb).
            if ($field === "role_id") {
                return $model->where('id', '!=', 1)->pluck('name', 'id')->toArray();
            }

            return $model->pluck('name', 'id')->toArray();
        });
    }

    /**
     * Bangun cache key yang unik berdasarkan SEMUA parameter yang
     * memengaruhi hasil query, bukan hanya nama model relasi.
     */
    protected function cacheKeyFor(string $field, string $modelClass, $model, $ownerModel): string
    {
        $base = self::CACHE_PREFIX . 'rel_' . Str::snake(class_basename($modelClass));

        // Tabel "statuses" datanya bergantung pada group (= tabel owner),
        // jadi context tabel owner WAJIB masuk ke cache key.
        if ($model->getTable() === "statuses") {
            return $base . '_group_' . Str::singular($ownerModel->getTable());
        }

        // role_id difilter secara statis (id != 1), tapi tetap kita beri
        // suffix eksplisit supaya jelas ini varian query yang berbeda
        // dari query pluck biasa, dan mudah dibedakan saat debug cache.
        if ($field === "role_id") {
            return $base . '_no_super_admin';
        }

        return $base;
    }

    /**
     * Tentukan namespace model secara otomatis dari nama field.
     *
     * CATATAN: konvensi ini hardcoded (user -> \App\Models, lainnya -> \Models).
     * Jika ada model yang tidak mengikuti pola ini, field akan silent-skip.
     * Pertimbangkan pindahkan mapping ini ke config (lihat rekomendasi refactor).
     */
    public function resolveModelClass(string $name): string
    {
        $studly = ucwords(Str::camel($name));
        return $name === "user"
            ? "\\App\\Models\\{$studly}"
            : "\\Models\\{$studly}";
    }

    /**
     * Tentukan tipe relasi Eloquent (BelongsTo, HasMany, dst) dari method di model.
     */
    public function getRelationType($ownerModel, string $method): string
    {
        $relation = $ownerModel->{$method}();
        $class = get_class($relation);

        return str_replace("Illuminate\\Database\\Eloquent\\Relations\\", "", $class);
    }
}