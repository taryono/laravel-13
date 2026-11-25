<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'PRESENT';
    case SICK = 'SICK';
    case PERMIT = 'PERMIT';
    case ABSENT = 'ABSENT';
    case LATE = 'LATE';

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Hadir',
            self::SICK => 'Sakit',
            self::PERMIT => 'Izin',
            self::ABSENT => 'Alpa / Tanpa Keterangan',
            self::LATE => 'Terlambat',
        };
    }
}