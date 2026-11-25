<?php

namespace Models;
use Illuminate\Database\Eloquent\Builder;
class Admission extends SoftDeletableModel
{
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
        'requirements',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'is_not_deleted',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'min_age_reference_date' => 'date',
            'max_age_reference_date' => 'date',
            'quota' => 'integer',
            'registration_fee' => 'decimal:2',
            'min_age_years' => 'integer',
            'min_age_months' => 'integer',
            'max_age_years' => 'integer',
            'max_age_months' => 'integer',
            'is_active' => 'boolean',
            'is_not_deleted' => 'boolean',
        ];
    }

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

    public function scopeOpenToday(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    public function getTypeLabelAttribute(): string
    {
        return match (strtoupper((string) $this->type)) {
            'REGULAR' => 'Jalur Reguler',
            'ACHIEVEMENT' => 'Jalur Prestasi',
            'AFFIRMATION' => 'Jalur Afirmasi / Beasiswa',
            'TRANSFER' => 'Siswa Pindahan',
            default => $this->type ?? '-',
        };
    }

    /**
     * Memeriksa apakah tanggal lahir calon siswa memenuhi syarat gelombang ini.
     */
    public function isEligibleBirthDate(\Carbon\CarbonInterface $birthDate): array
    {
        if ($this->min_age_years !== null && $this->min_age_reference_date !== null) {
            $latestAllowedBirth = $this->min_age_reference_date->copy()
                ->subYears($this->min_age_years)
                ->subMonths($this->min_age_months ?? 0);

            if ($birthDate->gt($latestAllowedBirth)) {
                return [
                    'eligible' => false,
                    'reason' => sprintf(
                        'Usia belum mencukupi syarat minimal %d thn %d bln pada %s (maksimal lahir %s).',
                        $this->min_age_years,
                        $this->min_age_months,
                        $this->min_age_reference_date->translatedFormat('d F Y'),
                        $latestAllowedBirth->translatedFormat('d F Y')
                    ),
                ];
            }
        }

        if ($this->max_age_years !== null && $this->max_age_reference_date !== null) {
            $earliestAllowedBirth = $this->max_age_reference_date->copy()
                ->subYears($this->max_age_years)
                ->subMonths($this->max_age_months ?? 0);

            if ($birthDate->lt($earliestAllowedBirth)) {
                return [
                    'eligible' => false,
                    'reason' => sprintf(
                        'Usia melebihi batas maksimal %d thn %d bln pada %s (minimal lahir %s).',
                        $this->max_age_years,
                        $this->max_age_months,
                        $this->max_age_reference_date->translatedFormat('d F Y'),
                        $earliestAllowedBirth->translatedFormat('d F Y')
                    ),
                ];
            }
        }

        return ['eligible' => true, 'reason' => null];
    }
}
