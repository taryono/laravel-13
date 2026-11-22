<?php

// app/Traits/LogsActivity.php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Models\ActivityLog;
use Throwable;

/**
 * Mencatat create / update / delete (dan restore untuk SoftDeletes) ke ActivityLog.
 *
 * Pasang di MODEL, bukan di controller.
 *
 * Kustomisasi per model (override method ini di model jika perlu):
 *  - activityLogHidden()  : field yang nilainya disamarkan
 *  - activityLogIgnored() : field yang diabaikan saat update (tidak dianggap perubahan)
 */
trait LogsActivity
{
    /** Penanda untuk menonaktifkan logging sementara (per class model). */
    protected static bool $activityLogDisabled = false;

    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            static::writeActivityLog($model, 'create', [
                'new_values' => static::maskActivityValues($model, $model->getAttributes()),
            ]);
        });

        static::updated(function (Model $model) {
            $changes = Arr::except($model->getChanges(), $model->activityLogIgnored());

            if (empty($changes)) {
                return;
            }

            $old = Arr::only($model->getOriginal(), array_keys($changes));

            static::writeActivityLog($model, 'update', [
                'old_values' => static::maskActivityValues($model, $old),
                'new_values' => static::maskActivityValues($model, $changes),
                'changed_fields' => array_keys($changes),
            ]);
        });

        static::deleted(function (Model $model) {
            static::writeActivityLog($model, 'delete', [
                'old_values' => static::maskActivityValues($model, $model->getOriginal()),
            ]);
        });

        // Hanya didaftarkan jika model memakai SoftDeletes
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(function (Model $model) {
                static::writeActivityLog($model, 'restore', [
                    'new_values' => static::maskActivityValues($model, $model->getAttributes()),
                ]);
            });
        }
    }

    /**
     * Field yang nilainya disamarkan di log.
     * Digabung dengan $hidden milik model.
     */
    public function activityLogHidden(): array
    {
        return array_unique(array_merge([
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'token',
            'secret',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ], $this->getHidden()));
    }

    /**
     * Field yang diabaikan saat mendeteksi perubahan pada update.
     */
    public function activityLogIgnored(): array
    {
        return ['created_at', 'updated_at'];
    }

    /**
     * Jalankan callback tanpa mencatat activity log (mis. seeder / import massal).
     *
     *   AcademicSemester::withoutActivityLog(fn () => AcademicSemester::create([...]));
     */
    public static function withoutActivityLog(callable $callback): mixed
    {
        $previous = static::$activityLogDisabled;
        static::$activityLogDisabled = true;

        try {
            return $callback();
        } finally {
            static::$activityLogDisabled = $previous;
        }
    }

    /**
     * Samarkan field sensitif. Key tetap ada agar terlihat bahwa field tersebut berubah,
     * tetapi nilainya tidak tersimpan.
     */
    protected static function maskActivityValues(Model $model, array $values): array
    {
        foreach ($model->activityLogHidden() as $field) {
            if (array_key_exists($field, $values)) {
                $values[$field] = '[FILTERED]';
            }
        }

        return $values;
    }

    /**
     * Tulis satu baris ActivityLog dengan metadata yang konsisten untuk semua aksi.
     */
    protected static function writeActivityLog(Model $model, string $action, array $extra = []): void
    {
        // Cegah rekursi jika ActivityLog sendiri memakai trait ini, atau logging dimatikan
        if (static::$activityLogDisabled || $model instanceof ActivityLog) {
            return;
        }

        try {
            $request = request();
            $user = auth()->user();

            ActivityLog::create(array_merge([
                'user_id' => $user?->getAuthIdentifier(),
                'user_role' => $user?->role,
                'action' => $action,
                'module' => class_basename($model),
                'record_id' => $model->getKey(),
                'record_type' => $model->getMorphClass(),
                'status' => 'success',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ], $extra));
        } catch (Throwable $e) {
            // Kegagalan mencatat log tidak boleh menggagalkan proses utama
            Log::warning('Gagal menulis ActivityLog.', [
                'exception' => $e->getMessage(),
                'module' => class_basename($model),
                'action' => $action,
                'record_id' => $model->getKey(),
            ]);
        }
    }
}