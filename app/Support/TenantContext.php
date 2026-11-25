<?php

// app/Support/TenantContext.php
namespace App\Support;

use Models\Organization;

class TenantContext
{
    public static function get(): ?Organization
    {
        return app()->bound('currentTenant') ? app('currentTenant') : null;
    }

    public static function id(): ?int
    {
        return static::get()?->id;
    }
}