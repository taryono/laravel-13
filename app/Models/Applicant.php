<?php

namespace Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Applicant extends Authenticatable
{
    protected $table = 'applicants';

    protected $fillable = [
        'school_id',
        'admission_id',
        'admission_path_id',
        'user_id',
        'name',
        'nik',
        'nisn',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'phone',
        'email',
        'parent_name',
        'parent_phone',
        'parent_email',
        'previous_school',
        'registration_number',
        'status',
        'test_score',
        'interview_score',
        'total_score',
        'ranking',
        'notes',
        'registered_at',
        'is_active',
        'created_by',
        'updated_by',
        // kolom tambahan untuk minat bakat
        'extracurriculars',
        'foreign_language',
        'special_skill',
        'agree_test',
    ];

    protected $casts = [
        'birth_date'        => 'date',
        'registered_at'     => 'datetime',
        'extracurriculars'  => 'array',
        'agree_test'        => 'boolean',
        'is_active'         => 'boolean',
    ];


    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function domicile()
    {
        return $this->hasOne(ApplicantDomicile::class, 'applicant_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'applicant_id');
    }

    public function verifications()
    {
        return $this->hasMany(AdmissionDocumentVerification::class, 'applicant_id');
    }

    public function selections()
    {
        return $this->hasMany(AdmissionSelection::class, 'applicant_id');
    }

    public function achievements()
    {
        return $this->hasMany(ApplicantAchievement::class, 'applicant_id');
    }

    public function parents()
    {
        return $this->hasMany(ApplicantParent::class, 'applicant_id');
    }

    public function invoices()
    {
        return $this->hasMany(ApplicantInvoice::class, 'applicant_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected $guarded = ['id'];

    // Relasi Status
    public function status()
    {
        return $this->belongsTo(ApplicantStatus::class, 'applicant_status_id');
    }

    // Relasi Pivot Multi Kategori (BelongsToMany)
    public function categories()
    {
        return $this->belongsToMany(ApplicantCategory::class, 'applicant_applicant_category');
    }

    public function path()
    {
        return $this->belongsTo(AdmissionPath::class, 'admission_path_id');
    }

    public function support_programs()
    {
        return $this->hasMany(ApplicantSupportProgram::class, 'applicant_id');
    }
    public function choices()
    {
        return $this->hasMany(ApplicantChoice::class)->orderBy('choice_order');
    }

    public function statusHistories()
    {
        return $this->hasMany(ApplicantStatusHistory::class);
    }
    // Accessor
    public function getStatusBadgeAttribute(): string
    {
        $status = ApplicantStatus::where('code', $this->status)->first();
        return $status?->badge_class ?? 'badge-secondary';
    }
}
