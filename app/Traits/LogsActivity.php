<?php
// app/Traits/HasUniqueCode.php

namespace App\Traits;
use \Models\ActivityLog;
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn($model) => ActivityLog::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->role,
            'action' => 'create',
            'module' => class_basename($model),
            'record_id' => $model->getKey(),
            'record_type' => $model->getMorphClass(),
            'new_values' => $model->getAttributes(),
            'status' => 'success',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]));

        static::updated(function ($model) {
            $changed = $model->getChanges();
            if (empty($changed))
                return;
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_role' => auth()->user()?->role,
                'action' => 'update',
                'module' => class_basename($model),
                'record_id' => $model->getKey(),
                'record_type' => $model->getMorphClass(),
                'old_values' => array_intersect_key($model->getOriginal(), $changed),
                'new_values' => $changed,
                'changed_fields' => array_keys($changed),
                'status' => 'success',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);
        });

        static::deleted(fn($model) => ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'module' => class_basename($model),
            'record_id' => $model->getKey(),
            'record_type' => $model->getMorphClass(),
            'old_values' => $model->getOriginal(),
            'status' => 'success',
            'ip_address' => request()->ip(),
        ]));
    }
}