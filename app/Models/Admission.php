<?php

namespace Models;

class Admission extends SoftDeletableModel
{
    protected $table = 'admissions';
    protected $fillable = [
        'school_id',
        'academic_year_id',
        'class_level_id',
        'name',
        'code',
        'type',
        'start_date',
        'end_date',
        'quota',
        'registration_fee',
        'min_age',
        'max_age',
        'requirements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function paths()
    {
        return $this->hasMany(AdmissionPath::class, 'admission_id');
    }

    public function timelines()
    {
        return $this->hasMany(AdmissionTimeline::class, 'admission_id')->orderBy('order_number');
    }

    public function quota_settings()
    {
        return $this->hasMany(AdmissionQuotaSetting::class, 'admission_id');
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'admission_id');
    }

    public function selections()
    {
        return $this->hasMany(AdmissionSelection::class, 'admission_id');
    }

    public function document_verifications()
    {
        return $this->hasMany(AdmissionDocumentVerification::class, 'admission_id');
    }

    public function announcements()
    {
        return $this->hasMany(AdmissionAnnouncement::class, 'admission_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ===== HELPER =====
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * Timeline default jika belum ada data di DB
     */
    public function getDefaultTimelinesAttribute(): array
    {
        return [
            [
                'phase_name' => 'Pendaftaran Online',
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'description' => 'Layanan 24 Jam (Ditutup pkl 23:59 WIB)',
            ],
            [
                'phase_name' => 'Verifikasi Berkas & Dokumen',
                'start_date' => $this->end_date->copy()->addDay(),
                'end_date' => $this->end_date->copy()->addDays(3),
                'description' => 'Jam Kerja Tim Panitia: 08:00 - 15:30 WIB',
            ],
            [
                'phase_name' => 'Pengumuman Kelulusan',
                'start_date' => $this->end_date->copy()->addDays(4),
                'end_date' => $this->end_date->copy()->addDays(4),
                'description' => 'Diumumkan serentak pukul 10:00 WIB',
            ],
            [
                'phase_name' => 'Daftar Ulang Fisik di Sekolah',
                'start_date' => $this->end_date->copy()->addDays(5),
                'end_date' => $this->end_date->copy()->addDays(8),
                'description' => 'Membawa berkas fisik asli ke sekolah tujuan',
            ],
        ];
    }
}
