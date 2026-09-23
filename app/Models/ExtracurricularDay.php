<?php

namespace Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ExtracurricularDay extends BasedModel
{
    protected $fillable = [
        'extracurricular_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function dayOfWeek(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_array($value)) {
                    return implode(',', $value);
                }

                return $value;
            },
            get: fn($value) => $value ? explode(',', $value) : [],
        );
    }

    public function attendances()
    {
        return $this->hasMany(ExtracurricularAttendance::class);
    }

    public static function getAvailableDays()
    {
        return [
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
            'Minggu' => 'Minggu',
        ];
    }
}
