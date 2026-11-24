<?php

// app/Services/SliderService.php
namespace App\Services;

use Models\Slider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SliderService
{
    public function getActive(): Collection
    {
        if (!Schema::hasTable('sliders')) {
            return collect([]);
        }
        return Slider::active()->get();
    }
}
