<?php
// app/Listeners/SyncPayrollToFinance.php

namespace App\Listeners;

use App\Services\PayrollFinanceIntegrationService;
use App\Events\PayrollCompletedEvent;

class SyncPayrollToFinance
{
    protected $integrationService;

    public function __construct(PayrollFinanceIntegrationService $service)
    {
        $this->integrationService = $service;
    }

    public function handle(PayrollCompletedEvent $event)
    {
        $this->integrationService->syncToFinance($event->payrollRun);
    }
}