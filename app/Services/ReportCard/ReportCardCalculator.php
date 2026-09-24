<?php
// app/Services/ReportCard/ReportCardCalculator.php

namespace App\Services\ReportCard;

class ReportCardCalculator
{
    /**
     * Rata-rata nilai akhir (null-safe).
     */
    public function average(array $grades): ?float
    {
        $scores = $this->validScores($grades);
        return empty($scores) ? null : round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Total nilai akhir (null-safe).
     */
    public function total(array $grades): ?float
    {
        $scores = $this->validScores($grades);
        return empty($scores) ? null : round(array_sum($scores), 2);
    }

    /**
     * Rata-rata pengetahuan saja.
     */
    public function averageKnowledge(array $grades): ?float
    {
        $scores = array_filter(
            array_column($grades, 'knowledge_score'),
            fn($v) => $v !== null && $v !== ''
        );
        return empty($scores) ? null : round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Rata-rata keterampilan saja.
     */
    public function averageSkill(array $grades): ?float
    {
        $scores = array_filter(
            array_column($grades, 'skill_score'),
            fn($v) => $v !== null && $v !== ''
        );
        return empty($scores) ? null : round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Filter nilai valid (bukan null, bukan string kosong, numerik).
     */
    protected function validScores(array $grades): array
    {
        return array_values(array_filter(
            array_column($grades, 'final_score'),
            fn($v) => $v !== null && $v !== '' && is_numeric($v)
        ));
    }
}
