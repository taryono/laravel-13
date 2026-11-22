<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends BasedModel
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_role',
        'action',
        'module',
        'record_id',
        'record_type',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'status',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relasi ke user yang melakukan aksi
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Relasi polymorphic ke record yang diubah (User, Student, Invoice, dll)
    public function record(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'record_type', 'record_id');
    }

    // Scope umum
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForRecord($query, Model $record)
    {
        return $query->where('record_type', $record->getMorphClass())
            ->where('record_id', $record->getKey());
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
}
