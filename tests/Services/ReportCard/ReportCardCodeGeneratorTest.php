<?php
// tests/Unit/Services/ReportCard/ReportCardCodeGeneratorTest.php

namespace Tests\Unit\Services\ReportCard;

use App\Services\ReportCard\ReportCardCodeGenerator;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportCardCodeGeneratorTest extends TestCase
{
    /** @test */
    public function generate_menghasilkan_format_yang_benar()
    {
        DB::shouldReceive('table')->andReturnSelf();
        DB::shouldReceive('join')->andReturnSelf();
        DB::shouldReceive('where')->andReturnSelf();
        DB::shouldReceive('select')->andReturnSelf();
        DB::shouldReceive('first')->andReturn((object) [
            'semester' => 1,
            'name'     => '2024/2025',
            'code'     => 'P01',
        ]);
        DB::shouldReceive('value')->andReturn(1);

        $gen = new ReportCardCodeGenerator();
        $code = $gen->generate(42, 1);

        $this->assertEquals('RC-001-2425-S1-00042-P01', $code);
    }
}
