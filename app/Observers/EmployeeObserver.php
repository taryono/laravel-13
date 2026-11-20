<?php 
// app/Observers/EmployeeObserver.php
namespace App\Observers;

use Models\Employee;
use App\Support\EmployeeNumberGenerator;

class EmployeeObserver
{
    public function creating(Employee $employee): void
    {
        if (empty($employee->employee_number)) {
            $orgId = (int) ($employee->organization_id
                ?? session('active_organization_id')
                ?? auth()->user()?->active_organization_id
                ?? auth()->user()?->current_organization_id
                ?? 1);

            if (empty($employee->organization_id)) {
                $employee->organization_id = $orgId;
            }

            $employee->employee_number = EmployeeNumberGenerator::generate($orgId);
        }
    }
}