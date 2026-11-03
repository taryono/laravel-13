<?php
// tests/Unit/Services/ReportCard/ReportCardCalculatorTest.php

namespace Tests\Unit\Services\ReportCard;

use App\Services\ReportCard\ReportCardCalculator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReportCardCalculatorTest extends TestCase
{
    private ReportCardCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new ReportCardCalculator();
    }

    #[Test]
    public function average_menghitung_rata_rata_nilai_valid()
    {
        $grades = [
            ['final_score' => 88.50],
            ['final_score' => 85.00],
            ['final_score' => 90.00],
            ['final_score' => 82.50],
        ];

        $this->assertEquals(86.50, $this->calc->average($grades));
    }

    #[Test]
    public function average_mengabaikan_nilai_null()
    {
        $grades = [
            ['final_score' => 88.50],
            ['final_score' => null],
            ['final_score' => 90.00],
        ];

        $this->assertEquals(89.25, $this->calc->average($grades));
    }

    #[Test]
    public function average_return_null_jika_semua_nilai_null()
    {
        $grades = [
            ['final_score' => null],
            ['final_score' => null],
        ];

        $this->assertNull($this->calc->average($grades));
    }

    #[Test]
    public function average_return_null_jika_array_kosong()
    {
        $this->assertNull($this->calc->average([]));
    }

    #[Test]
    public function total_menjumlahkan_semua_nilai_valid()
    {
        $grades = [
            ['final_score' => 88.50],
            ['final_score' => 85.00],
            ['final_score' => 90.00],
        ];

        $this->assertEquals(263.50, $this->calc->total($grades));
    }
}
