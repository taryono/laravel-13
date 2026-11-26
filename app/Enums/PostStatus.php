<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PENDING_REVIEW = 'pending_review';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Menunggu Review',
            self::PUBLISHED => 'Terbit',
            self::SCHEDULED => 'Terjadwal',
            self::ARCHIVED => 'Diarsipkan',
            self::REJECTED => 'Ditolak',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-secondary',
            self::PENDING_REVIEW => 'bg-warning text-dark',
            self::PUBLISHED => 'bg-success',
            self::SCHEDULED => 'bg-info text-dark',
            self::ARCHIVED => 'bg-secondary text-white',
            self::REJECTED => 'bg-danger text-white',
        };
    }
}