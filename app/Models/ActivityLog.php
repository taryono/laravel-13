<?php

namespace Models;

class ActivityLog extends BasedModel
{
    protected $table = 'activity_logs';
    const UPDATED_AT = null;

    public static function bootSoftDeletes()
    {
        // Table has no deleted_at column
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
