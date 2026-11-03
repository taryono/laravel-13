<?php
// tests/Unit/Services/ReportCard/ReportCardRankCalculatorTest.php

namespace Tests\Unit\Services\ReportCard;

use App\Services\ReportCard\ReportCardRankCalculator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReportCardRankCalculatorTest extends TestCase
{
    private ReportCardRankCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new ReportCardRankCalculator();
    }

    #[Test]
    public function assign_ranks_memberi_rank_berurutan()
    {
        $items = collect([
            (object) ['id' => 1, 'average_score' => 90, 'total_score' => 450],
            (object) ['id' => 2, 'average_score' => 85, 'total_score' => 425],
            (object) ['id' => 3, 'average_score' => 80, 'total_score' => 400],
        ]);

        $method = new \ReflectionMethod($this->calc, 'assignRanks');
        $result = $method->invoke($this->calc, $items);

        $this->assertEquals([
            ['id' => 1, 'rank' => 1],
            ['id' => 2, 'rank' => 2],
            ['id' => 3, 'rank' => 3],
        ], $result);
    }

    #[Test]
    public function assign_ranks_menangani_tie_dengan_rank_sama()
    {
        $items = collect([
            (object) ['id' => 1, 'average_score' => 90, 'total_score' => 450],
            (object) ['id' => 2, 'average_score' => 90, 'total_score' => 450],
            (object) ['id' => 3, 'average_score' => 85, 'total_score' => 425],
        ]);

        $method = new \ReflectionMethod($this->calc, 'assignRanks');
        $result = $method->invoke($this->calc, $items);

        $this->assertEquals([
            ['id' => 1, 'rank' => 1],
            ['id' => 2, 'rank' => 1],  // tie
            ['id' => 3, 'rank' => 3],  // lompat ke 3
        ], $result);
    }

    #[Test]
    public function assign_ranks_menangani_tie_break_dengan_total_score()
    {
        $items = collect([
            (object) ['id' => 1, 'average_score' => 90, 'total_score' => 455],
            (object) ['id' => 2, 'average_score' => 90, 'total_score' => 450],
        ]);

        $method = new \ReflectionMethod($this->calc, 'assignRanks');
        $result = $method->invoke($this->calc, $items);

        $this->assertEquals([
            ['id' => 1, 'rank' => 1],
            ['id' => 2, 'rank' => 2],  // average sama, total beda
        ], $result);
    }
}
