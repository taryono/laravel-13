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
            $employee->employee_number = EmployeeNumberGenerator::generate(
                $employee->organization_id
            );
        }
    }
}