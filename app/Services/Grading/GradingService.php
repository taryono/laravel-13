<?php

namespace App\Services\Grading;

use App\Models\GradingRule;

/**
 * Versi minimal: pembulatan dan konversi nilai -> predikat mengikuti GradingRule.
 * Asumsi struktur GradingRule:
 *   - decimals (int), rounding_mode ('nearest'|'up'|'down')
 *   - scaleRanges: relasi ke grade_scale_ranges
 *       (min_score, max_score, letter, predicate, description nullable)
 */
class GradingService
{
    /**
     * Fallback jika template belum punya grading rule.
     * Sama dengan aturan hardcoded lama supaya perilaku tidak berubah.
     */
    private const LEGACY_SCALE = [
        ['min' => 90, 'letter' => 'A', 'predicate' => 'Mumtaz'],
        ['min' => 80, 'letter' => 'B', 'predicate' => 'Jayyid Jiddan'],
        ['min' => 70, 'letter' => 'C', 'predicate' => 'Jayyid'],
        ['min' => 60, 'letter' => 'D', 'predicate' => 'Maqbul'],
        ['min' => 0,  'letter' => 'E', 'predicate' => 'Rasib'],
    ];

    public function round(?float $value, ?GradingRule $rule): ?float
    {
        if ($value === null) {
            return null;
        }

        $decimals = $rule->decimals ?? 2;
        $factor = 10 ** $decimals;

        return match ($rule->rounding_mode ?? 'nearest') {
            'up'    => ceil($value * $factor) / $factor,
            'down'  => floor($value * $factor) / $factor,
            default => round($value, $decimals),
        };
    }

    /**
     * @return array{letter: string, predicate: string, description: string}
     */
    public function resolve(?float $score, ?GradingRule $rule): array
    {
        $empty = ['letter' => '-', 'predicate' => '-', 'description' => ''];

        if ($score === null) {
            return $empty;
        }

        if ($rule === null) {
            foreach (self::LEGACY_SCALE as $band) {
                if ($score >= $band['min']) {
                    return [
                        'letter'      => $band['letter'],
                        'predicate'   => $band['predicate'],
                        'description' => '',
                    ];
                }
            }

            return $empty;
        }

        $range = $rule->scaleRanges
            ->first(fn ($r) => $score >= $r->min_score && $score <= $r->max_score);

        if (! $range) {
            return $empty;
        }

        return [
            'letter'      => (string) $range->letter,
            'predicate'   => (string) $range->predicate,
            'description' => (string) ($range->description ?? ''),
        ];
    }
}