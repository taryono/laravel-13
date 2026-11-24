<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Terbit',
            self::SCHEDULED => 'Terjadwal',
            self::ARCHIVED => 'Diarsipkan',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-secondary',
            self::PUBLISHED => 'bg-success',
            self::SCHEDULED => 'bg-info text-dark',
            self::ARCHIVED => 'bg-warning text-dark',
        };
    }
}