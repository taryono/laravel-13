<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Models\Employee;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'supervisor_id' => [
                'nullable',
                'exists:employees,id',
                function ($attribute, $value, $fail) {
                    $employee = $this->route('employee'); // saat update
                    if (!$employee) return;

                    $current = Employee::find($value);
                    while ($current) {
                        if ($current->id === $employee->id) {
                            $fail('Supervisor tidak boleh circular.');
                            return;
                        }
                        $current = $current->supervisor;
                    }
                },
            ],
        ];
    }
}
