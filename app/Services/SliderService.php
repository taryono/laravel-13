<?php

// app/Services/SliderService.php
namespace App\Services;

use Models\Slider;
use Illuminate\Support\Collection;

class SliderService
{
    public function getActive(): Collection
    {
        return Slider::active()->get();
    }
}
