<?php
// app/Traits/HasUniqueCode.php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait HasUniqueCode
{
    /**
     * Boot the trait.
     */
    protected static function bootHasUniqueCode()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getCodeField()})) {
                $model->{$model->getCodeField()} = $model->generateUniqueCode();
            }
        });
    }

    /**
     * Get the code field name.
     */
    public function getCodeField(): string
    {
        return $this->codeField ?? 'code';
    }

    /**
     * Get the code prefix.
     */
    public function getCodePrefix(): string
    {
        return $this->codePrefix ?? strtoupper(class_basename($this));
    }

    /**
     * Generate unique code.
     */
    public function generateUniqueCode(): string
    {
        $prefix = $this->getCodePrefix();
        $field = $this->getCodeField();
        
        // Get last code
        $last = $this->where($field, 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->$field, strlen($prefix) + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate code with custom format.
     */
    public function generateCodeWithFormat(string $format, array $params = []): string
    {
        $field = $this->getCodeField();
        $last = $this->orderBy('id', 'desc')->first();
        $number = $last ? (int) substr($last->$field, -3) + 1 : 1;
        
        $params['number'] = str_pad($number, 3, '0', STR_PAD_LEFT);
        $params['date'] = date('Ymd');
        $params['year'] = date('Y');
        $params['month'] = date('m');
        $params['day'] = date('d');
        $params['random'] = Str::random(6);
        
        return vsprintf($format, $params);
    }

    /**
     * Generate employee ID with year.
     */
    public function generateEmployeeId(): string
    {
        $prefix = 'EMP';
        $year = date('Y');
        
        $last = $this->where('employee_id', 'LIKE', $prefix . $year . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->employee_id, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}