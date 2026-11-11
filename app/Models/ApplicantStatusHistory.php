<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantStatusHistory extends Model
{
    protected $table = 'applicant_status_histories';

    protected $guarded = ['id'];

    const UPDATED_AT = null;

    protected $casts = [
        'metadata'          => 'array',
        'changed_by_system' => 'boolean',
        'created_at'        => 'datetime',
    ];

    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function fromStatus()
    {
        return $this->belongsTo(ApplicantStatus::class, 'from_status_id');
    }

    public function toStatus()
    {
        return $this->belongsTo(ApplicantStatus::class, 'to_status_id');
    }
}